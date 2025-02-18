<x-app-layout>
    <!-- Basic Layout -->
    <div class="mt-4">
        <h2>Register User</h2>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Register Admin</h5>
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

                            <div class="mb-6">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    pattern="[a-zA-Z0-9._%+-]+@gmail\.com"
                                    title="Masukkan alamat Gmail yang valid, misalnya user@gmail.com"
                                    placeholder="Enter your Gmail address">
                            </div>

                            <div class="mb-6">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required
                                        minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="Minimal 8 karakter, 1 huruf besar, 1 huruf kecil, dan 1 angka"
                                        placeholder="Enter password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <script>
                                // Toggle password visibility
                                document.querySelectorAll('.toggle-password').forEach(button => {
                                    button.addEventListener('click', function() {
                                        let target = document.getElementById(this.getAttribute('data-target'));
                                        if (target.type === "password") {
                                            target.type = "text";
                                            this.innerHTML = '<i class="fa fa-eye-slash"></i>';
                                        } else {
                                            target.type = "password";
                                            this.innerHTML = '<i class="fa fa-eye"></i>';
                                        }
                                    });
                                });
                            </script>


                            <!-- Confirm Password -->
                            <div class="mb-6">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm password" required />
                            </div>

                            <!-- Role -->
                            <div class="mb-6">
                                <label class="form-label" for="role">User Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="" disabled selected>Select role</option>
                                    <option value="admin">Admin</option>
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
    @push('script')
        <script>
            document.getElementById('password').addEventListener('input', function() {
                let password = this.value;
                let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

                if (!regex.test(password)) {
                    this.setCustomValidity(
                        "Password harus memiliki minimal 8 karakter, 1 huruf besar, 1 huruf kecil, dan 1 angka.");
                } else {
                    this.setCustomValidity("");
                }
            });
        </script>
    @endpush
</x-app-layout>
