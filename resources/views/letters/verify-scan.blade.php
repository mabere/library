<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Verifikasi Surat (Scan QR)</h2>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted mb-3">
                Arahkan kamera ke QR Code pada surat. Pastikan menggunakan HTTPS agar kamera bisa diakses.
            </p>

            <div class="ratio ratio-4x3 mb-3 bg-light border rounded">
                <video id="qr-video" class="w-100 h-100" muted playsinline></video>
            </div>

            <div class="d-flex gap-2">
                <button id="btn-start" class="btn btn-primary">Mulai Scan</button>
                <button id="btn-stop" class="btn btn-outline-secondary" disabled>Stop</button>
                <a href="{{ route('verify.form') }}" class="btn btn-outline-secondary">Upload Manual</a>
            </div>

            <div id="scan-status" class="mt-3 text-muted small"></div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/qr-scanner@1.4.2/qr-scanner.min.js"></script>
        <script>
            const video = document.getElementById('qr-video');
            const statusEl = document.getElementById('scan-status');
            const btnStart = document.getElementById('btn-start');
            const btnStop = document.getElementById('btn-stop');

            if (typeof QrScanner !== 'undefined') {
                QrScanner.WORKER_PATH = 'https://unpkg.com/qr-scanner@1.4.2/qr-scanner-worker.min.js';
            }

            let scanner = null;

            const parseToken = (text) => {
                try {
                    const url = new URL(text);
                    const parts = url.pathname.split('/').filter(Boolean);
                    return parts[parts.length - 1] || null;
                } catch (e) {
                    return text?.trim() || null;
                }
            };

            const onDecode = (result) => {
                const text = result?.data || result;
                const token = parseToken(text);
                if (token) {
                    window.location.href = `/letter/verify/${token}?method=scan`;
                } else {
                    statusEl.textContent = 'QR tidak valid.';
                }
            };

            btnStart.addEventListener('click', async () => {
                if (!scanner) {
                    scanner = new QrScanner(video, onDecode, { highlightScanRegion: true });
                }
                try {
                    await scanner.start();
                    statusEl.textContent = 'Scanning...';
                    btnStart.disabled = true;
                    btnStop.disabled = false;
                } catch (err) {
                    statusEl.textContent = 'Gagal mengakses kamera. Pastikan izin kamera diaktifkan.';
                }
            });

            btnStop.addEventListener('click', () => {
                if (scanner) {
                    scanner.stop();
                    statusEl.textContent = 'Scan dihentikan.';
                    btnStart.disabled = false;
                    btnStop.disabled = true;
                }
            });
        </script>
    @endpush
</x-app-layout>
