@props([
    'title',
    'value' => 0,
])

<div class="card shadow-sm border-0 h-100">
    <div class="card-body text-center">
        <div class="text-muted small">
            {{ $title }}
        </div>
        <div class="fs-4 fw-bold">
            {{ $value }}
        </div>
    </div>
</div>
