@section('title', 'Notifikasi')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-bell"></i>
            Notifikasi
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="d-flex justify-content-end mb-3">
                <form method="POST" action="{{ route('notifications.read_all') }}">
                    @csrf
                    <button class="btn btn-outline-secondary" type="submit">
                        Tandai semua dibaca
                    </button>
                </form>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            @php($data = $notification->data ?? [])
                            <a href="{{ route('notifications.read', $notification->id) }}"
                               class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold">
                                        {{ $data['title'] ?? 'Notifikasi' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ optional($notification->created_at)->format('d-m-Y H:i') ?? '-' }}
                                    </small>
                                </div>
                                <div class="text-muted small">
                                    Status: {{ strtoupper(str_replace('_',' ', $data['status'] ?? '-')) }}
                                </div>
                                @if(!empty($data['note']))
                                    <div class="text-muted small">
                                        Catatan: {{ $data['note'] }}
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                Belum ada notifikasi.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
