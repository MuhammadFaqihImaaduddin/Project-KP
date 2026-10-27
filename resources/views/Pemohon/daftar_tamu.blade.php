@extends('Pemohon.layout')

@section('title', 'Daftar Tamu')

@section('page-title', 'Daftar Tamu')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-white rounded-top-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 text-black">Detail Pengajuan Tamu</h4>
                @php
                    $mainSubmissionStatusId = optional($submission->status)->id;
                    $mainSubmissionStatusName = optional($submission->status)->name;
                    $statusColorClass = '';
                    switch ($mainSubmissionStatusId) {
                        case 1: $statusColorClass = 'bg-warning text-dark'; break;
                        case 2: $statusColorClass = 'bg-success'; break;
                        case 3: $statusColorClass = 'bg-danger'; break;
                        default: $statusColorClass = 'bg-secondary'; break;
                    }
                @endphp
                <span class="badge {{ $statusColorClass }} fs-6">{{ $mainSubmissionStatusName }}</span>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-0">
                        Pengajuan Oleh: {{ optional($submission->user)->username ?? 'N/A' }} - {{ optional($submission)->NPK ?? 'N/A' }}
                        | Tujuan: {{ optional($submission->unitKerja)->nama ?? 'N/A' }}
                    </h5>
                    @if(optional($submission)->keterangan_approval)
                        <p class="mb-0 mt-2 text-muted">
                            Keterangan Approval: {{ $submission->keterangan_approval }}
                        </p>
                    @endif
                </div>
                <div class="col-md-4 text-end">
                    {{-- Tombol "Tambah Tamu" hanya muncul jika status masih Pending --}}
                    @if ($mainSubmissionStatusId == 1) {{-- Status Pending --}}
                        <a href="{{ route('sub-registration.form', ['submissionId' => $submission->id]) }}" class="btn btn-primary me-2"> {{-- Tambah me-2 untuk spasi --}}
                            <i class="bx bx-plus me-1"></i> Tambah Tamu
                        </a>
                    @else
                        <button class="btn btn-primary disabled me-2" disabled>
                            <i class="bx bx-plus me-1"></i> Tambah Tamu
                        </button>
                    @endif

                    {{-- Tombol "Cetak Konfirmasi" hanya muncul jika status Approved --}}
                    @if ($mainSubmissionStatusId == 2 || $mainSubmissionStatusId == 4) {{-- Status Approved (ID 2 atau 4) --}}
                        <a href="{{ route('Pemohon.konfirmasi-persetujuan', ['submissionId' => $submission->id]) }}" class="btn btn-info text-white">
                            <i class="bx bx-printer me-1"></i> Cetak Konfirmasi
                        </a>
                    @else
                        <button class="btn btn-info disabled" disabled>
                            <i class="bx bx-printer me-1"></i> Cetak Konfirmasi
                        </button>
                    @endif
                    <small class="d-block text-muted mt-1">
                        @if ($mainSubmissionStatusId == 1)
                            Pengajuan belum diproses.
                        @else
                            Pengajuan sudah diproses.
                        @endif
                    </small>
                </div>
            </div>
        </div> {{-- Akhir card-header --}}

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

            @if ($submission->dataSubmission->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </th>
                                <th>Nama Tamu</th>
                                <th>Role</th>
                                <th>KTP / Safety Induction</th>
                                <th>Booster / PDPE</th>
                                <th style="width: 120px;" class="fixed-action-column">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submission->dataSubmission as $data)
                                @php
                                    $isVisitor = optional($data->role)->id == 5;
                                    $file1 = $isVisitor ? optional($data->ktpFile) : optional($data->safetyInductionFile);
                                    $file2 = $isVisitor ? optional($data->boosterFile) : optional($data->pdpeFile);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input" id="check{{ $data->id }}">
                                    </td>
                                    <td>{{ $data->nama_tamu }}</td>
                                    <td>{{ optional($data->role)->name ?? '-' }}</td>
                                    <td>
                                        @if ($file1 && $file1->file_path)
                                            <a href="{{ asset('storage/' . $file1->file_path) }}" target="_blank" class="text-primary text-decoration-none">
                                                <i class="bx bx-link-external me-1"></i> {{ $isVisitor ? 'KTP' : 'Safety Induction' }} ({{ $file1->file_name }})
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($file2 && $file2->file_path)
                                            <a href="{{ asset('storage/' . $file2->file_path) }}" target="_blank" class="text-primary text-decoration-none">
                                                <i class="bx bx-link-external me-1"></i> {{ $isVisitor ? 'Sertifikat Booster' : 'PDPE' }} ({{ $file2->file_name }})
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            {{-- Tombol Edit dan Hapus hanya aktif jika pengajuan masih Pending --}}
                                            @if ($mainSubmissionStatusId == 1)
                                                <a href="{{ route('daftar_tamu.edit', ['submissionId' => $submission->id, 'dataSubmissionId' => $data->id]) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bx bx-pencil"></i>
                                                </a>
                                                <form action="{{ route('daftar_tamu.delete', ['submissionId' => $submission->id, 'dataSubmissionId' => $data->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary disabled" disabled title="Tidak dapat diedit">
                                                    <i class="bx bx-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary disabled" disabled title="Tidak dapat dihapus">
                                                    <i class="bx bx-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center mt-4" role="alert">
                    Belum ada data tamu yang didaftarkan untuk pengajuan ini. Klik "Tambah Tamu" untuk memulai.
                </div>
            @endif
        </div> {{-- Akhir card-body --}}

        <div class="mt-4 text-center">
            <a href="{{ route('Pemohon.DashboardPemohon') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali ke Dashboard
            </a>
        </div>
    </div> {{-- Akhir card shadow --}}
</div> {{-- Akhir container-fluid --}}
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        function sa_warning() {
            return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');
        }
    </script>
@endsection
