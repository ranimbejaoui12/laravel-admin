@extends('layout')
@section('title', 'Add Appointment')
@section('header', 'Add New Appointment')

@section('content')
<div class="card shadow-sm border-0 rounded">
    <div class="card-body" style="max-width:600px; margin:auto;">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <!-- Doctor -->
            <div class="form-group mb-3">
                <label for="doctor_id">Doctor</label>
                <select name="doctor_id" id="doctor_id" class="form-control" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }} {{ $doctor->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Patient -->
            <div class="form-group mb-3">
                <label for="patient_id">Patient</label>
                <select name="patient_id" id="patient_id" class="form-control" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name }} {{ $patient->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div class="form-group mb-3">
                <label for="date">Date</label>
                <input type="date" name="date" id="date"
                       class="form-control"
                       value="{{ old('date') }}" required>
            </div>

            <!-- Start Time -->
            <div class="form-group mb-3">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="start_time"
                       class="form-control"
                       value="{{ old('start_time') }}" required>
            </div>

            <!-- End Time -->
            <div class="form-group mb-3">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="end_time"
                       class="form-control"
                       value="{{ old('end_time') }}" required>
            </div>

            <!-- Motivation -->
            <div class="form-group mb-3">
                <label for="motivation">Motivation</label>
                <textarea name="motivation" id="motivation"
                          class="form-control" rows="3" required>{{ old('motivation') }}</textarea>
            </div>

            <!-- Status (optional default hidden) -->
            <input type="hidden" name="status" value="pending">

            <!-- Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    Add Appointment
                </button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection