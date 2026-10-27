@extends('Pemohon.layout')

@section('title', 'Sub-Registration')

@section('page-title', 'Berkas Pendaftaran')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Formulir Data Tamu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sub-registration.submit', ['submissionId' => $submission->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_tamu" class="form-label">Nama Tamu</label>
                            <input type="text" name="nama_tamu" id="nama_tamu" class="form-control @error('nama_tamu') is-invalid @enderror" required>
                            @error('nama_tamu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" name="role" id="role" class="form-control" value="{{ $submission->role->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label id="file1Label" for="file1" class="form-label">File 1:</label>
                            <input type="file" name="file1" id="file1" class="form-control @error('file1') is-invalid @enderror" required>
                            @error('file1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" id="file1Hint"></small>
                        </div>

                        <div class="mb-3">
                            <label id="file2Label" for="file2" class="form-label">File 2:</label>
                            <input type="file" name="file2" id="file2" class="form-control @error('file2') is-invalid @enderror" required>
                            @error('file2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" id="file2Hint"></small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <a href="{{ route('daftar-tamu', ['submissionId' => $submission->id]) }}" class="btn btn-secondary">Kembali ke Daftar Tamu</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const role = "{{ strtolower($submission->role->name) }}";
        const file1Label = document.getElementById('file1Label');
        const file2Label = document.getElementById('file2Label');
        const file1Hint = document.getElementById('file1Hint');
        const file2Hint = document.getElementById('file2Hint');

        if (role === 'visitor') {
            file1Label.innerText = 'File 1 (KTP):';
            file2Label.innerText = 'File 2 (Sertifikat Booster):';
            file1Hint.innerText = 'Unggah KTP tamu.';
            file2Hint.innerText = 'Unggah Sertifikat Booster.';
        } else if (role === 'karyawan') {
            file1Label.innerText = 'File 1 (Safety Induction):';
            file2Label.innerText = 'File 2 (PDPE):';
            file1Hint.innerText = 'Unggah dokumen Safety Induction.';
            file2Hint.innerText = 'Unggah dokumen PDPE.';
        } else {
            file1Label.innerText = 'File 1:';
            file2Label.innerText = 'File 2:';
        }
    });
</script>
@endsection
