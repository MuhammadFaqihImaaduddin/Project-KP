@extends('Admin.layout')
@section('title')
    Dashboard Admin
@endsection
@section('css')
    {{-- Grid.js CSS tidak lagi dibutuhkan --}}
    {{-- <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}"> --}}
@endsection
@section('page-title')
    Dashboard Admin
@endsection
@section('body')
<body>
@endsection

@section('content')
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 p-4">
            <h3 class="m-0 text-white">Selamat Datang, Administrator!</h3>
        </div>
        <div class="card-body p-4">
            <p>Ini adalah dashboard untuk administrator sistem. Dari sini Anda akan bisa mengelola data master seperti Pengguna, Unit Kerja, dan Area Kerja.</p>
            <div class="mt-4">
                <a href="{{ route('admin.unit-kerja.index') }}" class="btn btn-info">Kelola Unit Kerja</a>
            </div>
        </div>
    </div>
@endsection




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
