@extends('layout')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"> Dashboard Overview</h3>

        <!-- DARK MODE TOGGLE -->
        <button onclick="toggleDarkMode()" class="btn btn-dark btn-sm">
            🌙 
        </button>
    </div>

    <!-- ================= TOP CARDS ================= -->
    <div class="row g-3">

        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white shadow">
                <h6>Total Doctors</h6>
                <h2 class="counter">{{ $doctors }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-success text-white shadow">
                <h6>Total Patients</h6>
                <h2 class="counter">{{ $patients }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-indigo text-white shadow">
                <h6>Total Appointments</h6>
                <h2 class="counter">{{ $appointmentsTotal }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white shadow">
                <h6>Leave Requests</h6>
                <h2 class="counter">{{ $leaveRequests }}</h2>
            </div>
        </div>
        

    </div>

    <!-- ================= ANALYTICS ================= -->
    <div class="row mt-4">

        <div class="col-md-8">
            <div class="card shadow p-4">

                <h5 class="mb-3">Appointments Status</h5>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>✅ Confirmed</span>
                        <span>{{ $appointmentsConfirmed }} ({{ $appointmentsConfirmedPercent }}%)</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success"
                             style="width: {{ $appointmentsConfirmedPercent }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>⏳ Pending</span>
                        <span>{{ $appointmentsPending }} ({{ $appointmentsPendingPercent }}%)</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning"
                             style="width: {{ $appointmentsPendingPercent }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>❌ Canceled</span>
                        <span>{{ $appointmentsCanceled }} ({{ $appointmentsCanceledPercent }}%)</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-danger"
                             style="width: {{ $appointmentsCanceledPercent }}%"></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- SMART ANALYTICS -->
        <div class="col-md-4">

            <div class="card shadow text-center p-3 mb-3 smart-card">
                <h6> Attestations</h6>
                <h2 class="counter">{{ $attestations }}</h2>
            </div>

            <div class="card shadow text-center p-3 smart-card">
                <h6> New Appointments</h6>
                <h2 class="counter">{{ $newAppointmentsCount }}</h2>
            </div>

        </div>

    </div>

</div>

@endsection

<!-- ================= STYLE ================= -->
<style>

.stat-card{
    padding:20px;
    border-radius:14px;
    text-align:center;
    transition:0.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.smart-card{
    border-radius:14px;
}

/* DARK MODE */
.dark-mode{
    background:#0f172a !important;
    color:white !important;
}

.dark-mode .card{
    background:#1e293b !important;
    color:white !important;
}

.progress{
    height:12px;
    border-radius:10px;
}

/* animation counters */
.counter{
    font-weight:bold;
}

</style>

<!-- ================= SCRIPTS ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// 🌙 DARK MODE
function toggleDarkMode(){
    document.body.classList.toggle("dark-mode");
}

// 📈 SIMPLE COUNTER ANIMATION
document.addEventListener("DOMContentLoaded", function () {

    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        let target = +counter.innerText;
        let count = 0;

        let step = Math.ceil(target / 30);

        let interval = setInterval(() => {
            count += step;
            if(count >= target){
                counter.innerText = target;
                clearInterval(interval);
            } else {
                counter.innerText = count;
            }
        }, 30);
    });

});

</script>