@section('title', 'Verifikasi Surat')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-qr-code-scan"></i>
            Verifikasi Surat
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">

                            {{-- Error Message --}}
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ url('/verify') }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf

                                {{-- Upload QR --}}
                                <div class="mb-4">
                                    <label class="form-label">
                                        Upload QR Code
                                    </label>
                                    <input type="file"
                                           name="qr_image"
                                           class="form-control"
                                           accept="image/*"
                                           required>
                                    <div class="form-text">
                                        Format gambar: JPG, PNG, atau JPEG
                                    </div>
                                </div>

                                {{-- Action --}}
                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                            class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i>
                                        Verifikasi
                                    </button>
                                </div>

                            </form>

                            {{-- Alternative --}}
                            <div class="text-center mt-4">
                                <a href="{{ route('verify.scan') }}"
                                   class="link-primary small">
                                    <i class="bi bi-camera"></i>
                                    Verifikasi otomatis dengan kamera
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
