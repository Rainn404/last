@extends('layouts.app_user')

@section('title','Edit Profil')

@section('user-content')
@push('styles')
<style>
    /* Modern Edit Profile Layout - Inspired by Popular Websites */
    .edit-profile-container { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
    .edit-profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; padding: 3rem 2rem; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; overflow: hidden; }
    .edit-profile-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); opacity: 0.1; }
    .edit-profile-avatar { width: 120px; height: 120px; border-radius: 50%; border: 6px solid rgba(255,255,255,0.3); object-fit: cover; box-shadow: 0 8px 25px rgba(0,0,0,0.15); position: relative; z-index: 2; }
    .edit-profile-title { font-size: 2.5rem; font-weight: 700; margin: 1.5rem 0 0.5rem 0; position: relative; z-index: 2; }
    .edit-profile-subtitle { font-size: 1.2rem; opacity: 0.9; margin-bottom: 1rem; position: relative; z-index: 2; }

    .edit-profile-content { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 2rem; }
    @media (max-width: 768px) { .edit-profile-content { grid-template-columns: 1fr; } }

    .edit-profile-sidebar { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); height: fit-content; position: sticky; top: 2rem; }
    .edit-profile-main { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }

    .section-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; display: flex; align-items: center; }
    .section-title i { margin-right: 0.75rem; color: #667eea; }

    .form-group { margin-bottom: 1.5rem; }
    .form-label { font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block; font-size: 0.95rem; }
    .form-control { border: 2px solid #e5e7eb; border-radius: 8px; padding: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #fafafa; }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); outline: none; background: white; }
    .form-control:hover { border-color: #d1d5db; }

    .avatar-section { text-align: center; margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 12px; position: relative; }
    .avatar-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(102,126,234,0.1)"/></svg>'); border-radius: 12px; }
    .avatar-preview { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; margin: 0 auto 1.5rem; display: block; border: 6px solid white; box-shadow: 0 8px 25px rgba(0,0,0,0.1); position: relative; z-index: 1; }
    .avatar-upload-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 2rem; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102,126,234,0.3); }
    .avatar-upload-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102,126,234,0.4); }
    .avatar-remove-btn { background: #dc2626; color: white; border: none; padding: 0.5rem 1rem; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-left: 0.5rem; }
    .avatar-remove-btn:hover { background: #b91c1c; transform: translateY(-1px); }

    .action-buttons { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; flex-wrap: wrap; }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 0.875rem 2.5rem; border-radius: 50px; font-weight: 600; color: white; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102,126,234,0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102,126,234,0.4); }
    .btn-secondary { border: 2px solid #e5e7eb; background: transparent; color: #6b7280; padding: 0.875rem 2.5rem; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
    .btn-secondary:hover { background: #f9fafb; border-color: #d1d5db; }

    .alert { border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .alert-danger { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; }
    .text-danger { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }

    .file-input-wrapper { position: relative; display: inline-block; width: 100%; }
    .file-input { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
    .file-input-label { display: flex; align-items: center; justify-content: center; padding: 0.75rem; border: 2px dashed #d1d5db; border-radius: 8px; background: #fafafa; cursor: pointer; transition: all 0.3s ease; }
    .file-input-label:hover { border-color: #667eea; background: #f0f4ff; }
    .file-input-label i { margin-right: 0.5rem; color: #6b7280; }

    .stats-section { margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; }
    .stat-item { text-align: center; padding: 1rem; background: #f8fafc; border-radius: 8px; }
    .stat-number { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
    .stat-label { color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; }
</style>
@endpush

<div class="edit-profile-container">
    <!-- Header Section -->
    <div class="edit-profile-header">
        <div class="text-center">
            @if($user->avatar)
                <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Profile Avatar" class="edit-profile-avatar">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=667eea&color=fff&size=120" alt="Profile Avatar" class="edit-profile-avatar">
            @endif
            <h1 class="edit-profile-title">Edit Profil</h1>
            <p class="edit-profile-subtitle">Perbarui informasi profil Anda</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="edit-profile-content">
        <!-- Sidebar -->
        <div class="edit-profile-sidebar">
            <!-- Avatar Section -->
            <div class="avatar-section">
                <h4 style="color: #374151; margin-bottom: 1.5rem; font-weight: 700;">Foto Profil</h4>
                @if($user->avatar)
                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Current Avatar" class="avatar-preview" id="avatarPreview">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=667eea&color=fff&size=140" alt="Default Avatar" class="avatar-preview" id="avatarPreview">
                @endif

                <form action="{{ route('profile.avatar.upload') }}" method="post" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    <div class="file-input-wrapper">
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="file-input">
                        <label for="avatarInput" class="file-input-label">
                            <i class="fas fa-camera"></i>
                            Pilih Foto Baru
                        </label>
                    </div>
                    <button class="avatar-upload-btn" type="submit" style="margin-top: 1rem; width: 100%;">
                        <i class="fas fa-upload me-2"></i>Upload Foto
                    </button>
                </form>

                @if($user->avatar)
                    <form action="{{ route('profile.avatar.remove') }}" method="post" style="margin-top: 1rem;">
                        @csrf
                        @method('delete')
                        <button class="avatar-remove-btn" type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')">
                            <i class="fas fa-trash me-2"></i>Hapus Foto
                        </button>
                    </form>
                @endif

                <small style="display: block; margin-top: 1rem; color: #6b7280; font-size: 0.875rem;">Format: JPG, PNG, GIF. Maksimal 2MB</small>
            </div>

            <!-- Quick Stats -->
            <div class="stats-section">
                <h5 style="color: #374151; margin-bottom: 1rem; font-weight: 600;">Statistik Akun</h5>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ \Carbon\Carbon::parse($user->created_at)->diffInDays() }}</div>
                        <div class="stat-label">Hari Bergabung</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ ucfirst($user->role) === 'Admin' ? 'A' : 'U' }}</div>
                        <div class="stat-label">Tipe Akun</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="edit-profile-main">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Ada Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="section-title">
                <i class="fas fa-user-edit"></i>
                Informasi Pribadi
            </div>

            <form action="{{ route('profile.update') }}" method="post">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-user me-2"></i>Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required placeholder="Masukkan nama lengkap Anda">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope me-2"></i>Alamat Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required placeholder="Masukkan alamat email Anda">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-shield-alt me-2"></i>Role Akun
                    </label>
                    <input type="text" value="{{ ucfirst($user->role) }}" class="form-control" readonly style="background: #f8fafc; cursor: not-allowed;">
                    <small style="color: #6b7280; font-size: 0.875rem;">Role akun tidak dapat diubah</small>
                </div>

                <div class="action-buttons">
                    <button class="btn-primary" type="submit">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');

    if (input && preview) {
        input.addEventListener('change', function(e){
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(ev){
                preview.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    // Form submission feedback
    const avatarForm = document.getElementById('avatarForm');
    if (avatarForm) {
        avatarForm.addEventListener('submit', function(){
            const submitBtn = avatarForm.querySelector('.avatar-upload-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endpush
