<x-app-layout>
    <!-- Basic Layout -->
    <div class="mt-4">
        <h2>Update User</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Update User</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            <!-- Name -->
                            <div class="mb-6">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" placeholder="Enter full name" required />
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" placeholder="Enter email" required />
                            </div>

                            <!-- Password (optional) -->
                            <div class="mb-6">
                                <label class="form-label" for="password">New Password (leave blank to keep current
                                    password)</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter new password" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-6">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm new password" />
                            </div>

                            <!-- Role -->
                            <div class="mb-6">
                                <label class="form-label" for="role">User Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="casier" {{ old('role', $user->role) == 'casier' ? 'selected' : '' }}>
                                        Casier</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.history.back();">Kembali</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
