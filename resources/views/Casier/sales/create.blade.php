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

                            <!-- MEMBERSHIP DATA -->
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="membership-code">Kode Membership</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="membership-code"
                                        placeholder="MBR0000000001" />
                                </div>
                            </div>

                            <div id="membership-data" class="mt-3"></div>

                            <div class="container mt-4">
                                <button type="button" id="addProduct" class="btn btn-primary mb-3">Tambah
                                    Produk</button>

                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Stock Tersedia</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Total</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="productTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>Total Harga</label>
                                <input type="text" id="totalPrice" class="form-control" readonly>

                                <label>Diskon Membership (%) (Opsional)</label>
                                <div class="input-group">
                                    <input type="number" id="discountInput" class="form-control" placeholder="0">
                                    <button id="applyDiscount" class="btn btn-primary">Terapkan</button>
                                </div>

                                <label>Total Diskon</label>
                                <input type="text" id="totalDiscount" class="form-control" readonly>

                                <label>Total Akhir (PPN 12%)</label>
                                <input type="text" id="finalTotal" class="form-control" readonly>
                            </div>

                            <button id="saveTransaction" class="btn btn-success mt-3">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // get membership by code
            $(document).ready(function() {
                $('#membership-code').on('input', function() {
                    let code = $(this).val();
                    if (code.length >= 5) {
                        let url = "{{ route('sales.searchMembership') }}?code=" + encodeURIComponent(code);
                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    $('#membership-data').html(`
                                <div class="card p-3 my-2 ">
                                    <h5>Data Membership</h5>
                                    <p><strong>Nama:</strong> ${response.data.name}</p>
                                    <p><strong>Point:</strong> ${response.data.point}</p>
                                    <p><strong>Tipe Member:</strong> ${response.data.type}</p>
                                </div>
                            `);
                                } else {
                                    $('#membership-data').html(
                                        '<p class="text-danger">Data tidak ditemukan</p>');
                                }
                            },
                            error: function() {
                                $('#membership-data').html(
                                    '<p class="text-danger">Terjadi kesalahan dalam mengambil data</p>'
                                );
                            }
                        });
                    } else {
                        $('#membership-data').html(''); // Hapus tampilan jika input kurang dari 5 karakter
                    }
                });
            });

            $(document).ready(function() {
                loadSessionData();

                $("#addProduct").click(function() {
                    let newRow = `
                <tr>
                    <td><input type="text" class="form-control product-name" placeholder="Nama Produk"></td>
                    <td><input type="number" class="form-control stock" value="10" min="0" readonly></td>
                    <td><input type="number" class="form-control quantity" value="1" min="1"></td>
                    <td class="price">Rp 10.000</td>
                    <td class="total">Rp 10.000</td>
                    <td><button class="btn btn-danger removeProduct">Hapus</button></td>
                </tr>
            `;
                    $("#productTableBody").append(newRow);
                    updateTotal();
                    saveSessionData();
                });

                $(document).on("click", ".removeProduct", function() {
                    $(this).closest("tr").remove();
                    updateTotal();
                    saveSessionData();
                });

                $(document).on("input", ".quantity", function() {
                    let row = $(this).closest("tr");
                    let price = parseInt(row.find(".price").text().replace("Rp ", "").replace(".", "")) || 0;
                    let quantity = parseInt($(this).val()) || 1;
                    let total = price * quantity;
                    row.find(".total").text("Rp " + total.toLocaleString("id-ID"));
                    updateTotal();
                    saveSessionData();
                });

                $("#applyDiscount").click(function() {
                    updateTotal();
                });

                $("#clearTransaction").click(function() {
                    $("#productTableBody").empty();
                    sessionStorage.removeItem("kasirData");
                    updateTotal();
                });

                $("#saveTransaction").click(function() {
                    let transactionData = getTransactionData();
                    console.log("Mengirim ke sales.purchaseditem:", transactionData);
                    sessionStorage.removeItem("kasirData");
                    alert("Transaksi berhasil disimpan!");
                });

                function updateTotal() {
                    let totalPrice = 0;
                    $(".total").each(function() {
                        let price = parseInt($(this).text().replace("Rp ", "").replace(".", "")) || 0;
                        totalPrice += price;
                    });

                    let discount = parseInt($("#discountInput").val()) || 0;
                    let discountAmount = (totalPrice * discount) / 100;
                    let totalAfterDiscount = totalPrice - discountAmount;
                    let finalTotal = totalAfterDiscount * 1.12;

                    $("#totalPrice").val("Rp " + totalPrice.toLocaleString("id-ID"));
                    $("#totalDiscount").val("Rp " + discountAmount.toLocaleString("id-ID"));
                    $("#finalTotal").val("Rp " + finalTotal.toLocaleString("id-ID"));
                    saveSessionData();
                }

                function saveSessionData() {
                    let data = getTransactionData();
                    sessionStorage.setItem("kasirData", JSON.stringify(data));
                }

                function loadSessionData() {
                    let data = sessionStorage.getItem("kasirData");
                    if (data) {
                        data = JSON.parse(data);
                        $("#productTableBody").html(data.products);
                        $("#discountInput").val(data.discount);
                        $("#totalPrice").val(data.totalPrice);
                        $("#totalDiscount").val(data.totalDiscount);
                        $("#finalTotal").val(data.finalTotal);
                    }
                }

                function getTransactionData() {
                    return {
                        products: $("#productTableBody").html(),
                        discount: $("#discountInput").val(),
                        totalPrice: $("#totalPrice").val(),
                        totalDiscount: $("#totalDiscount").val(),
                        finalTotal: $("#finalTotal").val()
                    };
                }
            });
        </script>
    @endpush
</x-app-layout>
