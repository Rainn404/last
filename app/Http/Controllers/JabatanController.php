<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{

    
    public function index()
{
    $jabatans = \App\Models\Jabatan::all();
    return view('admin.jabatan.index', compact('jabatans'));
}

    public function create()
    {
        return view('admin.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'level' => 'required|integer|min:1|max:10',
            'status' => 'boolean'
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'deskripsi' => $request->deskripsi,
            'level' => $request->level,
            'status' => $request->status ?? true
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jabatan = Jabatan::where('id_jabatan', $id)->firstOrFail();
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'level' => 'required|integer|min:1|max:10',
            'status' => 'boolean'
        ]);

        $jabatan = Jabatan::where('id_jabatan', $id)->firstOrFail();
        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'deskripsi' => $request->deskripsi,
            'level' => $request->level,
            'status' => $request->status ?? true
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::where('id_jabatan', $id)->firstOrFail();
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $jabatan = Jabatan::where('id_jabatan', $id)->firstOrFail();
        $jabatan->update([
            'status' => !$jabatan->status
        ]);

        $status = $jabatan->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.jabatan.index')
            ->with('success', "Jabatan berhasil $status.");
    }
}