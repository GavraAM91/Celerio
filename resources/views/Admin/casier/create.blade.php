<x-app-layout>
    <!-- Basic Layout -->
    <div class="mt-4">
        <h2>Register User</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Register User</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.register') }}" method="POST">
                            @csrf

                            <!-- Name -->
                            <div class="mb-6">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter full name" required />
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            required pattern="[a-zA-Z0-9._%+-]+@gmail\.com"
                                            title="Masukkan alamat Gmail yang valid, misalnya user@gmail.com"
                                            placeholder="Enter your Gmail address">
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Kata Sandi</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password"
                                                required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                title="Minimal 8 karakter, 1 huruf besar, 1 huruf kecil, dan 1 angka"
                                                placeholder="Enter password">
                                            <span
                                                class="input-group-text cursor-pointer bg-white border-gray-300 rounded-md shadow-sm"
                                                onclick="togglePassword()">
                                                <i class="bx bx-hide"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Ketentuan Password -->
                            <ul id="password-requirements" class="list-unstyled">
                                <li class="text-danger">Minimal 8 karakter</li>
                                <li class="text-danger">Harus ada huruf besar</li>
                                <li class="text-danger">Harus ada huruf kecil</li>
                                <li class="text-danger">Harus ada angka</li>
                            </ul>

                            <!-- Pesan Ketidakcocokan Password -->
                            <div id="password-match" class="d-none text-danger mb-3">
                                Password tidak cocok!
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm password" required>
                            </div>

                            <!-- Role -->
                            <div class="mb-6">
                                <label class="form-label" for="role">User Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="" disabled selected>Select role</option>
                                    <option value="casier">Casier</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Register</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.history.back();">Kembali</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Script JavaScript untuk validasi -->
    <script>
        //buka password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.input-group-text i');

            // Jika tipe input adalah password, ubah ke text
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                // Ganti ikon (misalnya dari bx-hide ke bx-show)
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            } else {
                // Kembalikan ke tipe password
                passwordInput.type = "password";
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('password_confirmation');
            const submitButton = document.getElementById('submit-button');
            const passwordMatchText = document.getElementById('password-match');
            const passwordRequirements = document.querySelectorAll('#password-requirements li');


            //function validasi password
            function validatePassword() {
                const password = passwordField.value;
                let isValid = true;

                const rules = [{
                        regex: /.{8,}/,
                        element: passwordRequirements[0]
                    }, // Minimal 8 karakter
                    {
                        regex: /[A-Z]/,
                        element: passwordRequirements[1]
                    },
                    {
                        regex: /[a-z]/,
                        element: passwordRequirements[2]
                    }, // Huruf kecil
                    {
                        regex: /\d/,
                        element: passwordRequirements[3]
                    } // Angka
                ];

                rules.forEach(rule => {
                    if (rule.regex.test(password)) {
                        rule.element.classList.remove('text-danger');
                        rule.element.classList.add('text-success');
                    } else {
                        rule.element.classList.remove('text-success');
                        rule.element.classList.add('text-danger');
                        isValid = false;
                    }
                });

                return isValid;
            }

            function checkPasswordMatch() {
                if (passwordField.value !== confirmPasswordField.value) {
                    passwordMatchText.classList.remove('d-none');
                    submitButton.disabled = true;
                } else {
                    passwordMatchText.classList.add('d-none');
                    // Submit button hanya aktif jika password valid
                    submitButton.disabled = !validatePassword();
                }
            }

            passwordField.addEventListener('input', function() {
                validatePassword();
                checkPasswordMatch();
            });

            confirmPasswordField.addEventListener('input', checkPasswordMatch);
        });
    </script>
</x-app-layout>
