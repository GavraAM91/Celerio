<x-app-layout>
    <div class="mt-4">
        <h2>Add Coupon</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Coupon</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('coupon.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name_coupon" class="form-label">Coupon Name</label>
                                <input type="text" class="form-control" id="name_coupon" name="name_coupon"
                                    value="{{ old('name_coupon') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="coupon_description" class="form-label">Coupon Description</label>
                                <textarea class="form-control" id="coupon_description" name="coupon_description" required>{{ old('coupon_description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="value_coupon" class="form-label">Value Coupon (Rp)</label>
                                <input type="number" class="form-control" id="value_coupon" name="value_coupon"
                                    value="{{ old('value_coupon') }}">
                            </div>

                            <div class="mb-3">
                                <label for="percentage_coupon" class="form-label">Percentage Coupon (%)</label>
                                <input type="number" class="form-control" id="percentage_coupon"
                                    name="percentage_coupon" value="{{ old('percentage_coupon') }}">
                            </div>

                            <div class="mb-3">
                                <label for="minimum_usage_coupon" class="form-label">Minimum Usage (Rp)</label>
                                <input type="number" class="form-control" id="minimum_usage_coupon"
                                    name="minimum_usage_coupon" value="{{ old('minimum_usage_coupon') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="expired_at" class="form-label">Expired Date</label>
                                <input type="date" class="form-control" id="expired_at" name="expired_at"
                                    value="{{ old('expired_at') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="total_coupon" class="form-label">Total Coupon</label>
                                <input type="number" class="form-control" id="total_coupon" name="total_coupon"
                                    value="{{ old('total_coupon') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Coupon Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="" disabled selected>Select status</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.history.back();">Kembali</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
