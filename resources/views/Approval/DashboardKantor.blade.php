{{-- Approval/DashboardKantor.blade.php --}}

@extends('Approval.layout')
@section('title')
    Dashboard Kantor
@endsection
@section('css')
    {{-- Grid.js CSS tidak lagi dibutuhkan --}}
    {{-- <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}"> --}}
@endsection
@section('page-title')
    Dashboard Kantor
@endsection
@section('body')
<body>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-4"> {{-- Styling card --}}
                <div class="card-header bg-white text-white rounded-top-4 p-4"> {{-- Header card dengan warna --}}
                    <h4 class="card-title mb-0 text-black">Daftar Pengajuan Kantor</h4>
                </div>
                <div class="card-body p-4"> {{-- Padding untuk card body --}}
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

                    @if(count($submissions) > 0)
                        <div class="table-responsive"> {{-- Penting untuk responsivitas tabel --}}
                            <table class="table table-bordered table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No.</th>
                                        <th>Nama Pemohon</th>
                                        <th>NPK Pemohon</th>
                                        <th>Jenis Tamu</th>
                                        <th>Tujuan Unit Kerja</th>
                                        <th>Waktu Pengajuan</th>
                                        <th>Status</th>
                                        <th style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $key => $submission)
                                        <tr>
                                            <td>{{ $submissions->firstItem() + $key }}</td> {{-- Sesuaikan jika bukan paginate --}}
                                            <td>{{ optional($submission->user)->username ?? 'N/A' }}</td>
                                            <td>{{ optional($submission)->NPK ?? 'N/A' }}</td>
                                            <td>{{ optional($submission->role)->name ?? '-' }}</td>
                                            <td>{{ optional($submission->unitKerja)->nama ?? 'N/A' }}</td>
                                            <td>{{ optional($submission->created_at)->format('Y-m-d, H:i:s') ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusColorClass = '';
                                                    switch (optional($submission->status)->id) {
                                                        case 1: $statusColorClass = 'bg-warning text-dark'; break;
                                                        case 2: $statusColorClass = 'bg-success'; break;
                                                        case 3: $statusColorClass = 'bg-danger'; break;
                                                        default: $statusColorClass = 'bg-secondary'; break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusColorClass }} fs-7">{{ optional($submission->status)->name }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('Approval.CheckSubmissionKantor', ['submissionId' => $submission->id]) }}" class="btn btn-sm btn-primary" title="Check Submission">
                                                        <i class="bx bx-file-find"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center mt-4" role="alert">
                            Belum ada pengajuan yang perlu disetujui di Kantor.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

---

@section('scripts')
    {{-- Sweet Alert jika kamu ingin menggunakannya untuk konfirmasi delete --}}
    {{-- <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    {{-- Script untuk konfirmasi delete jika ada tombol delete --}}
    {{-- <script>
        function sa_warning() {
            return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');
        }
    </script> --}}
@endsection
