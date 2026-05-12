<!-- ================= NAVBAR ================= -->
<nav class="main-header navbar navbar-expand navbar-light bg-white border-bottom">

    <!-- LEFT -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars fa-lg"></i>
            </a>
        </li>
    </ul>

    <!-- RIGHT -->
    <ul class="navbar-nav ml-auto">

        <!-- NOTIFICATIONS -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell fa-lg"></i>
                <span id="notif-count" class="badge badge-danger navbar-badge d-none">0</span>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-lg p-0">

                <div class="p-2 text-center font-weight-bold border-bottom">
                    Notifications
                </div>

                <div id="notif-list" class="notif-container">
                    <div class="text-center text-muted p-3">
                        Aucun notification
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <a href="#" id="mark-all-read"
                   class="dropdown-item text-center text-primary font-weight-bold">
                    Mark all as read
                </a>

            </div>
        </li>

        <!-- LOGOUT -->
<li class="nav-item">
    <a class="nav-link"
       href="{{ route('logout') }}"
       onclick="return confirm('Are you sure you want to logout?')">
        <i class="fas fa-power-off fa-lg"></i>
    </a>
</li>
    </ul>
</nav>


<!-- ================= SIDEBAR ================= -->
<aside class="main-sidebar sidebar-light elevation-4">

    <!-- BRAND -->
    <a href="#" class="brand-link d-flex align-items-center">
        <img src="{{ asset('img/logo.png') }}" style="height:38px; margin-right:10px;">
        <span class="brand-text font-weight-bold">Admin Panel</span>
    </a>

    <div class="sidebar">

        @php
            $user = auth()->user();
        @endphp

        <!-- USER -->
        <li class="nav-item d-flex align-items-center mt-3 mb-3">
            <a href="{{ $user ? route('profile.edit') : '#' }}"
               class="nav-link d-flex align-items-center">

                @if($user && $user->image)
                    <img src="{{ asset('storage/' . $user->image) }}"
                         style="width:35px;height:35px;border-radius:50%;object-fit:cover;margin-right:8px;">
                @else
                    <i class="fas fa-user-circle mr-2" style="font-size:1.5rem;"></i>
                @endif

                <span>
                    {{ $user ? $user->name . ' ' . $user->lastname : 'Guest' }}
                </span>

            </a>
        </li>

        <!-- MENU -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('hospitals.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>Hospitals</p>
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->role != \App\Enums\UserRoles::PATIENT->value)
                <li class="nav-item">
                    <a href="{{ route('patients.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-injured"></i>
                        <p>Patients</p>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('doctors.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-md"></i>
                        <p>Doctors</p>
                    </a>
                </li>

                <!-- REQUESTS -->
                <li class="nav-item">
                    <a href="{{ route('requests.doctor') }}" class="nav-link">
                        <i class="nav-icon fas fa-inbox"></i>
                        <p>Doctor Requests</p>
                    </a>
                </li>

                <!-- APPOINTMENTS -->
                <li class="nav-item">
                    <a href="{{ route('appointments.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Appointments</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('appointments.calendar') }}" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Calendar</p>
                    </a>
                </li>

                <!-- ADMIN ONLY -->
                @if(auth()->check() )
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Users</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>

    </div>
</aside>
