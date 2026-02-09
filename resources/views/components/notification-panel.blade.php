@php
    $user = auth()->user();
    $items = $user ? $user->unreadNotifications()->take(5)->get() : collect();
@endphp

@if($user)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0 fw-semibold">
                    Notifikasi Terbaru
                </h6>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">
                    Lihat Semua
                </a>
            </div>

            @if($items->count())
                <div class="list-group list-group-flush">
                    @foreach($items as $notification)
                        @php($data = $notification->data ?? [])
                        <a href="{{ route('notifications.read', $notification->id) }}" class="list-group-item list-group-item-action">
                            <div class="fw-semibold">{{ $data['title'] ?? 'Notifikasi' }}</div>
                            <div class="text-muted small">
                                Status: {{ strtoupper(str_replace('_',' ', $data['status'] ?? '-')) }}
                            </div>
                            @if(!empty($data['note']))
                                <div class="text-muted small">
                                    Catatan: {{ $data['note'] }}
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-muted">Tidak ada notifikasi baru.</div>
            @endif
        </div>
    </div>
@endif
