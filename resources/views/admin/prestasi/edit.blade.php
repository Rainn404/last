@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Edit Prestasi - HIMA Sistem Manajemen')

@section('content')
    @include('admin.prestasi.form', [
        'title' => 'Edit Prestasi',
        'action' => route('admin.prestasi.update', $prestasi->id_prestasi),
        'prestasi' => $prestasi
    ])
@endsection