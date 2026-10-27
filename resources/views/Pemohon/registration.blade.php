@extends('Pemohon.layout')
@section('title')
    Registration
@endsection
@section('page-title')
    Registration
@endsection
@section('body')
    <body>
@endsection

@section('content')

    <div class="container-sm">
        <div class="card mx-auto" style="max-width: 600px;"> {{-- Berikan max-width yang jelas --}}
            <div class="card-body">
                <h1 class="card-title text-center mb-4 text-primary">Pendaftaran Pengajuan</h1>

                <form action="{{ route('registration.submit') }}" method="post" id="registrationForm" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="unitKerjaDisplay" class="form-label">Tujuan Unit Kerja:</label>
                        {{-- Tampilkan nama unit kerja user yang login --}}
                        <input type="text" id="unitKerjaDisplay" class="form-control" value="{{ optional($tujuanUnitKerja)->nama ?? 'Unit Kerja Tidak Ditemukan' }}" readonly>
                        {{-- Kirimkan ID unit kerja via hidden input --}}
                        <input type="hidden" name="unitKerja" value="{{ optional($tujuanUnitKerja)->id }}">
                        @error('unitKerja')
                            <div class="text-danger mt-1">{{ $message }}</div> {{-- Error untuk hidden input --}}
                        @enderror
                        @if(is_null(optional($tujuanUnitKerja)->id))
                            <small class="text-danger">Peringatan: User Anda tidak memiliki Unit Kerja yang terdaftar. Harap hubungi Admin.</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="namaPendaftar" class="form-label">Nama Karyawan Pendaftar:</label>
                        {{-- Isi otomatis dari user yang login --}}
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', Auth::user()->nama ?? '') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="npk" class="form-label">Nomor Pokok Karyawan:</label>
                        {{-- Isi otomatis dari user yang login --}}
                        <input type="text" name="npk" class="form-control @error('npk') is-invalid @enderror" value="{{ old('npk', Auth::user()->NPK ?? '') }}" required>
                        @error('npk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contactPerson" class="form-label">Contact Person:</label>
                        <input type="tel" name="contactPerson" class="form-control @error('contactPerson') is-invalid @enderror" id="contactPerson" value="{{ old('contactPerson') }}" required>
                        @error('contactPerson')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="roleSelection" class="form-label">Pilih Jenis Tamu:</label>
                        <select name="roleSelection" id="roleSelection" class="form-select @error('roleSelection') is-invalid @enderror" onchange="toggleSubmitButton()">
                            <option value="">Pilih Jenis Tamu</option>
                            {{-- Opsi role selection akan diisi melalui JavaScript --}}
                        </select>
                        @error('roleSelection')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <button type="submit" class="btn btn-primary btn-block" id="submitButton" disabled>Submit</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data role (tetap dibutuhkan untuk roleSelection)
            var roleSelect = document.getElementById('roleSelection');
            fetch('/get-roles')
                .then(response => response.json())
                .then(data => {
                    // Clear existing options, hanya tampilkan Visitor dan Karyawan
                    roleSelect.innerHTML = '<option value="">Pilih Jenis Tamu</option>';

                    for (var id in data) {
                        // Asumsi role Visitor ID 5 dan Karyawan ID 6
                        if (id == 5 || id == 6) {
                            var option = document.createElement('option');
                            option.value = id;
                            option.text = data[id];
                            roleSelect.add(option);
                        }
                    }
                    toggleSubmitButton(); // Panggil ini setelah opsi terisi
                });

            // Hapus fetch unitKerjaSelect dan logikanya
            // Karena unitKerja sekarang otomatis dari user login
            // var unitKerjaSelect = document.getElementById('unitKerja');
            // fetch('/get-unit-kerja-options')
            //     .then(response => response.json())
            //     .then(data => { /* ... */ });

            // Event listener untuk memicu pengecekan tombol submit
            document.getElementById('registrationForm').addEventListener('input', toggleSubmitButton);
            document.getElementById('roleSelection').addEventListener('change', toggleSubmitButton);
        });

        function toggleSubmitButton() {
            var roleSelection = document.getElementById('roleSelection');
            var unitKerjaInput = document.querySelector('input[name="unitKerja"]'); // Ambil hidden input unitKerja
            var submitButton = document.getElementById('submitButton');
            var allRequiredFields = document.querySelectorAll('#registrationForm input[required], #registrationForm select[required]');

            var isFormValid = true;

            // Cek semua field required
            for (var i = 0; i < allRequiredFields.length; i++) {
                if (allRequiredFields[i].value.trim() === '') {
                    isFormValid = false;
                    break;
                }
            }

            // Tambahan: Pastikan unitKerja dari user login tidak kosong (hidden input)
            if (unitKerjaInput && unitKerjaInput.value.trim() === '') {
                isFormValid = false;
            }

            // Tambahan: Pastikan roleSelection bukan "Pilih Jenis Tamu"
            if (roleSelection.value.trim() === '') {
                isFormValid = false;
            }

            submitButton.disabled = !isFormValid;
        }
    </script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
