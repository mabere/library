@props([
    'status',
])

@php
    $map = [
        'diajukan'           => 'secondary',
        'diverifikasi_staf'  => 'warning',
        'ditolak_staf'       => 'danger',
        'disetujui_kepala'   => 'success',
    ];

    $color = $map[$status] ?? 'secondary';
@endphp

<span class="badge text-bg-{{ $color }}">
    {{ strtoupper(str_replace('_', ' ', $status)) }}
</span>
