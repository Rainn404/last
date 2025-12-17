@php $layout = request()->is('admin-panel/*') ? 'layouts.admin-panel.app' : 'layouts.admin.app'; @endphp
@extends($layout)

@section('title', 'Tambah Prestasi - HIMA Sistem Manajemen')

@section('content')
    @include('admin.prestasi.form', [
        'title' => 'Tambah Prestasi',
        'action' => route('admin.prestasi.store'),
        'prestasi' => null
    ])
@endsection