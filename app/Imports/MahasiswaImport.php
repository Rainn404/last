<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;

class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Pastikan NIM selalu berupa string
        $nim = (string) $row['nim'];

        // Cek apakah NIM sudah ada, jika ya update, jika tidak create
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        // Prepare data array dengan semua field
        $data = [
            'nama' => $row['nama'] ?? null,
            'angkatan' => isset($row['angkatan']) ? (int)$row['angkatan'] : null,
            'status' => $row['status'] ?? null,
            'ipk' => isset($row['ipk']) ? (float)$row['ipk'] : null,
            'juara' => isset($row['juara']) ? (int)$row['juara'] : null,
            'tingkatan' => isset($row['tingkatan']) ? (int)$row['tingkatan'] : null,
            'keterangan' => isset($row['keterangan']) ? (int)$row['keterangan'] : null,
        ];

        if ($mahasiswa) {
            // Update data yang sudah ada
            $mahasiswa->update($data);
            return null;
        }

        // Create data baru
        return new Mahasiswa([
            'nim' => $nim,
            ...$data
        ]);
    }

    public function rules(): array
    {
        return [
            'nim' => [
                'required',
                'max:20',
                function ($attribute, $value, $fail) {
                    // Ensure NIM is treated as string and validate it
                    $nimString = (string) $value;
                    if (strlen($nimString) > 20) {
                        $fail('NIM tidak boleh lebih dari 20 karakter');
                    }
                    if (empty(trim($nimString))) {
                        $fail('NIM wajib diisi');
                    }
                },
            ],
            'nama' => [
                'required',
                'string',
                'max:255'
            ],
            'angkatan' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 10)
            ],
            'status' => [
                'nullable',
                Rule::in(['Aktif', 'Non-Aktif', 'Cuti'])
            ],
            'ipk' => [
                'nullable',
                'numeric',
                'min:0',
                'max:4'
            ],
            'juara' => [
                'nullable',
                'integer',
                'min:1'
            ],
            'tingkatan' => [
                'nullable',
                'integer',
                Rule::in([1, 3, 5, 7, 9])
            ],
            'keterangan' => [
                'nullable',
                'integer',
                Rule::in([1, 3])
            ]
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nim.required' => 'NIM wajib diisi',
            'nim.unique' => 'NIM :input sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'email.email' => 'Format email tidak valid',
            'status.in' => 'Status harus berupa: Aktif, Non-Aktif, atau Cuti',
        ];
    }
}