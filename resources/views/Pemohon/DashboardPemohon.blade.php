@extends('Pemohon.layout')
@section('title')
    Dashboard Pemohon
@endsection
@section('css')
    {{-- Jika kamu punya Sweet Alert, bisa di-uncomment --}}
    {{-- <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    {{-- Grid.js tidak lagi dibutuhkan jika pakai tabel HTML biasa --}}
    {{-- <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}"> --}}
    {{-- flatpickr css --}}
    {{-- <link href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"> --}}
@endsection
@section('page-title')
    Dashboard Pemohon
@endsection
@section('body')
<body>
@endsection

@section('content')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12"> {{-- Menggunakan col-lg-12 untuk lebar penuh --}}
                <div class="card shadow-sm border-0 rounded-4">
                    {{-- PERUBAHAN DI SINI: card-header kini menggunakan d-flex untuk menyejajarkan judul dan tombol --}}
                    <div class="card-header bg-white rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 text-black">Daftar Pengajuan</h2>
                        <a href="{{ route('registration.form') }}" class="btn btn-success">
                            <i class="mdi mdi-plus me-1"></i> Buat Pengajuan Baru
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

                        @if(count($submissions) > 0)
                            <div class="table-responsive"> {{-- Penting untuk responsivitas tabel --}}
                                <table class="table table-bordered table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">No.</th>
                                            <th>Jenis Tamu</th>
                                            <th>Tujuan Unit Kerja</th>
                                            <th>Status</th>
                                            <th>Waktu Pengajuan</th>
                                            <th style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $key => $submission)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ optional($submission->role)->name }}</td> {{-- Menggunakan optional() untuk menghindari error jika relasi null --}}
                                                <td>{{ optional($submission->unitKerja)->nama }}</td> {{-- Menggunakan optional() --}}
                                                <td>
                                                    @php
                                                        $statusColorClass = '';
                                                        switch (optional($submission->status)->id) {
                                                            case 1:
                                                                $statusColorClass = 'bg-warning text-dark'; // Pending
                                                                break;
                                                            case 2:
                                                                $statusColorClass = 'bg-success'; // Approved
                                                                break;
                                                            case 3:
                                                                $statusColorClass = 'bg-danger'; // Rejected
                                                                break;
                                                            default:
                                                                $statusColorClass = 'bg-secondary'; // Default
                                                                break;
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $statusColorClass }} fs-7">{{ optional($submission->status)->name }}</span>
                                                </td>
                                                <td>
                                                    {{ optional($submission->created_at)->format('Y-m-d, H:i:s') ?? 'N/A' }} {{-- Menggunakan optional() dan fallback N/A --}}
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('daftar-tamu', ['submissionId' => $submission->id]) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                            <i class="bx bx-show"></i>
                                                        </a>
                                                        {{-- Hanya izinkan delete jika status adalah 1 (Pending) atau 3 (Rejected) --}}
                                                        @if(optional($submission->status)->id == 1 || optional($submission->status)->id == 3)
                                                            <form action="{{ route('deleteForm', ['submissionId' => $submission->id]) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                                    <i class="bx bx-trash-alt"></i>
                                                                </button>
                                                            </form>
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
                                Belum ada pengajuan yang dibuat. Klik "Buat Pengajuan Baru" untuk memulai.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{-- Sweet Alert jika kamu ingin menggunakannya untuk konfirmasi delete --}}
    {{-- <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        // Fungsi sa_warning() jika masih ingin menggunakan SweetAlert untuk konfirmasi
        function sa_warning() {
            // Jika tidak menggunakan SweetAlert, gunakan konfirmasi browser default
            return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');
        }
    </script>
@endsection
