<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" class="form-control" name="nama" value="{{ $pendaftaran->nama }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">NIM *</label>
            <input type="text" class="form-control" name="nim" value="{{ $pendaftaran->nim }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Semester *</label>
            <input type="number" class="form-control" name="semester" value="{{ $pendaftaran->semester }}" min="1" max="14" required>
        </div>
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" class="form-control" name="no_hp" value="{{ $pendaftaran->no_hp }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Status Pendaftaran *</label>
            <select class="form-select" name="status_pendaftaran" required>
                <option value="pending" {{ $pendaftaran->status_pendaftaran == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diterima" {{ $pendaftaran->status_pendaftaran == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ditolak" {{ $pendaftaran->status_pendaftaran == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Divisi</label>
            <select class="form-select" name="id_divisi">
                <option value="">Pilih Divisi</option>
                @foreach($divisi as $div)
                <option value="{{ $div->id }}" {{ $pendaftaran->id_divisi == $div->id ? 'selected' : '' }}>{{ $div->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <select class="form-select" name="id_jabatan">
                <option value="">Pilih Jabatan</option>
                @foreach($jabatan as $jab)
                <option value="{{ $jab->id }}" {{ $pendaftaran->id_jabatan == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Alasan Penolakan</label>
            <textarea class="form-control" name="notes" rows="3">{{ $pendaftaran->notes }}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <label class="form-label">Alasan Mendaftar *</label>
            <textarea class="form-control" name="alasan_mendaftar" rows="3" required>{{ $pendaftaran->alasan_mendaftar }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Pengalaman Organisasi</label>
            <textarea class="form-control" name="pengalaman" rows="3">{{ $pendaftaran->pengalaman }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Kemampuan/Keterampilan</label>
            <textarea class="form-control" name="skill" rows="3">{{ $pendaftaran->skill }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Dokumen Pendaftaran</label>
            <input type="file" class="form-control" name="dokumen" accept=".pdf">
            @if($pendaftaran->dokumen)
            <small class="text-muted">File saat ini: <a href="{{ asset('storage/' . $pendaftaran->dokumen) }}" target="_blank">Lihat</a></small>
            @endif
        </div>
    </div>
</div>