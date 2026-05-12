@extends('layout')

@section('content')
<div class="container my-5">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold text-primary">Doctor Requests</h2>
        <p class="text-muted">Manage leave requests and attestations separately</p>
    </div>

    {{-- NAV TABS --}}
    <ul class="nav nav-pills mb-4" id="requestTabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#leaveTab">
                Leave Requests
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#attestationTab">
                Attestations
            </a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- LEAVE --}}
        <div class="tab-pane fade show active" id="leaveTab">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Leave Requests
                </div>

                <div class="card-body p-0">

                    @if(isset($doctorRequests) && $doctorRequests->isEmpty())
                        <div class="p-3 text-muted text-center">
                            No leave requests found
                        </div>
                    @else

                        <div class="table-responsive">
                            <table class="table table-hover text-center mb-0">

                                <thead class="table-light">
                                <tr>
                                    <th>Doctor</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($doctorRequests ?? [] as $req)

                                    @php $status = strtolower($req->status); @endphp

                                    <tr>
                                        <td>{{ $req->doctor_id }}</td>
                                        <td>{{ $req->start_date }}</td>
                                        <td>{{ $req->end_date }}</td>
                                        <td>{{ $req->reason }}</td>

                                        <td>
                                            <span class="badge
                                                {{ $status === 'accepted' ? 'bg-success' :
                                                   ($status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($status === 'pending')

                                                <form action="{{ route('doctor.leave.updateWeb', $req->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button class="btn btn-success btn-sm">Accept</button>
                                                </form>

                                                <form action="{{ route('doctor.leave.updateWeb', $req->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button class="btn btn-danger btn-sm">Reject</button>
                                                </form>

                                            @else
                                                <span class="text-muted small">No actions</span>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>

                            </table>
                        </div>

                    @endif

                </div>
            </div>
        </div>

        {{-- ATTESTATIONS --}}
        <div class="tab-pane fade" id="attestationTab">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold">
                    Attestations
                </div>

                <div class="card-body p-0">

                    @if(isset($attestations) && $attestations->isEmpty())
                        <div class="p-3 text-muted text-center">
                            No attestations found
                        </div>
                    @else

                        <div class="table-responsive">
                            <table class="table table-hover text-center mb-0">

                                <thead class="table-light">
                                <tr>
                                    <th>Doctor</th>
                                    <th>Type</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($attestations ?? [] as $att)

                                    @php $attStatus = strtolower($att->status); @endphp

                                    <tr>
                                        <td>{{ $att->doctor_id }}</td>
                                        <td>{{ $att->type }}</td>
                                        <td>{{ $att->note ?? '-' }}</td>

                                        <td>
                                            <span class="badge
                                                {{ $attStatus === 'accepted' ? 'bg-success' :
                                                   ($attStatus === 'refused' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($att->status) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($attStatus === 'pending')

                                                <form action="{{ route('doctor.attestation.updateWeb', $att->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button class="btn btn-success btn-sm">Accept</button>
                                                </form>

                                                <form action="{{ route('doctor.attestation.updateWeb', $att->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="refused">
                                                    <button class="btn btn-outline-danger btn-sm">Reject</button>
                                                </form>

                                            @else
                                                <span class="text-muted small">No actions</span>
                                            @endif
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>

                            </table>
                        </div>

                    @endif

                </div>
            </div>

        </div>

    </div>
</div>

{{-- STYLE --}}
<style>
.table-hover tbody tr:hover {
    background: #f3f7ff;
}
</style>

{{-- AUTO TAB FROM NOTIFICATION --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');

    if (tab === 'leave') {
        document.querySelector('a[href="#leaveTab"]').click();
    }

    if (tab === 'attestation') {
        document.querySelector('a[href="#attestationTab"]').click();
    }

});
</script>

@endsection