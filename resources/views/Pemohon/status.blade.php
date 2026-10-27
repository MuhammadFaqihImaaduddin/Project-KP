{{-- <!-- resources/views/status.blade.php -->

@extends('Pemohon.layout')
@section('title')
    Status Page
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    Status Page
@endsection
@section('body')

    <body>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2>Status Page</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if(count($submissions) > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Role</th>
                                            <th>Tujuan Unit Kerja</th>
                                            <th>Status</th>
                                            <th>Submission Date</th>
                                            <th>Last Updated</th>
                                            <th>Action</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $key => $submission)
                                            <tr>
                                                <td>{{ $key + 1 }}</td> <!-- Menampilkan nomor urut -->
                                                <td>{{ $submission->role }}</td>
                                                <td>{{ optional($submission->unitKerja)->nama }}</td>
                                                <td>
                                                    @php
                                                        $statusColor = '';
                                                        switch ($submission->status->id) {
                                                            case 1:
                                                                $statusColor = 'yellow';
                                                                break;
                                                            case 2:
                                                                $statusColor = 'green';
                                                                break;
                                                            case 3:
                                                                $statusColor = 'red';
                                                                break;
                                                            default:
                                                                $statusColor = 'black'; // Warna default jika status tidak sesuai dengan yang diberikan
                                                        }@endphp

                                                        <span class="badge bg, fs-6" style="color: {{ $statusColor }}" font>
                                                            {{ $submission->status->name }}
                                                        </span>
                                                </td>
                                                <td>
                                                    @if($submission->created_at instanceof \Carbon\Carbon)
                                                        {{ $submission->created_at->format('Y-m-d H:i:s') }}
                                                    @elseif(is_string($submission->created_at))
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $submission->created_at)->format('Y-m-d H:i:s') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->updated_at instanceof \Carbon\Carbon)
                                                        {{ $submission->updated_at->format('Y-m-d H:i:s') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->status->id == 1 || $submission->status->id == 3)
                                                        <a href="{{ route('editForm', ['submissionId' => $submission->id]) }}" class="btn btn-primary">Edit</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->status->id == 1 || $submission->status->id == 3)
                                                    <form action="{{ route('deleteForm', ['submissionId' => $submission->id]) }}" method="post" onsubmit="return sa_warning()">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No submissions available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<!-- Sweet Alerts js -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Sweet alert init js-->
<script src="{{ URL::asset('build/js/pages/sweet-alerts.init.js') }}"></script>
<!-- App js -->
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    //Warning Message
document.getElementById("sa_warning").addEventListener("click", function() {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#51d28c",
        cancelButtonColor: "#f34e4e",
        confirmButtonText: "Yes, delete it!"
      }).then(function (result) {
        if (result.isConfirmed) {
            return true;
          Swal.fire("Deleted!", "Your file has been deleted.", "success"
          );
        }
    });
});
</script>
@endsection --}}
