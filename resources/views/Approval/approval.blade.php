<!-- resources/views/approval.blade.php -->

@extends('layout-lw') <!-- Assuming you have a layout named 'layout-lw' -->

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Approval Page</h2>
                    </div>
                    <div class="card-body">
                        @if(count($submissions) > 0)
                            <form action="{{ route('approve.files') }}" method="post">
                                @csrf
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Role</th>
                                            <th>Unit Kerja</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <!-- Add more columns as needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $submission)
                                            <tr>
                                                <td>{{ $submission->id }}</td>
                                                <td>{{ $submission->role }}</td>
                                                <td>{{ $submission->unit_kerja }}</td>
                                                <td>{{ $submission->status }}</td>
                                                <!-- Add more columns as needed -->
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="approved[]" value="{{ $submission->id }}">
                                                        Approve
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary">Approve Selected</button>
                            </form>
                        @else
                            <p>No submissions awaiting approval.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
