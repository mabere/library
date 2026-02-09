{{-- HEADER --}}
<table width="100%" style="border-bottom:2px solid #000; padding-bottom:10px;">
    <tr>
        <td width="15%" align="left">
            @if(file_exists(public_path('logo.png')))
                <img src="{{ public_path('logo.png') }}" width="80">
            @endif
        </td>

        <td width="70%" align="center">
            <div style="font-size:20px; font-weight:bold;margin-bottom:4px;">
                {{ config('institution.yayasan') }}
            </div>
            <div style="font-size:17px; font-weight:bold;margin-bottom:4px;">
                {{ config('institution.campus') }}
            </div>
            <div style="font-size:15px; font-weight:bold;margin-bottom:4px;">
                {{ config('institution.name') }}
            </div>
            <div style="font-size:12px;">
                {{ config('institution.address') }} <br>
                Telp: {{ config('institution.telp') }} | {{ config('institution.email') }}
            </div>
        </td>

        <td width="15%" align="right">
            @if(file_exists(public_path('tutwuri.png')))
                <img src="{{ public_path('tutwuri.png') }}" width="80">
            @endif
        </td>
    </tr>
</table>

{{-- JUDUL --}}
<h3 style="text-align:center; margin-top:25px; margin-bottom:6px;">
    <u>{{ strtoupper($title) }}</u>
</h3>
<div style="text-align:center; font-size:14px; margin-bottom:12px;">
    Nomor: {{ $number }}
</div>

{{-- WATERMARK --}}
<div style="
    position: fixed;
    top: 45%;
    left: 10%;
    width: 80%;
    text-align: center;
    font-size: 90px;
    color: rgba(0,0,0,0.07);
    transform: rotate(-25deg);
    z-index: -1;
    font-weight: bold;
">
    VALID
</div>

{{-- ISI --}}
<p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

<table width="100%" style="margin:15px 0;">
    <tr>
        <td width="30%">NIM</td>
        <td width="2%">:</td>
        <td>{{ $student->nim }}</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>:</td>
        <td>{{ $student->nama }}</td>
    </tr>
    <tr>
        <td>Program Studi</td>
        <td>:</td>
        <td>{{ $student->department?->name ?? '-' }}</td>
    </tr>

    @if($letter_type === 'penyerahan_skripsi')
        <tr>
            <td>Judul Skripsi</td>
            <td>:</td>
            <td>{{ $student->judul_skripsi }}</td>
        </tr>
    @endif
</table>

<p style="text-align: justify;">
    {{ $description }}
</p>

<p>
    Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.
</p>

{{-- TANDA TANGAN --}}
<table width="100%" style="margin-top:40px;">
    <tr>
        <td width="60%"></td>

        <td width="40%" style="text-align:center;">
            <p style="margin-bottom:5px;">
                Unaaha, {{ date('d F Y') }}
            </p>

            <p style="margin-top:0;">
                Petugas Perpustakaan
            </p>

            <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                 width="110"
                 style="margin:10px 0;">

            <p style="margin-top:10px; font-weight:bold;">
                {{ $verified_by }}
            </p>

            <p style="font-size:11px;">
                NIP. {{ $nip ?? '-' }}
            </p>

            <p style="font-size:10px; margin-top:6px;">
                Scan QR Code untuk verifikasi keaslian surat
            </p>
        </td>
    </tr>
</table>
