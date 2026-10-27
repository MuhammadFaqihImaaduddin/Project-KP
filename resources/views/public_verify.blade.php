@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Persetujuan #{{ $submission->id }}</title>
    <link href="{{ URL::asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/css/app.min.css') }}" rel="stylesheet" type="text-content" />
    <link href="{{ URL::asset('build/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f2f5;
            color: #333;
        }
        .document-container {
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 25mm; /* Standard margin for A4 */
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .company-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .company-header img {
            max-height: 50px; /* Adjust logo size */
            margin-bottom: 10px;
        }
        .document-title {
            font-size: 1.8em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #0056b3;
        }
        .section-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            color: #007bff;
        }
        .info-item {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .info-item strong {
            display: inline-block;
            min-width: 150px; /* Align labels */
            color: #000;
        }
        .qr-code-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }
        .qr-code-section img {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .verification-text {
            font-size: 0.9em;
            color: #555;
            margin-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            margin-top: 15px;
        }
        .footer-info {
            font-size: 0.8em;
            color: #888;
            text-align: center;
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        /* Print-specific styles */
        @media print {
            body {
                margin: 0;
                background: none;
                -webkit-print-color-adjust: exact; /* For background colors */
            }
            .document-container {
                width: 210mm;
                min-height: 297mm;
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 25mm;
                page-break-after: always;
            }
            .print-button {
                display: none;
            }
            .status-badge.bg-success { background-color: #28a745 !important; }
            .status-badge.bg-danger { background-color: #dc3545 !important; }
            .status-badge.bg-warning { background-color: #ffc107 !important; }
            .status-badge.bg-secondary { background-color: #6c757d !important; }
            .text-dark { color: #343a40 !important; }
        }
    </style>
</head>
<body>
    <div class="document-container">
        <div class="company-header">
            {{-- Ganti dengan logo perusahaan Anda --}}
            <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="Logo Perusahaan" style="max-height: 50px; margin-bottom: 10px;">
            <h5 class="mb-0">PT. Nama Perusahaan Anda</h5>
            <p class="mb-0">Jl. Contoh Alamat No. 123, Kota Anda</p>
            <p>Telepon: (021) 1234-5678 | Email: info@perusahaan.com</p>
        </div>

        <div class="document-title">
            SURAT KONFIRMASI PERSETUJUAN AKSES TAMU
        </div>

        <div class="section-title">Detail Pengajuan</div>
        <div class="info-group">
            <p class="info-item"><strong>ID Pengajuan:</strong> {{ $submission->id }}</p>
            <p class="info-item"><strong>Diajukan Oleh:</strong> {{ optional($submission->user)->username ?? 'N/A' }}</p>
            <p class="info-item"><strong>Unit Asal Pengaju:</strong> {{ optional($submission->unitKerja)->nama ?? 'N/A' }}</p>
            <p class="info-item"><strong>Waktu Pengajuan:</strong> {{ optional($submission->created_at)->format('Y-m-d H:i:s') }}</p>
        </div>

        <div class="section-title">Detail Tamu yang Diajukan</div>
        <ul style="list-style-type: none; padding-left: 0;">
            @foreach($submission->dataSubmission as $guest)
                <li class="info-item">
                    <strong style="min-width: 100px;">Nama:</strong> {{ $guest->nama_tamu }}<br>
                    <span style="display: inline-block; min-width: 100px;"></span><strong>Jenis:</strong> {{ optional($guest->role)->name ?? 'N/A' }}
                </li>
            @endforeach
        </ul>

        <div class="section-title">Status Persetujuan</div>
        @php
            $statusColorClass = '';
            $statusText = optional($submission->status)->name;
            switch (optional($submission->status)->id) {
                case 2: // Approved by Kantor
                case 4: // Approved by Pabrik
                    $statusColorClass = 'bg-success';
                    $statusText = 'DISETUJUI';
                    break;
                case 3: // Rejected by Kantor
                case 5: // Rejected by Pabrik
                    $statusColorClass = 'bg-danger';
                    $statusText = 'DITOLAK';
                    break;
                case 1: // Pending
                default:
                    $statusColorClass = 'bg-warning text-dark';
                    $statusText = 'PENDING';
                    break;
            }
        @endphp
        <div class="status-badge {{ $statusColorClass }}">
            {{ $statusText }}
        </div>
        @if(optional($submission)->keterangan_approval)
            <p class="info-item" style="font-size: 0.9em; margin-top: 10px;">
                <strong>Keterangan Approver:</strong> {{ $submission->keterangan_approval }}
            </p>
        @endif
        <p class="info-item"><strong>Disetujui/Ditolak Oleh:</strong>
            @if(optional($submission->status)->id == 2 || optional($submission->status)->id == 3)
                VP Kamtib (Kantor)
            @elseif(optional($submission->status)->id == 4 || optional($submission->status)->id == 5)
                VP Operasi 1 (Pabrik)
            @else
                N/A
            @endif
            pada {{ optional($submission->updated_at)->format('Y-m-d H:i:s') ?? 'Tanggal Tidak Tersedia' }}
        </p>


        <div class="qr-code-section">
            {{-- QR Code Verifikasi --}}
            <p>Pindai QR Code di bawah untuk verifikasi keaslian dokumen:</p>
            @if(optional($submission)->verification_token)
                <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(150)->generate(route('verification.page', ['token' => $submission->verification_token]))) }} " alt="QR Code Verifikasi">
                <p class="verification-text">Token Verifikasi: <strong>{{ $submission->verification_token }}</strong></p>
            @else
                <p class="verification-text text-danger">QR Code tidak tersedia. Token verifikasi tidak ditemukan.</p>
            @endif
            <p class="verification-text">Dokumen ini dicetak pada {{ now()->format('Y-m-d H:i:s') }}.</p>
        </div>

        <div class="footer-info">
            &copy; {{ date('Y') }} PT. Nama Perusahaan Anda. Dokumen ini sah tanpa tanda tangan.
            <br>Harap tidak memalsukan atau menyalahgunakan dokumen ini.
        </div>
    </div>

</body>
</html>
