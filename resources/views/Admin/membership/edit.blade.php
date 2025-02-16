<x-app-layout>
    <div class="mt-4">
        <h2>Update Member</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Update Member</h5>
                        <small class="text-body float-end">Edit member details</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('membership.update', $membership_data->id) }}" method="POST">
                            @csrf

                            <!-- Name -->
                            <div class="mb-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $membership_data->name) }}" required />
                                <input type="hidden" name="id" id="id" value={{ $membership->id }}>
                            </div>

                            <!-- Username -->
                            <div class="mb-6">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username', $membership_data->username) }}" required />
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $membership_data->email) }}" required />
                            </div>

                            <!-- Type -->
                            <div class="mb-6">
                                <label class="form-label" for="type">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="type1" {{ $membership_data->type == 'type1' ? 'selected' : '' }}>
                                        Type 1</option>
                                    <option value="type2" {{ $membership_data->type == 'type2' ? 'selected' : '' }}>
                                        Type 2</option>
                                </select>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-6">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', $membership_data->phone_number) }}" required />
                            </div>

                            <!-- Address -->
                            <div class="mb-6">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ old('address', $membership_data->address) }}" required />
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ $membership_data->status == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="inactive"
                                        {{ $membership_data->status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('membership.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
