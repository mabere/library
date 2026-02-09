@section('title', 'Activity Log')

<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-shield-check"></i>
            Activity Log
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Action</label>
                            <input type="text" name="action" value="{{ request('action') }}" class="form-control" placeholder="contoh: bebas_pustaka.status_changed">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User</label>
                            <select name="user_id" class="form-select">
                                <option value="">Semua</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected((string) $user->id === (string) request('user_id'))>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dari</label>
                            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sampai</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Subjek</th>
                                    <th>IP</th>
                                    <th>User Agent</th>
                                    <th>Meta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ optional($log->created_at)->format('d-m-Y H:i') ?? '-' }}</td>
                                        <td>{{ $log->user?->name ?? 'System' }}</td>
                                        <td><code>{{ $log->action }}</code></td>
                                        <td>
                                            @if($log->subject_type)
                                                <div class="small text-muted">{{ class_basename($log->subject_type) }}</div>
                                                <div>#{{ $log->subject_id }}</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $log->ip_address ?? '-' }}</td>
                                        <td class="small text-muted">{{ $log->user_agent ?? '-' }}</td>
                                        <td class="small">
                                            @if($log->meta)
                                                <pre class="mb-0">{{ json_encode($log->meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Belum ada activity log.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
