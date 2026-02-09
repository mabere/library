@component('mail::message')
# {{ $title }}

Halo {{ $name }},

Status pengajuan Anda telah diperbarui.

@component('mail::panel')
@foreach($details as $line)
{{ $line }}  
@endforeach
Status: **{{ $status }}**
@endcomponent

@if(!empty($note))
Catatan: {{ $note }}
@endif

@component('mail::button', ['url' => $url])
Lihat Detail
@endcomponent

Terima kasih.  
{{ config('app.name') }}
@endcomponent
