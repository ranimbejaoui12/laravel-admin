@extends('layout')

@section('title', 'Appointment Management')
@section('header', 'Appointment List')

@section('content')
<div class="card shadow-sm border-0 rounded">
    <div class="card-body">

        <div class="table-responsive">
            <table id="appointment_table" class="table table-hover align-middle">

                <!-- ===== TABLE HEADER ===== -->
                <thead class="table-light">
                    <tr>
                        <th>Hospital</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Motivation</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <!-- ===== TABLE BODY ===== -->
                <tbody>
                    @foreach ($appointments as $appointment)
                    <tr>

                        <!-- Hospital -->
                        <td>
                            {{ $appointment->hospital?->name ?? '—' }}
                        </td>

                        <!-- Date -->
                        <td>{{ $appointment->date }}</td>

                        <!-- Time -->
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $appointment->start_time }} → {{ $appointment->end_time }}
                            </span>
                        </td>

                        <!-- Doctor -->
                        <td>{{ $appointment->doctor?->name ?? '—' }}</td>

                        <!-- Specialty -->
                        <td>{{ $appointment->doctor?->specialty?->name ?? '—' }}</td>

                        <!-- Motivation -->
                        <td class="text-truncate" style="max-width: 220px;" title="{{ $appointment->motivation }}">
                            {{ $appointment->motivation }}
                        </td>

                        <!-- ===== STATUS ===== -->
                        <td class="text-center">

                            @if($appointment->status === 'pending')
                                <div class="d-flex justify-content-center gap-1 flex-wrap">

                                    <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="status" value="confirmed"
                                            class="btn btn-success btn-sm">
                                            ✔
                                        </button>
                                    </form>

                                    <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="status" value="cancelled"
                                            class="btn btn-danger btn-sm">
                                            ✖
                                        </button>
                                    </form>

                                </div>

                            @elseif($appointment->status === 'confirmed')
                                <span class="badge bg-success">Confirmed</span>

                            @elseif($appointment->status === 'cancelled')
                                <span class="badge bg-danger">Canceled</span>

                            @else
                                <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                            @endif

                        </td>

                        <!-- ===== ACTIONS ===== -->
                        <td class="text-center">

                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('appointments.destroy', $appointment->id) }}"
                                  method="POST"
                                  class="d-inline-block">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this appointment?')"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <!-- ===== FLOATING BUTTON ===== -->
        <button type="button"
                class="btn btn-success rounded-circle shadow-lg"
                style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; font-size: 26px;"
                data-toggle="modal"
                data-target="#modal_add_appointment"
                title="Add Appointment">
            <i class="fas fa-plus"></i>
        </button>

    </div>
</div>

<!-- ===== MODAL ===== -->
@include('appointments.partials.add_modal')

<!-- ===== DATATABLE ===== -->
<script>
$(document).ready(function() {
    $('#appointment_table').DataTable({
        responsive: true,
        autoWidth: false,
        ordering: true,
        searching: true,
        pageLength: 5,
        language: {
            search: "🔍",
            zeroRecords: "No appointments found",
        }
    });
});
</script>

@endsection