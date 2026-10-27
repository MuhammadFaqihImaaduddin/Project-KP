@extends('Pemohon.layout')

@section('title', 'Edit Formulir')
@section('page-title', 'Edit Formulir')

@section('body')
<body>
@endsection

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-4 fw-bold text-center">Edit Data Tamu</h4>

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

                    <form action="{{ route('daftar_tamu.update', ['submissionId' => $submission->id, 'dataSubmissionId' => $dataSubmission->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_tamu" class="form-label">Nama Tamu</label>
                            {{-- Menggunakan old() agar nilai tidak hilang saat validasi gagal --}}
                            <input type="text" name="nama_tamu" id="nama_tamu" class="form-control @error('nama_tamu') is-invalid @enderror" value="{{ old('nama_tamu', $dataSubmission->nama_tamu) }}" required>
                            @error('nama_tamu')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Jika Visitor --}}
                        @if ($dataSubmission->role_id == 4)
                            <div class="mb-3">
                                <label class="form-label">KTP (PDF, JPG, JPEG, PNG - Max 10MB)</label>
                                @if ($dataSubmission->ktpFile)
                                    <div class="mb-1">
                                        <a href="{{ asset('storage/' . $dataSubmission->ktpFile->file_path) }}" target="_blank" class="text-primary">Lihat KTP Saat Ini</a>
                                    </div>
                                @endif
                                <input type="file" name="ktp" class="form-control @error('ktp') is-invalid @enderror">
                                @error('ktp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sertifikat Booster (PDF, JPG, JPEG, PNG - Max 10MB)</label>
                                @if ($dataSubmission->boosterFile)
                                    <div class="mb-1">
                                        <a href="{{ asset('storage/' . $dataSubmission->boosterFile->file_path) }}" target="_blank" class="text-primary">Lihat Booster Saat Ini</a>
                                    </div>
                                @endif
                                <input type="file" name="booster" class="form-control @error('booster') is-invalid @enderror">
                                @error('booster')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        {{-- Jika Karyawan --}}
                        @if ($dataSubmission->role_id == 5)
                            <div class="mb-3">
                                <label class="form-label">Safety Induction (PDF, JPG, JPEG, PNG - Max 10MB)</label>
                                @if ($dataSubmission->safetyInductionFile)
                                    <div class="mb-1">
                                        <a href="{{ asset('storage/' . $dataSubmission->safetyInductionFile->file_path) }}" target="_blank" class="text-primary">Lihat Induction Saat Ini</a>
                                    </div>
                                @endif
                                <input type="file" name="induction" class="form-control @error('induction') is-invalid @enderror">
                                @error('induction')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">PDPE (PDF, JPG, JPEG, PNG - Max 10MB)</label>
                                @if ($dataSubmission->pdpeFile)
                                    <div class="mb-1">
                                        <a href="{{ asset('storage/' . $dataSubmission->pdpeFile->file_path) }}" target="_blank" class="text-primary">Lihat PDPE Saat Ini</a>
                                    </div>
                                @endif
                                <input type="file" name="pdpe" class="form-control @error('pdpe') is-invalid @enderror">
                                @error('pdpe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('daftar-tamu', ['submissionId' => $submission->id]) }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
