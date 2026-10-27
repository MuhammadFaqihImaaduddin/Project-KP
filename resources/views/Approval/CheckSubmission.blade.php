@extends('Approval.layout')

@section('title')
    Detail Pengajuan
@endsection

@section('page-title')
    Detail Pengajuan
@endsection

@section('body')
<body>
@endsection

@section('content')
    <div class="container my-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                <h3 class="m-0 text-white">Detail Pengajuan Tamu</h3>
                {{-- Menampilkan Status Global Pengajuan di Header --}}
                @php
                    $parentSubmission = optional($submission->first())->submission;
                    $mainSubmissionStatusId = optional($parentSubmission->status)->id;
                    $mainSubmissionStatusName = optional($parentSubmission->status)->name;
                    $statusColorClass = '';
                    switch ($mainSubmissionStatusId) {
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
                <span class="badge {{ $statusColorClass }} fs-6">{{ $mainSubmissionStatusName }}</span>
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

                {{-- Detail Pengajuan Utama (dari Submission parent) --}}
                @if ($parentSubmission)
                    <h5 class="mb-3 text-primary">Informasi Pengajuan:</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            {{-- NPK ada di user, bukan di submission langsung --}}
                            <p class="mb-1"><strong>Diajukan Oleh:</strong> {{ optional($parentSubmission->user)->username ?? 'N/A' }} (NPK: {{ optional($parentSubmission)->NPK ?? 'N/A' }})</p>
                            <p class="mb-1"><strong>Tujuan Unit Kerja:</strong> {{ optional($parentSubmission->unitKerja)->nama ?? 'N/A' }}</p>
                            {{-- Tambahkan Jenis Tamu Pengajuan (role dari submission parent) --}}
                            <p class="mb-1"><strong>Jenis Tamu Pengajuan:</strong> {{ optional($parentSubmission->role)->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Waktu Pengajuan:</strong> {{ optional($parentSubmission->created_at)->format('Y-m-d, H:i:s') ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Jumlah Tamu:</strong> {{ $submission->count() }} orang</p>
                        </div>
                    </div>
                    <hr class="mb-4">
                @endif

                {{-- Tabel Daftar Tamu --}}
                <h5 class="mb-3 text-primary">Daftar Tamu:</h5>
                @if ($submission->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Nama Tamu</th>
                                    <th>Jenis Role</th>
                                    <th>File 1</th>
                                    <th>File 2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submission as $index => $dataSubmission)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $dataSubmission->nama_tamu }}</td>
                                        <td>{{ optional($dataSubmission->role)->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                // Perbaikan role_id: 5=Visitor, 6=Karyawan
                                                $file1 = null;
                                                $file1Label = 'File 1';
                                                if (optional($dataSubmission->role)->id == 5) { // Visitor
                                                    $file1 = optional($dataSubmission->ktpFile);
                                                    $file1Label = 'KTP';
                                                } elseif (optional($dataSubmission->role)->id == 6) { // Karyawan
                                                    $file1 = optional($dataSubmission->safetyInductionFile);
                                                    $file1Label = 'Safety Induction';
                                                }
                                            @endphp
                                            @if ($file1 && $file1->file_path) {{-- Pastikan file_path tidak kosong --}}
                                                <a href="{{ asset('storage/' . $file1->file_path) }}" target="_blank" class="text-primary text-decoration-none">
                                                    <i class="bx bx-link-external me-1"></i> {{ $file1Label }} ({{ $file1->file_name }})
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                // Perbaikan role_id: 5=Visitor, 6=Karyawan
                                                $file2 = null;
                                                $file2Label = 'File 2';
                                                if (optional($dataSubmission->role)->id == 5) { // Visitor
                                                    $file2 = optional($dataSubmission->boosterFile);
                                                    $file2Label = 'Sertifikat Booster';
                                                } elseif (optional($dataSubmission->role)->id == 6) { // Karyawan
                                                    $file2 = optional($dataSubmission->pdpeFile);
                                                    $file2Label = 'PDPE';
                                                }
                                            @endphp
                                            @if ($file2 && $file2->file_path) {{-- Pastikan file_path tidak kosong --}}
                                                <a href="{{ asset('storage/' . $file2->file_path) }}" target="_blank" class="text-primary text-decoration-none">
                                                    <i class="bx bx-link-external me-1"></i> {{ $file2Label }} ({{ $file2->file_name }})
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">Tidak ada data tamu untuk pengajuan ini.</p>
                @endif
            </div>

            <div class="card-footer bg-light p-4">
                @php
                    // --- LOGIKA UNTUK RUTE APPROVAL DINAMIS ---
                    $approverRoleId = Auth::user()->role_id; // Role user yang sedang login
                    $approveRouteName = '';
                    $rejectRouteName = '';
                    $dashboardBackRoute = '';

                    if ($approverRoleId == 2) { // Jika user yang login adalah VP Kamtib (Kantor)
                        $approveRouteName = 'approval.Kantor.approve';
                        $rejectRouteName = 'approval.Kantor.reject';
                        $dashboardBackRoute = 'Approval.DashboardKantor';
                    } elseif ($approverRoleId == 3) { // Jika user yang login adalah VP Operasi 1 (Pabrik)
                        $approveRouteName = 'approval.Pabrik.approve';
                        $rejectRouteName = 'approval.Pabrik.reject';
                        $dashboardBackRoute = 'Approval.DashboardPabrik';
                    } else {
                        // Default fallback jika role_id approver tidak dikenali (tidak diharapkan terjadi)
                        $approveRouteName = '#';
                        $rejectRouteName = '#';
                        $dashboardBackRoute = 'redirect'; // Kembali ke redirect umum
                    }
                    // --- AKHIR LOGIKA DINAMIS ---
                @endphp

            @if ($parentSubmission)
                @if ($mainSubmissionStatusId == 2) {{-- Approved --}}
                    <div class="alert alert-success mt-3 text-center">
                        <strong>Pengajuan ini sudah DISETUJUI.</strong>
                        @if($parentSubmission->keterangan_approval)<p class="mb-0 mt-2">Keterangan: {{ $parentSubmission->keterangan_approval }}</p>@endif
                    </div>
                @elseif ($mainSubmissionStatusId == 3) {{-- Rejected --}}
                    <div class="alert alert-danger mt-3 text-center">
                        <strong>Pengajuan ini DITOLAK.</strong>
                        @if($parentSubmission->keterangan_approval)<p class="mb-0 mt-2">Alasan Penolakan: {{ $parentSubmission->keterangan_approval }}</p>@endif
                    </div>
                @else {{-- Pending --}}
                    <div class="mb-3">
                        <label for="keteranganApproval" class="form-label">Keterangan / Alasan (Wajib jika Menolak)</label>
                        <textarea name="keterangan_approval" id="keteranganApproval" class="form-control" rows="3" placeholder="Isi keterangan persetujuan atau alasan penolakan..."></textarea>
                        @error('keterangan_approval')
                            <div class="invalid-feedback d-block">{{ $message }}</div> {{-- d-block agar selalu tampil --}}
                        @enderror
                    </div>

                    <div class="approval-buttons d-flex justify-content-between mt-3">
                        <a href="{{ route($dashboardBackRoute) }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>

                        <div>
                            {{-- Tombol Approve (di dalam form terpisah untuk memudahkan validasi komentar) --}}
                            <form id="approveForm" action="{{ route($approveRouteName, ['submissionId' => $parentSubmission->id]) }}" method="post" class="d-inline me-2" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pengajuan ini?');">
                                @csrf
                                <input type="hidden" name="keterangan_approval_approve" id="keteranganApprovalApprove">
                                <button type="submit" class="btn btn-success" onclick="document.getElementById('keteranganApprovalApprove').value = document.getElementById('keteranganApproval').value;">
                                    <i class="bx bx-check-circle me-1"></i> Setuju
                                </button>
                            </form>

                            {{-- Tombol Reject (di dalam form terpisah) --}}
                            <form id="rejectForm" action="{{ route($rejectRouteName, ['submissionId' => $parentSubmission->id]) }}" method="post" class="d-inline" onsubmit="return validateRejectForm();">
                                @csrf
                                <input type="hidden" name="keterangan_approval_reject" id="keteranganApprovalReject">
                                <button type="submit" class="btn btn-danger" onclick="document.getElementById('keteranganApprovalReject').value = document.getElementById('keteranganApproval').value;">
                                    <i class="bx bx-x-circle me-1"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                 <div class="alert alert-warning text-center mt-3">
                    Informasi pengajuan utama tidak ditemukan.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        // Fungsi validasi khusus untuk tombol Tolak
        function validateRejectForm() {
            var keterangan = document.getElementById('keteranganApproval').value.trim();
            if (keterangan === '') {
                alert('Alasan penolakan wajib diisi.');
                document.getElementById('keteranganApproval').classList.add('is-invalid');
                return false; // Batalkan submit
            }
            document.getElementById('keteranganApproval').classList.remove('is-invalid');
            document.getElementById('keteranganApprovalReject').value = keterangan; // Pastikan hidden field terisi
            return confirm('Anda yakin ingin MENOLAK pengajuan ini?');
        }

        // Untuk tombol Approve, tidak wajib komentar
        document.getElementById('approveForm')?.addEventListener('submit', function(event) {
            // Keterangan akan diambil dan diset ke hidden field oleh onclick di button
            // Validasi confirm() sudah ada di onsubmit form
        });
    </script>
@endsection
