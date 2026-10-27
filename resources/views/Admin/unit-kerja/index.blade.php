@extends('Admin.layout')
@section('title')
    Buat Akun Kerja
@endsection
@section('css')
    {{-- Grid.js CSS tidak lagi dibutuhkan --}}
    {{-- <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}"> --}}
@endsection
@section('page-title')
    Daftar Unit Kerja
@endsection
@section('body')
<body>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-white">Daftar Unit Kerja</h4>
                <a href="{{ route('admin.unit-kerja.create') }}" class="btn btn-success">
                    <i class="mdi mdi-plus me-1"></i> Tambah Unit Kerja Baru
                </a>
            </div>
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($unitKerjaList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Nama Unit Kerja</th>
                                    <th>Kode</th>
                                    <th>Area Kerja</th>
                                    <th>Username Pemohon</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitKerjaList as $index => $unitKerja)
                                    <tr>
                                        <td>{{ $unitKerjaList->firstItem() + $index }}</td>
                                        <td>{{ $unitKerja->nama }}</td>
                                        <td>{{ $unitKerja->kode }}</td>
                                        <td>{{ optional($unitKerja->areaKerja)->nama ?? 'N/A' }}</td>
                                        <td>{{ optional($unitKerja->userPemohon)->username ?? 'Belum Terkait' }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.unit-kerja.edit', $unitKerja->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bx bx-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.unit-kerja.destroy', $unitKerja->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Unit Kerja ini? Ini akan juga menghapus user pemohon terkait dan data pengajuan mungkin terpengaruh!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $unitKerjaList->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info text-center mt-4" role="alert">
                        Belum ada Unit Kerja yang terdaftar.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
