<h3 style="text-align:center">LAPORAN SURAT PERPUSTAKAAN</h3>
<p style="text-align:center">
    Bulan {{ $bulan }} Tahun {{ $tahun }}
</p>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nomor Surat</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Surat</th>
            <th>Petugas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($letters as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $row->created_at->format('d-m-Y') }}</td>
            <td>{{ $row->letter_number }}</td>
            <td>{{ $row->student->nama }}</td>
            <td>{{ $row->student?->department?->name ?? '-' }}</td>
            <td>{{ strtoupper(str_replace('_',' ',$row->letter_type)) }}</td>
            <td>{{ $row->verified_by }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:40px;">
    Dicetak pada: {{ date('d F Y') }}
</p>
