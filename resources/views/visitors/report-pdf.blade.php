<h3 style="text-align:center">LAPORAN PENGUNJUNG PERPUSTAKAAN</h3>
@if ($from || $to)
    <p style="text-align:center">
        Periode:
        {{ $from ? \Carbon\Carbon::parse($from)->format('d-m-Y') : '-' }}
        s/d
        {{ $to ? \Carbon\Carbon::parse($to)->format('d-m-Y') : '-' }}
    </p>
@endif

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>No</th>
            <th>Waktu</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Program Studi</th>
            <th>Keperluan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($visitors as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ optional($row->visit_at)->format('d-m-Y H:i') }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->nim ?? '-' }}</td>
            <td>{{ $row->department?->name ?? '-' }}</td>
            <td>{{ implode(', ', $row->purpose ?? []) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:40px;">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</p>
