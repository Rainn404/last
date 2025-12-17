<?php

namespace App\Services;

use App\Models\User;
use App\Models\AnggotaHima;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAnggotaService
{
    /**
     * Create user account and anggota record for accepted registration.
     *
     * @param Pendaftaran $pendaftaran
     * @return array ['success' => bool, 'user' => User|null, 'anggota' => AnggotaHima|null, 'message' => string]
     */
    public function createFromPendaftaran(Pendaftaran $pendaftaran): array
    {
        try {
            DB::beginTransaction();

            // 1. Reload pendaftaran with user relationship
            $pendaftaran = $pendaftaran->fresh('user');

            // 2. Check if user already exists by email or nim
            $email = $pendaftaran->user?->email ?? $this->generateEmail($pendaftaran->nim);
            
            $existingUser = User::where('email', $email)
                ->orWhere('email', $this->generateEmail($pendaftaran->nim))
                ->first();

            if ($existingUser) {
                // If user exists but not linked to pendaftaran, link them
                if (!$pendaftaran->id_user) {
                    $pendaftaran->update(['id_user' => $existingUser->id]);
                }
                
                DB::commit();
                
                return [
                    'success' => false,
                    'user' => $existingUser,
                    'anggota' => null,
                    'message' => 'User sudah terdaftar'
                ];
            }

            // 3. Create User account with role 'anggota'
            $password = Str::random(16);

            $user = User::create([
                'name' => $pendaftaran->nama,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            // 4. Create AnggotaHima record
            $anggota = AnggotaHima::create([
                'id_user' => $user->id,
                'nim' => $pendaftaran->nim,
                'nama' => $pendaftaran->nama,
                'id_divisi' => $pendaftaran->id_divisi,
                'id_jabatan' => $pendaftaran->id_jabatan,
                'semester' => $pendaftaran->semester ?? 1,
                'status' => true, // Active status
            ]);

            // 5. Update pendaftaran to link with new user
            $pendaftaran->update([
                'id_user' => $user->id,
            ]);

            DB::commit();

            return [
                'success' => true,
                'user' => $user,
                'anggota' => $anggota,
                'password' => $password, // For notification
                'message' => 'User dan anggota berhasil dibuat'
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'user' => null,
                'anggota' => null,
                'message' => 'Gagal membuat user/anggota: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate email from NIM if not provided.
     *
     * @param string $nim
     * @return string
     */
    private function generateEmail(string $nim): string
    {
        return 'anggota_' . strtolower($nim) . '@hima-ti.local';
    }

    /**
     * Check if anggota already exists for this pendaftaran.
     *
     * @param Pendaftaran $pendaftaran
     * @return bool
     */
    public function anggotaExists(Pendaftaran $pendaftaran): bool
    {
        return AnggotaHima::where('nim', $pendaftaran->nim)->exists();
    }
}
