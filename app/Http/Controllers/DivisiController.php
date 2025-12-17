<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Divisi;
use App\Models\AnggotaHima;

class DivisiController extends Controller
{
    // ============================
    // PUBLIC HALAMAN LIST DIVISI
    // ============================
    public function publicIndex()
    {
        $divisis = Divisi::where('status', true)
            ->withCount('anggotaHima')
            ->orderBy('id_divisi', 'ASC') // <-- PERBAIKAN
            ->get();

        return view('divisi', compact('divisis'));
    }

    // ============================
    // PUBLIC DETAIL DIVISI
    // ============================
    public function publicShow(string $id)
    {
        $divisi = Divisi::where('status', true)
            ->withCount('anggotaHima')
            ->with(['anggotaHima' => function ($query) {
                $query->where('status', true)->with('jabatan');
            }])
            ->where('id_divisi', $id)
            ->first();

        if (!$divisi) {
            return redirect()->route('divisi')
                ->with('error', 'Data divisi tidak ditemukan.');
        }

        return view('divisi-detail', compact('divisi'));
    }

    // ============================
    // ADMIN LIST DIVISI
    // ============================
    public function index()
    {
        $divisis = Divisi::withCount('anggotaHima')
            ->orderBy('id_divisi', 'ASC') // <-- PERBAIKAN
            ->get();

        return view('admin.divisi.index', compact('divisis'));
    }

    // ============================
    // ADMIN CREATE PAGE
    // ============================
    public function create()
    {
        return view('admin.divisi.create');
    }

    // ============================
    // ADMIN STORE
    // ============================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_divisi'   => 'required|string|max:255|unique:divisis,nama_divisi',
            'ketua_divisi'  => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'color'         => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi data.');
        }

        Divisi::create([
            'nama_divisi'   => $request->nama_divisi,
            'ketua_divisi'  => $request->ketua_divisi,
            'deskripsi'     => $request->deskripsi,
            'status'        => 1,
            'color'         => $request->color ?? '#1a73e8',
        ]);

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Divisi baru berhasil ditambahkan!');
    }

    // ============================
    // ADMIN SHOW DETAIL DIVISI
    // ============================
    public function show(string $id)
    {
        $divisi = Divisi::withCount('anggotaHima')
            ->with(['anggotaHima' => function ($query) {
                $query->with('jabatan');
            }])
            ->find($id);

        if (!$divisi) {
            return redirect()->route('admin.divisi.index')
                ->with('error', 'Data divisi tidak ditemukan.');
        }

        return view('admin.divisi.view', compact('divisi'));
    }

    // ============================
    // ADMIN EDIT FORM
    // ============================
    public function edit(string $id)
    {
        $divisi = Divisi::find($id);

        if (!$divisi) {
            return redirect()->route('admin.divisi.index')
                ->with('error', 'Data divisi tidak ditemukan.');
        }

        return view('admin.divisi.edit', compact('divisi'));
    }

    // ============================
    // ADMIN UPDATE
    // ============================
    public function update(Request $request, string $id)
    {
        $divisi = Divisi::find($id);

        if (!$divisi) {
            return redirect()->route('admin.divisi.index')
                ->with('error', 'Data divisi tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_divisi'   => 'required|string|max:255|unique:divisis,nama_divisi,' . $id . ',id_divisi',
            'ketua_divisi'  => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'nullable|boolean',
            'color'         => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi.');
        }

        $divisi->update([
            'nama_divisi'   => $request->nama_divisi,
            'ketua_divisi'  => $request->ketua_divisi,
            'deskripsi'     => $request->deskripsi,
            'status'        => $request->status ?? true,
            'color'         => $request->color ?? '#1a73e8',
        ]);

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Data divisi berhasil diperbarui!');
    }

    // ============================
    // ADMIN DELETE
    // ============================
    public function destroy(string $id)
    {
        $divisi = Divisi::find($id);

        if (!$divisi) {
            return redirect()->route('admin.divisi.index')
                ->with('error', 'Data divisi tidak ditemukan.');
        }

        if ($divisi->anggotaHima()->count() > 0) {
            return redirect()->route('admin.divisi.index')
                ->with('error', 'Tidak dapat menghapus divisi yang masih memiliki anggota.');
        }

        $divisi->delete();

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Data divisi berhasil dihapus!');
    }
}
