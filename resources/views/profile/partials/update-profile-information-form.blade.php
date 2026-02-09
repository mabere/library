@section('title', 'Profil Akun')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-circle"></i>
            Profil Akun
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- ========================================================= --}}
                    {{-- PROFILE INFORMATION --}}
                    {{-- ========================================================= --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">

                            <h5 class="fw-semibold mb-1">
                                Informasi Profil
                            </h5>
                            <p class="text-muted small mb-4">
                                Perbarui nama dan alamat email akun Anda.
                            </p>

                            {{-- Email verification form --}}
                            <form id="send-verification"
                                  method="POST"
                                  action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')

                                {{-- NAME --}}
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- EMAIL --}}
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PHONE --}}
                                <div class="mb-3">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <input type="text"
                                           name="phone_number"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           value="{{ old('phone_number', $user->phone_number) }}"
                                           placeholder="628xxxxxxxxxx">
                                    <div class="form-text">Gunakan format internasional tanpa tanda +.</div>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- EMAIL NOT VERIFIED --}}
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-circle"></i>
                                        Email Anda belum diverifikasi.
                                        <button form="send-verification"
                                                class="btn btn-link p-0 ms-1">
                                            Kirim ulang email verifikasi
                                        </button>

                                        @if (session('status') === 'verification-link-sent')
                                            <div class="text-success small mt-2">
                                                <i class="bi bi-check-circle"></i>
                                                Link verifikasi baru telah dikirim.
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <button class="btn btn-primary mt-2">
                                    <i class="bi bi-save"></i>
                                    Simpan Profil
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <span class="text-success small ms-3">
                                        <i class="bi bi-check-circle"></i>
                                        Profil diperbarui.
                                    </span>
                                @endif
                            </form>

                        </div>
                    </div>

                    {{-- ========================================================= --}}
                    {{-- UPDATE PASSWORD --}}
                    {{-- ========================================================= --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">

                            <h5 class="fw-semibold mb-1">
                                Ubah Password
                            </h5>
                            <p class="text-muted small mb-4">
                                Pastikan menggunakan password yang kuat dan aman.
                            </p>

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')

                                {{-- CURRENT PASSWORD --}}
                                <div class="mb-3">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password"
                                           name="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- NEW PASSWORD --}}
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password"
                                           name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- CONFIRM PASSWORD --}}
                                <div class="mb-4">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control"
                                           required>
                                </div>

                                <button class="btn btn-primary">
                                    <i class="bi bi-key"></i>
                                    Perbarui Password
                                </button>

                                @if (session('status') === 'password-updated')
                                    <span class="text-success small ms-3">
                                        <i class="bi bi-check-circle"></i>
                                        Password diperbarui.
                                    </span>
                                @endif
                            </form>

                        </div>
                    </div>

                    {{-- ========================================================= --}}
                    {{-- DELETE ACCOUNT --}}
                    {{-- ========================================================= --}}
                    <div class="card border-danger">
                        <div class="card-body p-4">

                            <h5 class="fw-semibold text-danger mb-1">
                                Hapus Akun
                            </h5>
                            <p class="text-muted small mb-4">
                                Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
                            </p>

                            <form method="POST"
                                  action="{{ route('profile.destroy') }}"
                                  onsubmit="return confirm('Yakin ingin menghapus akun secara permanen?')">
                                @csrf
                                @method('DELETE')

                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password"
                                           name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-danger">
                                    <i class="bi bi-trash"></i>
                                    Hapus Akun
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
