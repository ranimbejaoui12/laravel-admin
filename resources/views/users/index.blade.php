@extends('layout')

@section('title', 'Users Management')
@section('header', 'Users List')

@section('content')

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-users mr-1"></i> Users Management
        </h3>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table id="users_table" class="table table-hover table-striped align-middle">

                <thead class="bg-light">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                    <tr>

                        <td>{{ $user->name }}</td>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $user->role->name }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            @if($user->role == \App\Enums\UserRoles::DOCTOR)

                                @if($user->status == 'accepted')
                                    <span class="badge bg-success">Accepted</span>

                                @elseif($user->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>

                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif

                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-center">

                            <a href="{{ route('users.edit', $user) }}"
                               class="btn btn-sm btn-warning"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('users.destroy', $user) }}"
                                  class="d-inline-block">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            {{-- Doctor actions --}}
                            @if($user->role == \App\Enums\UserRoles::DOCTOR && $user->status == 'pending')

                                <form action="{{ route('admin.acceptDoctor', $user->id) }}"
                                      method="POST"
                                      class="d-inline-block">
                                    @csrf
                                    @method('PUT')

                                    <button class="btn btn-sm btn-success"
                                            onclick="return confirm('Accept this doctor?')">
                                        ✔
                                    </button>
                                </form>

                                <form action="{{ route('admin.rejectDoctor', $user->id) }}"
                                      method="POST"
                                      class="d-inline-block">
                                    @csrf
                                    @method('PUT')

                                    <button class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Reject this doctor?')">
                                        ✖
                                    </button>
                                </form>

                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Floating Add Button --}}
        <a href="#" data-toggle="modal" data-target="#addUser"
        class="btn btn-success rounded-circle shadow"
        style="position: fixed; bottom: 30px; right: 30px; width: 55px; height: 55px; display:flex; align-items:center; justify-content:center;">
            <i class="fas fa-user-plus"></i>
        </a>
    </div>
</div>

@include('modals._add_user')

@endsection

@push('scripts')
<script>
    $(function () {
        $('#users_table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: false,
            autoWidth: false
        });
    });
</script>
@endpush
