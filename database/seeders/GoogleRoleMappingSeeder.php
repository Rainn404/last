<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GoogleRoleMapping;

class GoogleRoleMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mappings = [
            [
                'email_pattern' => 'himapolitala.ti@gmail.com',
                'role' => 'super_admin',
                'priority' => 200,
                'description' => 'Super Admin HIMA TI Politala'
            ],
            [
                'email_pattern' => 'gelang307@gmail.com',
                'role' => 'admin',
                'priority' => 190,
                'description' => 'Pengurus HIMA (Gelang307)'
            ],
            [
                'email_pattern' => '*@mhs.politala.ac.id',
                'role' => 'mahasiswa',
                'priority' => 85,
                'description' => 'Mahasiswa Politala (subdomain mhs)'
            ],
            [
                'email_pattern' => '*@hima-ti.politala.ac.id',
                'role' => 'admin',
                'priority' => 90,
                'description' => 'Admin dengan email domain HIMA TI Politala'
            ],
            [
                'email_pattern' => '*@politala.ac.id',
                'role' => 'mahasiswa',
                'priority' => 80,
                'description' => 'Mahasiswa Politala'
            ],
            [
                'email_pattern' => '*@admin.hima-ti.com',
                'role' => 'admin',
                'priority' => 70,
                'description' => 'Admin dengan domain khusus'
            ],
        ];

        foreach ($mappings as $mapping) {
            GoogleRoleMapping::updateOrCreate(
                ['email_pattern' => $mapping['email_pattern']],
                $mapping
            );
        }
    }
}
