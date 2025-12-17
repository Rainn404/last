<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Exports\MahasiswaExport;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $angkatan = $request->get('angkatan');

        $query = Mahasiswa::query();

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Angkatan filter
        if ($angkatan) {
            $query->where('angkatan', $angkatan);
        }

        $mahasiswa = $query->orderBy('nim')->paginate(15);

        // Get status counts for dashboard
        $statusCounts = [
            'total' => Mahasiswa::count(),
            'aktif' => Mahasiswa::where('status', 'Aktif')->count(),
            'non_aktif' => Mahasiswa::where('status', 'Non-Aktif')->count(),
            'cuti' => Mahasiswa::where('status', 'Cuti')->count(),
        ];

        // Get list of angkatan for filter dropdown
        $angkatanList = Mahasiswa::distinct()
            ->whereNotNull('angkatan')
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return view('admin.mahasiswa.index', compact('mahasiswa', 'statusCounts', 'search', 'status', 'angkatan', 'angkatanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'angkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'status' => ['required', Rule::in(['Aktif', 'Non-Aktif', 'Cuti'])],
            'ipk' => 'nullable|numeric|min:0|max:4',
            'juara' => 'nullable|integer|in:1,3,5,7',
            'tingkatan' => 'nullable|integer|in:1,3,5,7,9',
            'keterangan' => 'nullable|integer|in:1,3',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'angkatan.integer' => 'Angkatan harus berupa angka',
            'angkatan.min' => 'Angkatan minimal tahun 2000',
            'angkatan.max' => 'Angkatan tidak boleh lebih dari tahun depan',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'ipk.numeric' => 'IPK harus berupa angka',
            'ipk.max' => 'IPK maksimal 4.00',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Mahasiswa::create($request->only(['nama', 'nim', 'angkatan', 'status', 'ipk', 'juara', 'tingkatan', 'keterangan']));

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nim' => ['required', 'string', 'max:20', Rule::unique('mahasiswa')->ignore($mahasiswa->id)],
            'angkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'status' => ['required', Rule::in(['Aktif', 'Non-Aktif', 'Cuti'])],
            'ipk' => 'nullable|numeric|min:0|max:4',
            'juara' => 'nullable|integer|in:1,3,5,7',
            'tingkatan' => 'nullable|integer|in:1,3,5,7,9',
            'keterangan' => 'nullable|integer|in:1,3',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi',
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM sudah digunakan mahasiswa lain',
            'angkatan.integer' => 'Angkatan harus berupa angka',
            'angkatan.min' => 'Angkatan minimal tahun 2000',
            'angkatan.max' => 'Angkatan tidak boleh lebih dari tahun depan',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
            'ipk.numeric' => 'IPK harus berupa angka',
            'ipk.max' => 'IPK maksimal 4.00',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $mahasiswa->update($request->only(['nama', 'nim', 'angkatan', 'status', 'ipk', 'juara', 'tingkatan', 'keterangan']));

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswa->delete();

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Show the import form
     */
    public function importView()
    {
        return view('admin.mahasiswa.import');
    }

    /**
     * Import data from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ], [
            'file.required' => 'File Excel wajib dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'file.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diimpor');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Show the export form
     */
    public function exportView()
    {
        return view('admin.mahasiswa.export');
    }

    /**
     * Export data to Excel file
     */
    public function export(Request $request)
    {
        $status = $request->get('status');
        $filename = 'data_mahasiswa_' . date('Y_m_d_His') . '.xlsx';

        return Excel::download(new MahasiswaExport($status), $filename);
    }

    /**
     * Download template Excel
     */
    public function template()
    {
        $filename = 'template_mahasiswa_' . date('Y_m_d') . '.xlsx';

        return Excel::download(new MahasiswaExport(null, true), $filename);
    }
}
