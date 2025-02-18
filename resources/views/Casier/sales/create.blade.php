<x-app-layout>
    <div class="mx-2 my-2">
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Penjualan Kasir</h5>
                    </div>
                    <div class="card-body">
                        <form id="purchaseForm" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="membership-type">Tipe Membership</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="membership-type">
                                        <option value="non-member">Non Member</option>
                                        <option value="member">Member</option>
                                    </select>
                                </div>
                            </div>

                            <div id="membership-section" style="display: none;">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="membership-code">Kode Membership</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="membership-code"
                                            placeholder="MBR0000000001" />
                                    </div>
                                </div>
                                <div id="membership-data" class="mt-3"></div>
                            </div>

                            <div class="container mt-4">
                                <button type="button" id="addProduct" class="btn btn-primary mb-3">Tambah
                                    Produk</button>
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Produk</th>
                                                <th>Stock Tersedia</th>
                                                <th>Jumlah</th>
                                                <th>Harga Awal</th>
                                                <th>Harga Jual</th>
                                                <th>Total</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="productTableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-8">
                                <div class="row">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="coupon-name" class="form-label">Coupon Name</label>
                                            <input type="text" class="form-control" id="coupon-name"
                                                placeholder="#BELANJATERUS" />
                                        </div>
                                        <div id="coupon-data" class="mt-3"></div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <strong><label class="form-label">Total Harga Dengan Diskon</label></strong>
                                            <input id="totalDiscount" class="form-control-plaintext"></p>

                                            <strong><label class="form-label">Total Harga Barang</label></strong>
                                            <input id="productTotal" class="form-control-plaintext"></p>

                                            <strong><label class="form-label">Total Pajak (PPN 12%)</label></strong>
                                            <input id="taxTotal" class="form-control-plaintext"></p>

                                            <strong><label class="form-label">Harga Final</label></strong>
                                            <input id="finalPrice" class="form-control-plaintext"></p>

                                            <strong><label class="form-label">Jumlah Uang</label></strong>
                                            <input type="text" class="form-control" id="jumlahUang"
                                                placeholder="Rp 100000" / required>

                                            <strong><label class="form-label">Uang Kembalian</label></strong>
                                            <input id="uangKembalian" class="form-control-plaintext"></p>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
                            </div>


                            <button type="submit" class="btn btn-success mt-3">Simpan</button>
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
                    hiddenMembershipType.value = "member";
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
                            <input type="hidden" name="membership_id" id="hidden-membership_id" value="${response.data.id}">
                                <div class="form-group">
                                    <label for="use-points">Gunakan Poin:</label>
                                    <input type="number" id="use-points" class="form-control" min="0" max="${response.data.point}" value="0">
                                </div>
                                <button id="apply-points" class="btn btn-primary mt-2">Gunakan</button>
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

            // Event listener untuk tombol "Gunakan"
            $(document).on('click', '#apply-points', function() {
                let availablePoints = parseInt($('#membership-points').val()) || 0;
                let usedPoints = parseInt($('#use-points').val()) || 0;

                if (usedPoints > availablePoints) {
                    alert("Poin yang dimasukkan melebihi jumlah yang tersedia.");
                    return;
                }

                let discountAmount = usedPoints * 100; // Contoh konversi poin ke nilai diskon
                $('#totalDiscount').val("Rp " + discountAmount.toLocaleString("id-ID"));
                updateFinalPrice();
                calculatePayment();
            });

            //get product
            $(document).ready(function() {

                $("#addProduct").click(function() {
                    let newRow = `
            <tr>
               <td><input type="text" class="form-control product_id" readonly></td>
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

                    if (productName.length >= 1) {
                        let url = "{{ route('sales.searchProduct') }}?productName=" + encodeURIComponent(
                            productName) + "&membershipType=" + encodeURIComponent(membershipType);

                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(response) {
                                if (response.success && response.data) {
                                    let product_id = response.data.product.id;
                                    let stock = response.data.product.stock;
                                    let price = response.data.product.product_price;
                                    let sellingMultiplier = response.data.sellingPrice
                                        .selling_price || 1; // Default multiplier 1
                                    let quantity = parseInt(row.find(".quantity").val());

                                    let sellingPrice = price * sellingMultiplier;
                                    let total = sellingPrice * quantity;

                                    row.find(".product_id").val(product_id);
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
                                <input type="hidden" id="coupon_id" value="${response.data.id || 0}">
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

            function updateFinalPrice() {
                let totalDiscount = $("#totalDiscount").val();
                let finalPrice;

                if (totalDiscount === "TIDAK ADA KUPON") {
                    finalPrice = $("#taxTotal").val(); // Gunakan harga setelah pajak jika tidak ada kupon
                } else {
                    finalPrice = totalDiscount; // Gunakan harga setelah diskon jika ada kupon
                }

                $("#finalPrice").val(finalPrice);
            }

            // Panggil updateFinalPrice() setelah menghitung kupon dan setelah perhitungan pajak
            $(document).on("input", "#coupon-name, .quantity, #jumlahUang", function() {
                updateFinalPrice();
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
                let finalTotalAfterDiscount = parseFloat($("#finalPrice").val().replace(/[^\d]/g, '')) || 0;

                // Ambil jumlah uang yang diinputkan
                let inputMoney = parseFloat($('#jumlahUang').val().replace(/[^\d]/g, '')) || 0;

                // Hitung kembalian
                let changeMoney = inputMoney - finalTotalAfterDiscount;

                // console.log("Total setelah diskon:", finalTotalAfterDiscount);
                // console.log("Uang dibayarkan:", inputMoney);
                // console.log("Kembalian:", changeMoney);

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
                // console.log("Raw tax total:", totalWithTaxRaw);


                totalWithTax = parseFloat(totalWithTaxRaw.replace(/[^\d]/g, '')) || 0;
                // console.log("Total harga setelah pajak (parsed):", totalWithTax);

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

                updateFinalPrice();
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


            $(document).ready(function() {
                $("#purchaseForm").submit(function(e) {
                    e.preventDefault(); // Mencegah reload form

                    let userId = $("#user_id").val();
                    let couponId = $("#coupon_id").length ? $("#coupon_id").val() : null;
                    let membership_id = $("#hidden-membership_id").length ? $("#hidden-membership_id").val() :
                        null;
                    let usedPoints = parseInt($('#use-points').val()) || 0;
                    let membershipId = $('#hidden-membership_id').val() || null;

                    let totalPrice = ($("#productTotal").val() || "0").replace(/[^\d]/g, '');
                    let tax = 0.12;
                    let totalPriceWithDiscount = $("#totalDiscount").length ? ($("#totalDiscount").val())
                        .replace(/[^\d]/g, '') : null;
                    let finalPrice = ($("#finalPrice").val() || "0").replace(/[^\d]/g, '');
                    let cashReceived = ($("#jumlahUang").val() || "0").replace(/[^\d]/g, '');
                    let change = cashReceived - finalPrice; // Hitung uang kembalian otomatis

                    function getProductData() {
                        let products = [];

                        $("#productTableBody tr").each(function() {
                            let row = $(this);

                            let productId = row.find(".product_id").val().trim();
                            let productName = row.find(".product-name").val()
                                .trim(); // Ubah ke product_name
                            let quantity = parseInt(row.find(".quantity").val()) || 0;

                            let sellingPriceText = row.find(".selling_price").text().trim()
                                .replace("Rp ", "").replace(/\./g, "").replace(",", ".");
                            let sellingPrice = parseFloat(sellingPriceText) || 0;

                            if (productId && productName && quantity > 0 && sellingPrice > 0) {
                                products.push({
                                    product_id: productId,
                                    product_name: productName, // Ubah dari name ke product_name
                                    quantity: quantity,
                                    selling_price: sellingPrice // Ubah dari price ke selling_price
                                });
                            }
                        });
                        return products;
                    }

                    // Panggil fungsi ini sebelum mengirim data ke server
                    let productData = getProductData();


                    let formData = {
                        user_id: userId,
                        membership_id: membership_id,
                        membership_id: membershipId,
                    used_points: usedPoints,
                        coupon_id: couponId,
                        total_price: totalPrice,
                        tax: tax,
                        total_price_with_discount: totalPriceWithDiscount,
                        final_price: finalPrice,
                        cash_received: cashReceived,
                        change: change,
                        data: productData
                    };

                    // console.log(formData);

                    $.ajax({
                        url: "{{ route('sales.PurchasedProduct') }}",
                        type: "POST",
                        data: JSON.stringify(formData),
                        contentType: "application/json",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            // Redirect langsung ke halaman DetailTransaction dengan invoice_sales
                            if (response.data.invoice_sales) {
                                window.location.href =
                                    "{{ route('sales.DetailTransaction') }}?invoice_sales=" +
                                    encodeURIComponent(response.data.invoice_sales);
                            } else {
                                console.error("invoice_sales tidak ditemukan dalam response.data:",
                                    response.data);
                            }
                        },
                        error: function(xhr) {
                            // alert("Gagal menyimpan transaksi. Cek kembali input Anda.");
                            showToast("Gagal menyimpan transaksi. Cek kembali input Anda.",
                                "error");
                            console.log(xhr.responseText);

                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
