@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Requests</h2>

    <!-- Doctor Requests -->
    <h4 class="mt-4">Doctor Requests</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctorRequests as $req)
            <tr>
                <td>{{ $req->doctor->name }}</td>
                <td>{{ $req->start_date }}</td>
                <td>{{ $req->end_date }}</td>
                <td>{{ $req->reason }}</td>
                <td>{{ $req->status }}</td>
                <td>
                    <form action="{{ url('/api/requests/doctor/'.$req->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Accepted">
                        <button class="btn btn-success btn-sm">Accept</button>
                    </form>
                    <form action="{{ url('/api/requests/doctor/'.$req->id) }}" method="POST" class="mt-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Patient Requests -->
    <h4 class="mt-5">Patient Requests</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Type</th>
                <th>Details</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patientRequests as $req)
            <tr>
                <td>{{ $req->patient->name }}</td>
                <td>{{ $req->type }}</td>
                <td>{{ $req->details }}</td>
                <td>{{ $req->status }}</td>
                <td>
                    <form action="{{ url('/api/requests/patient/'.$req->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Accepted">
                        <button class="btn btn-success btn-sm">Accept</button>
                    </form>
                    <form action="{{ url('/api/requests/patient/'.$req->id) }}" method="POST" class="mt-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection