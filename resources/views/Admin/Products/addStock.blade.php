<x-app-layout>
    <div class="mt-4">
        <h2>Add Stock</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Stock</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.addStock', $data['data_product']->id) }}" name="addStock"
                            method="POST">
                            @csrf

                            <!-- Stock -->
                            <div class="mb-6">
                                <label class="form-label" for="stock">Stock</label>
                                <input type="text" class="form-control" id="stock" name="stock"
                                    value="{{ old('stock', $data['data_stock']->stock ?? '') }}"
                                    placeholder="Enter stock quantity" />
                            </div>

                            <!-- Expired Date -->
                            <div class="mb-6">
                                <label class="form-label" for="expired_at">Expired Date and Time</label>
                                <input type="datetime-local" class="form-control" id="expired_at" name="expired_at"
                                    value="{{ old('expired_at', isset($data['data_stock']) ? \Carbon\Carbon::parse($data['data_stock']->expired_at)->format('Y-m-d\TH:i') : '') }}" />
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
