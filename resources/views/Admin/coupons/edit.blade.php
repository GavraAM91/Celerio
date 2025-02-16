<x-app-layout>
    <div class="mt-4">
        <h2>Update Coupon</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Update Coupon</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('coupon.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $coupon->id }}">

                            <div class="mb-6">
                                <label class="form-label" for="name_coupon">Coupon Name</label>
                                <input type="text" class="form-control" id="name_coupon" name="name_coupon"
                                    value="{{ $coupon->name_coupon }}" required />
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="coupon_description">Description</label>
                                <textarea class="form-control" id="coupon_description" name="coupon_description" required>{{ $coupon->coupon_description }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="value_coupon">Value</label>
                                <input type="number" class="form-control" id="value_coupon" name="value_coupon"
                                    value="{{ $coupon->value_coupon }}" required />
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="percentage_coupon">Percentage</label>
                                <input type="number" class="form-control" id="percentage_coupon"
                                    name="percentage_coupon" value="{{ $coupon->percentage_coupon }}" />
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="minimum_usage_coupon">Minimum Usage</label>
                                <input type="number" class="form-control" id="minimum_usage_coupon"
                                    name="minimum_usage_coupon" value="{{ $coupon->minimum_usage_coupon }}" />
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="expired_at">Expired Date</label>
                                <input type="date" class="form-control" id="expired_at" name="expired_at"
                                    value="{{ old('expired_at', $data->expired_at ?? '') }}" />
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="total_coupon">Total Coupons</label>
                                <input type="number" class="form-control" id="total_coupon" name="total_coupon"
                                    value="{{ $coupon->total_coupon }}" required />
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ $coupon->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $coupon->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.history.back();">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
