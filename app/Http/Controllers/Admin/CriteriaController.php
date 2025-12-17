<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criterion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CriteriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Criterion::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($type && in_array($type, ['benefit', 'cost'])) {
            $query->where('type', $type);
        }

        $criteria = $query->orderBy('priority')->paginate(10);

        $totalCriteria = Criterion::count();
        $activeCriteria = Criterion::where('status', true)->count();
        $benefitCriteria = Criterion::where('type', 'benefit')->count();
        $costCriteria = Criterion::where('type', 'cost')->count();
        
        $benefitPercentage = $totalCriteria > 0 ? round(($benefitCriteria / $totalCriteria) * 100) : 0;
        $costPercentage = $totalCriteria > 0 ? round(($costCriteria / $totalCriteria) * 100) : 0;

        return view('admin.criteria.index', compact(
            'criteria',
            'totalCriteria',
            'activeCriteria',
            'benefitCriteria',
            'costCriteria',
            'benefitPercentage',
            'costPercentage'
        ));
    }

    public function create()
    {
        return view('admin.criteria.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ], [
            'name.required' => 'Nama kriteria wajib diisi',
            'type.required' => 'Tipe kriteria wajib dipilih',
            'type.in' => 'Tipe kriteria harus benefit atau cost'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa data yang dimasukkan.');
        }

        try {
            // Auto-generate kode dengan pattern C1, C2, C3, dst
            $existingCodes = Criterion::pluck('code')->toArray();
            
            $maxNumber = 0;
            foreach ($existingCodes as $existingCode) {
                if (preg_match('/^C(\d+)$/', $existingCode, $matches)) {
                    $num = (int)$matches[1];
                    if ($num > $maxNumber) {
                        $maxNumber = $num;
                    }
                }
            }
            
            $nextNumber = $maxNumber + 1;
            $code = 'C' . $nextNumber;

            Criterion::create([
                'name' => $request->name,
                'code' => $code,
                'type' => $request->type,
                'description' => null,
                'priority' => $nextNumber,
                'weight' => 0,
                'status' => true
            ]);

            return redirect()->route('admin.criteria.index')
                ->with('success', 'Kriteria berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kriteria. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $criterion = Criterion::findOrFail($id);
        return view('admin.criteria.show', compact('criterion'));
    }

    public function edit($id)
    {
        $criterion = Criterion::findOrFail($id);
        return view('admin.criteria.edit', compact('criterion'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
        ], [
            'name.required' => 'Nama kriteria wajib diisi',
            'type.required' => 'Tipe kriteria wajib dipilih',
            'type.in' => 'Tipe kriteria harus benefit atau cost'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa data yang dimasukkan.');
        }

        try {
            $criterion = Criterion::findOrFail($id);
            
            $criterion->update([
                'name' => $request->name,
                'type' => $request->type
            ]);

            return redirect()->route('admin.criteria.index')
                ->with('success', 'Kriteria berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kriteria. Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $criterion = Criterion::findOrFail($id);
            $criterionName = $criterion->name;

            $criterion->delete();

            return redirect()->route('admin.criteria.index')
                ->with('success', "Kriteria '{$criterionName}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus kriteria. Error: ' . $e->getMessage());
        }
    }
}

