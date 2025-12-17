<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSawSeeder extends Seeder
{
    /**
     * Seed data mahasiswa untuk ranking SAW
     */
    public function run(): void
    {
        $mahasiswa = [
            [
                'nim' => '2021001',
                'nama' => 'MARYANA',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 3.49,
                'juara' => 5,       // Juara 2
                'tingkatan' => 3,   // Kabupaten
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021002',
                'nama' => 'MUHAMMAD ZAKI',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 2.66,
                'juara' => 7,       // Juara 1
                'tingkatan' => 7,   // Nasional
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021003',
                'nama' => 'TITIN SADIYAH',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 3.52,
                'juara' => 1,       // Anggota
                'tingkatan' => 7,   // Nasional
                'keterangan' => 3,  // Akademik
            ],
            [
                'nim' => '2021004',
                'nama' => 'SITI NURHALIZA',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 2.59,
                'juara' => 3,       // Juara 3
                'tingkatan' => 5,   // Provinsi
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021005',
                'nama' => 'M. REZA FAHLEVI',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 2.67,
                'juara' => 5,       // Juara 2
                'tingkatan' => 5,   // Provinsi
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021006',
                'nama' => 'Reihan Fariza',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 3.03,
                'juara' => 7,       // Juara 1
                'tingkatan' => 5,   // Provinsi
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021007',
                'nama' => 'Annisa',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 3.62,
                'juara' => 7,       // Juara 1
                'tingkatan' => 5,   // Provinsi
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021008',
                'nama' => 'Noor Ridwansyah',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 3.17,
                'juara' => 3,       // Juara 3
                'tingkatan' => 7,   // Nasional
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021009',
                'nama' => 'Nova Dwi Kesumawati',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 2.92,
                'juara' => 7,       // Juara 1
                'tingkatan' => 7,   // Nasional
                'keterangan' => 1,  // Non-Akademik
            ],
            [
                'nim' => '2021010',
                'nama' => 'Ahmad Nasrullah Yusuf',
                'angkatan' => 2021,
                'status' => 'Aktif',
                'ipk' => 3.42,
                'juara' => 5,       // Juara 2
                'tingkatan' => 7,   // Nasional
                'keterangan' => 1,  // Non-Akademik
            ],
        ];

        foreach ($mahasiswa as $mhs) {
            DB::table('mahasiswa')->updateOrInsert(
                ['nim' => $mhs['nim']],
                array_merge($mhs, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
