@php
use App\Enums\UserRoles;
@endphp
<!--popOut add user modal -->
<div class="modal fade" id="addUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add a new user</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form class="needs-validation" method="post" action="{{ route('users.store') }}" novalidate>
                        @csrf

                        <!-- Role -->
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control" onchange="toggleFields()" required>
                                <option value="">-- Select Role --</option>
                                @foreach (App\Enums\UserRoles::values() as $key => $value)
                                    <option value="{{ $value }}">{{ $key }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Basic User info -->
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input id="lastname" name="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" required>
                            @error('lastname')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Phone (required for all roles) -->
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Patient Fields -->
                        <div id="patientFields" style="display:none;">
                            <div class="form-group">
                                <label for="noSSocial">No S Social</label>
                                <input id="noSSocial" name="noSSocial" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input id="dob" name="dob" type="date" class="form-control">
                            </div>
                        </div>

                        <!-- Doctor Fields -->
                        <div id="doctorFields" style="display:none;">
                            <div class="form-group">
                                <label for="specialty">Specialty</label>
                                <input id="specialty" name="specialty" type="text" class="form-control">
                            </div>
                        </div>

                        <!-- Admin Fields (optional) -->
                        <div id="adminFields" style="display:none;">
                            <div class="form-group">
                                <label for="admin_notes">Admin Notes</label>
                                <textarea id="admin_notes" name="admin_notes" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script>
function toggleFields() {
    const role = document.getElementById('role').value;

    const patientFields = document.getElementById('patientFields');
    const doctorFields = document.getElementById('doctorFields');
    const adminFields = document.getElementById('adminFields');

    // Hide all optional fields first
    [patientFields, doctorFields, adminFields].forEach(div => div.style.display = 'none');

    // Show fields according to role
    if(role == '{{ UserRoles::PATIENT->value }}') {
        patientFields.style.display = 'block';
    } else if(role == '{{ UserRoles::DOCTOR->value }}') {
        doctorFields.style.display = 'block';
    } else if(role == '{{ UserRoles::ADMIN->value }}') {
        adminFields.style.display = 'block';
    }
}

// Initial toggle on page load
window.addEventListener('DOMContentLoaded', toggleFields);
document.getElementById('role').addEventListener('change', toggleFields);
</script>