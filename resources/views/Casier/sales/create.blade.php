<x-app-layout>
    @if (session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
            <div class="toast align-items-center text-white bg-danger border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="mx-2 my-2">
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Penjualan Kasir</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('sales.store') }}">
                            @csrf
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Kode Membership </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="basic-default-name"
                                        placeholder="MBR0000000001" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-company">Company</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="basic-default-company"
                                        placeholder="ACME Inc." />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-email">Email</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="basic-default-email" class="form-control"
                                            placeholder="john.doe" aria-label="john.doe"
                                            aria-describedby="basic-default-email2" />
                                        <span class="input-group-text" id="basic-default-email2">@example.com</span>
                                    </div>
                                    <div class="form-text">You can use letters, numbers & periods</div>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-phone">Phone No</label>
                                <div class="col-sm-10">
                                    <input type="text" id="basic-default-phone" class="form-control phone-mask"
                                        placeholder="658 799 8941" aria-label="658 799 8941"
                                        aria-describedby="basic-default-phone" />
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="basic-default-message">Message</label>
                                <div class="col-sm-10">
                                    <textarea id="basic-default-message" class="form-control" placeholder="Hi, Do you have a moment to talk Joe?"
                                        aria-label="Hi, Do you have a moment to talk Joe?" aria-describedby="basic-icon-default-message2"></textarea>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
