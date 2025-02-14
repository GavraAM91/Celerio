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
                            <!-- Dropdown untuk memilih tipe membership -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="membership-type">Tipe Membership</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="membership-type">
                                        <option value="non-member">Non Member</option>
                                        <option value="member">Member</option>
                                    </select>
                                </div>

                                <!-- get type mmember-->
                                {{-- <input type="hidden" name="membership_type" id="hidden-membership-type" value="type3"> --}}
                            </div>

                            <!-- MEMBERSHIP DATA (Disembunyikan secara default) -->
                            <div id="membership-section" style="display: none;">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="membership-code">Kode Membership</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="membership-code"
                                            placeholder="MBR0000000001" />
                                    </div>
                                </div>
                                <div id="membership-data" class="mt-3"></div>
                                <!-- get type mmember-->
                                {{-- <input type="hidden" name="membership_type" id="hidden-membership-type" value=""> --}}
                            </div>


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
                                                <th>Harga Awal</th>
                                                <th>Harga Jual</th>
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
                                <!-- TOTAL HARGA BARANG SEMENTARA-->
                                <label>Total Harga Barang </label>
                                <input type="text" id="productTotal" class="form-control" readonly>

                                <label>Total Pajak (PPN 12%)</label>
                                <input type="text" id="taxTotal" class="form-control" readonly>

                                <!-- Coupon Name -->
                                <div class="row my-1 mx-0">
                                    <label class="" for="coupon-name">Coupon Name</label>
                                    <input type="text" class="form-control" id="coupon-name"
                                        placeholder="#BELANJATERUS" />
                                </div>

                                <div id="coupon-data" class="mt-3"></div>

                                {{-- <label>Diskon Membership (%) (Opsional)</label>
                                <div class="input-group">
                                    <input type="number" id="discountInput" class="form-control" placeholder="0">
                                    <button id="applyDiscount" class="btn btn-primary">Terapkan</button>
                                </div> --}}

                                <label>Total Harga Dengan Diskon</label>
                                <input type="text" id="totalDiscount" class="form-control" readonly>


                                <label> Jumlah Uang </label>
                                <input type="text" class="form-control" id="jumlahUang" placeholder="Rp 100000" />

                                <label> Uang Kembalian </label>
                                <input type="text" class="form-control" id="uangKembalian" />
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
            //apakah ada membership 
            document.getElementById("membership-type").addEventListener("change", function() {
                let membershipSection = document.getElementById("membership-section");
                let hiddenMembershipType = document.getElementById("hidden-membership-type");

                if (this.value === "member") {
                    membershipSection.style.display = "block";
                    hiddenMembershipType.value = "";
                } else {
                    membershipSection.style.display = "none";
                    document.getElementById("membership-data").innerHTML = "";
                    hiddenMembershipType.value = "type3";
                }
            });

            //search data membership
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
                        <div class="card p-3 my-2">
                            <h5>Data Membership</h5>
                            <p><strong>Nama:</strong> ${response.data.name}</p>
                            <p><strong>Point:</strong> ${response.data.point}</p>
                            <p><strong>Tipe Member:</strong> ${response.data.type}</p>
                            <input type="hidden" name="membership_type" id="hidden-membership-type" value="${response.data.type}">
                        </div>
                    `);
                            } else {
                                $('#membership-data').html(
                                    '<p class="text-danger">Data tidak ditemukan</p>');
                                $('#hidden-membership-type').val("");
                            }
                        },
                        error: function() {
                            $('#membership-data').html(
                                '<p class="text-danger">Terjadi kesalahan dalam mengambil data</p>');
                            $('#hidden-membership-type').val("");
                        }
                    });
                } else {
                    $('#membership-data').html('<p class="text-warning">Masukkan kode minimal 5 karakter</p>');
                    $('#hidden-membership-type').val(""); // Reset jika input kurang dari 5 karakter
                }
            });

            //get product
            $(document).ready(function() {

                $("#addProduct").click(function() {
                    let newRow = `
            <tr>
                <td><input type="text" class="form-control product-name" placeholder="Nama Produk"></td>
                <td><input type="number" class="form-control stock" value="0" min="0" readonly></td>
                <td><input type="number" class="form-control quantity" value="1" min="1"></td>
                <td class="price">Rp 0</td>
                <td class="selling_price">Rp 0</td>
                <td class="total">Rp 0</td>
                <td><button class="btn btn-danger removeProduct">Hapus</button></td>
                <input type="hidden" class="sellingMultiplier-input" value="0">
            </tr>
        `;
                    $("#productTableBody").append(newRow);
                });

                //remove product
                $(document).on("click", ".removeProduct", function() {
                    $(this).closest("tr").remove();
                    calculateProductTotal();
                });

                //search data from db
                $(document).on("input", ".product-name", function() {
                    let row = $(this).closest("tr");
                    let productName = $(this).val();
                    let membershipType = $("#hidden-membership-type").val() || "type3"; // Default jika kosong

                    if (productName.length >= 4) {
                        let url = "{{ route('sales.searchProduct') }}?productName=" + encodeURIComponent(
                            productName) + "&membershipType=" + encodeURIComponent(membershipType);

                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(response) {
                                if (response.success && response.data) {
                                    let stock = response.data.product.stock;
                                    let price = response.data.product.product_price;
                                    let sellingMultiplier = response.data.sellingPrice
                                        .selling_price || 1; // Default multiplier 1
                                    let quantity = parseInt(row.find(".quantity").val());

                                    let sellingPrice = price * sellingMultiplier;
                                    let total = sellingPrice * quantity;

                                    row.find(".stock").val(stock);
                                    row.find(".price").text("Rp " + price.toLocaleString("id-ID"));
                                    row.find(".sellingMultiplier-input").val(sellingMultiplier);
                                    row.find(".selling_price").text("Rp " + sellingPrice
                                        .toLocaleString("id-ID"));
                                    row.find(".total").text("Rp " + total.toLocaleString("id-ID"));

                                    calculateProductTotal();
                                } else {
                                    resetRow(row);
                                }
                            },
                            error: function() {
                                resetRow(row);
                            }
                        });
                    } else {
                        resetRow(row);
                    }
                });

                //update total saat quantity berubah
                $(document).on("input", ".quantity", function() {
                    let row = $(this).closest("tr");
                    updateRowTotal(row);
                    calculateProductTotal();

                    let productTotal = parseFloat($("#productTotal").val().replace(/[^\d]/g, "")) || 0;
                    let totalWithTax = calculateTax(productTotal);
                    countCoupon(totalWithTax);
                    calculatePayment();
                });

                //update total
                function updateRowTotal(row) {
                    let price = parseFloat(row.find(".price").text().replace("Rp ", "").replace(/\./g, "")) || 0;
                    let sellingMultiplier = parseFloat(row.find(".sellingMultiplier-input").val()) || 1;
                    let quantity = parseInt(row.find(".quantity").val()) || 1;

                    let sellingPrice = price * sellingMultiplier;
                    let total = sellingPrice * quantity;

                    // console.log("price : ", price);
                    // console.log("margin : ", sellingMultiplier);
                    // console.log("quantity : ", quantity);
                    // console.log("total : ", total);
                    // console.log("sellingprice : ", sellingPrice);

                    row.find(".selling_price").text("Rp " + sellingPrice.toLocaleString("id-ID"));
                    row.find(".total").text("Rp " + total.toLocaleString("id-ID"));
                }

                function resetRow(row) {
                    row.find(".stock").val(0);
                    row.find(".price").text("Rp 0");
                    row.find(".selling_price").text("Rp 0");
                    row.find(".total").text("Rp 0");
                    row.find(".sellingMultiplier-input").val(0);
                }

                function calculateProductTotal() {
                    let productTotal = 0;
                    $(".total").each(function() {
                        let total = parseFloat($(this).text().replace("Rp ", "").replace(/\./g, "")) || 0;
                        productTotal += total;
                    });

                    $("#productTotal").val("Rp " + productTotal.toLocaleString("id-ID"));

                    calculateTax(productTotal);
                }
            });

            //coupon
            $(document).ready(function() {
                // Event listener untuk input kupon
                $('#coupon-name').on('input', function() {
                    let name = $(this).val();
                    if (name.length >= 5) {
                        let url = "{{ route('sales.searchCoupon') }}?name=" + encodeURIComponent(name);
                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    $('#coupon-data').html(`
                            <div class="card p-3 my-2">
                                <p><strong>Nama:</strong> ${response.data.name_coupon}</p>
                                <p><strong>Minimal Penggunaan:</strong> ${response.data.minimum_usage_coupon}</p>
                                <p><strong>Nilai Kupon:</strong> ${response.data.value_coupon || response.data.percentage_coupon}</p>
                                <p><strong>Total Kupon:</strong> ${response.data.total_coupon}</p>
                                <p><strong>Kupon Terpakai:</strong> ${response.data.used_coupon}</p>
                                <p><strong>Status Kupon:</strong> ${response.data.status}</p>
                                <input type="hidden" id="coupon-value" value="${response.data.value_coupon || 0}">
                                <input type="hidden" id="coupon-percentage" value="${response.data.percentage_coupon || 0}">
                                <input type="hidden" id="coupon-id" value="${response.data.id || 0}">
                            </div>
                        `);

                                    // Ambil total pajak dari #taxTotal
                                    let totalWithTax = parseFloat($('#taxTotal').val().replace(
                                        /[^\d.]/g, '')) || 0;
                                    console.log("Total harga sebelum diskon:", totalWithTax);

                                    countCoupon(totalWithTax);
                                } else {
                                    $('#coupon-data').html(
                                        '<p class="text-danger">Data tidak ditemukan</p>');
                                }
                            },
                            error: function() {
                                $('#coupon-data').html(
                                    '<p class="text-danger">Terjadi kesalahan dalam mengambil data</p>'
                                );
                            }
                        });
                    } else {
                        $('#coupon-data').html('');
                    }
                });
            });


            // Fungsi untuk menghitung pajak
            function calculateTax(productTotal) {
                let taxRate = 0.12;
                let taxAmount = productTotal * taxRate;
                let totalWithTax = productTotal + taxAmount;

                // console.log('pajak :', totalWithTax);

                // Menampilkan pajak yang dihitung
                $("#taxAmount").val("Rp " + taxAmount.toLocaleString("id-ID"));

                // Menampilkan total setelah pajak
                $("#taxTotal").val("Rp " + totalWithTax.toLocaleString("id-ID"));

                // Hitung ulang kupon berdasarkan total setelah pajak
                countCoupon(totalWithTax);
                return totalWithTax;
            }

            // Menghitung pembayaran berdasarkan total harga setelah diskon
            function calculatePayment() {
                // Ambil total harga setelah diskon
                let finalTotalAfterDiscount = parseFloat($("#totalDiscount").val().replace(/[^\d]/g, '')) || 0;

                // Ambil jumlah uang yang diinputkan
                let inputMoney = parseFloat($('#jumlahUang').val().replace(/[^\d]/g, '')) || 0;

                // Hitung kembalian
                let changeMoney = inputMoney - finalTotalAfterDiscount;

                console.log("Total setelah diskon:", finalTotalAfterDiscount);
                console.log("Uang dibayarkan:", inputMoney);
                console.log("Kembalian:", changeMoney);

                // Menampilkan hasil kembalian
                if (changeMoney >= 0) {
                    $('#uangKembalian').val("Rp " + changeMoney.toLocaleString("id-ID"));
                } else {
                    $('#uangKembalian').val("Uang tidak cukup");
                }
            }

            function countCoupon(totalWithTax) {
                let couponValue = parseFloat($('#coupon-value').val()) || 0;
                let couponPercentage = parseFloat($('#coupon-percentage').val()) || 0;
                let couponName = $('#coupon-name').val().trim();

                // Pastikan total pajak diambil dengan benar
                let totalWithTaxRaw = $('#taxTotal').val();
                console.log("Raw tax total:", totalWithTaxRaw);


                totalWithTax = parseFloat(totalWithTaxRaw.replace(/[^\d]/g, '')) || 0;
                console.log("Total harga setelah pajak (parsed):", totalWithTax);

                let discountAmount = 0;
                if (couponValue > 0) {
                    discountAmount = couponValue;
                } else if (couponPercentage > 0) {
                    discountAmount = totalWithTax * (couponPercentage / 100);
                }

                let finalTotal = totalWithTax - discountAmount;
                if (finalTotal < 0) finalTotal = 0;

                // Cek apakah tidak ada kupon yang dipilih
                if (couponName === "" || (couponValue === 0 && couponPercentage === 0)) {
                    $("#totalDiscount").val("TIDAK ADA KUPON");
                } else {
                    $("#totalDiscount").val("Rp " + finalTotal.toLocaleString("id-ID"));
                }
                calculatePayment();
            }

            // Event listener untuk input kupon
            $('#coupon-name, #coupon-value, #coupon-percentage').on('input', function() {
                countCoupon();
            });

            // Event listener untuk jumlah uang yang dimasukkan
            $('#jumlahUang').on('input', function() {
                calculatePayment();
            });

            // Event listener untuk total diskon agar kembalian otomatis dihitung ulang
            $('#totalDiscount').on('input', function() {
                calculatePayment();
            });
        </script>
    @endpush
</x-app-layout>
