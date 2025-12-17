<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Berita;
use App\Models\Komentar;

class BeritaController extends Controller
{
    /* =========================================================
     * ADMIN AREA
     * ========================================================= */

    public function index()
    {
        $berita = Berita::orderByDesc('Tanggal_berita')
                        ->orderByDesc('Id_berita')
                        ->get();

        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:200',
            'isi'          => 'required|string',
            'nama_penulis' => 'nullable|string|max:100',
            'tanggal'      => 'nullable|date',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('berita', 'public');
        }

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.show', compact('berita'));
    }

    // Tambahan khusus tombol “Lihat” di admin (tidak ke halaman publik)
    public function adminShow($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.show', compact('berita'));
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:200',
            'isi'          => 'required|string',
            'nama_penulis' => 'nullable|string|max:100',
            'tanggal'      => 'nullable|date',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $berita = Berita::findOrFail($id);

        if ($request->hasFile('foto')) {
            if (!empty($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }
            $validated['foto'] = $request->file('foto')->store('berita', 'public');
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if (!empty($berita->foto)) {
            Storage::disk('public')->delete($berita->foto);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    /* =========================================================
     * FRONTEND / PUBLIK AREA
     * ========================================================= */

    // GET /berita → hanya 3 berita utama
    public function publicIndex()
    {
        $highlight = Berita::orderByDesc('Tanggal_berita')
                            ->orderByDesc('Id_berita')
                            ->take(3)
                            ->get();

        return view('berita.index', compact('highlight'));
    }

    // GET /berita-lainnya → semua berita
    public function lainnya()
    {
        $beritaLainnya = Berita::orderByDesc('Tanggal_berita')
                                ->orderByDesc('Id_berita')
                                ->get();

        return view('berita.lainnya', compact('beritaLainnya'));
    }

    // GET /berita/{id} → halaman detail berita publik
    public function publicShow($id)
    {
        $berita = Berita::with('komentar')->findOrFail($id);
        return view('berita.show', compact('berita'));
    }

    /* =========================================================
     * KOMENTAR PUBLIK
     * ========================================================= */

    public function publicCommentStore(Request $request, $id)
    {
        $request->validate([
            'nama' => 'nullable|string|max:100',
            'isi'  => 'required|string|max:2000',
        ]);

        $berita = Berita::findOrFail($id);

        $berita->komentar()->create([
            'nama' => $request->filled('nama') ? $request->nama : 'Anonim',
            'isi'  => $request->isi,
        ]);

        return redirect()->to(route('berita.show', $id) . '#komentar')
                         ->with('success', 'Komentar berhasil dikirim.');
    }

    public function publicCommentUpdate(Request $request, $id, $komentarId)
    {
        $request->validate([
            'nama' => 'nullable|string|max:100',
            'isi'  => 'required|string|max:2000',
        ]);

        $berita   = Berita::findOrFail($id);
        $komentar = $berita->komentar()->where('id', $komentarId)->firstOrFail();

        $komentar->update([
            'nama' => $request->filled('nama') ? $request->nama : 'Anonim',
            'isi'  => $request->isi,
        ]);

        return redirect()->to(route('berita.show', $id) . '#komentar')
                         ->with('success', 'Komentar berhasil diperbarui.');
    }

    public function publicCommentDestroy($id, $komentarId)
    {
        $berita   = Berita::findOrFail($id);
        $komentar = $berita->komentar()->where('id', $komentarId)->firstOrFail();

        $komentar->delete();

        return redirect()->to(route('berita.show', $id) . '#komentar')
                         ->with('success', 'Komentar berhasil dihapus.');
    }
}
