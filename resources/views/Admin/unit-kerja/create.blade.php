@extends('Admin.layout')
@section('title')
    Buat Akun Kerja
@endsection
@section('css')
    {{-- Grid.js CSS tidak lagi dibutuhkan --}}
    {{-- <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}"> --}}
@endsection
@section('page-title')
    Buat Akun Kerja
@endsection
@section('body')
<body>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4 p-4">
                        <h4 class="mb-0 text-white">Tambah Unit Kerja Baru</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.unit-kerja.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Unit Kerja</label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kode" class="form-label">Kode Unit Kerja (2 Digit)</label>
                                <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" required maxlength="2">
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="area_kerja_id" class="form-label">Area Kerja</label>
                                <select name="area_kerja_id" id="area_kerja_id" class="form-select @error('area_kerja_id') is-invalid @enderror" required>
                                    <option value="">Pilih Area Kerja</option>
                                    @foreach($areaKerjaOptions as $area)
                                        <option value="{{ $area->id }}" {{ old('area_kerja_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_kerja_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_pemohon" class="form-label">Password Akun Pemohon (Default: password123)</label>
                                <input type="password" name="password_pemohon" id="password_pemohon" class="form-control @error('password_pemohon') is-invalid @enderror" placeholder="Isi untuk override password default">
                                @error('password_pemohon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Akan membuat user dengan username: `uk_kodeunit` dan email: `kodeunit@example.com`.</small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.unit-kerja.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Unit Kerja & Buat Akun Pemohon</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
