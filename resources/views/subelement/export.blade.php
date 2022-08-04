<style>
    table,
    td,
    th {
        border: 1px solid;
    }
</style>

<table width="100%">
    <thead>
        <tr>
            <th colspan="5">Daftar Element</th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr style="border: 1px solid #000000;">
            <th style="border: 1px solid #000000;" width="5">No</th>
            <th style="border: 1px solid #000000;" width="10">Kode</th>
            <th style="border: 1px solid #000000;" width="50">Nama</th>
            <th style="border: 1px solid #000000;" width="50">Nilai</th>
            <th style="border: 1px solid #000000;" width="50">Tahun</th>
            <th style="border: 1px solid #000000;" width="50">Satuan</th>
            <th style="border: 1px solid #000000;" width="50">Keterangan</th>
            <th style="border: 1px solid #000000;" width="50">Sumber Data</th>
            <th style="border: 1px solid #000000;" width="50">Metode Perhitungan</th>
            <th style="border: 1px solid #000000;" width="50">meta_data</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $index => $item)
            <tr style="border: 1px solid #000000;">
                <td style="border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $item->kode }}</td>
                <td style="border: 1px solid #000000;">{{ $item->nama }}</td>
                <td style="border: 1px solid #000000;">{{ $item->hasSubElementTahun($year) }}</td>
                <td style="border: 1px solid #000000;">{{ $year }}</td>
                <td style="border: 1px solid #000000;">{{ $item->satuan }}</td>
                <td style="border: 1px solid #000000;">{{ $item->keterangan }}</td>
                <td style="border: 1px solid #000000;">{{ $item->sumber_data }}</td>
                <td style="border: 1px solid #000000;">{{ $item->metode_perhitungan }}</td>
                <td style="border: 1px solid #000000;">{{ $item->meta_data }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">Data Element tidak ada</td>
            </tr>
        @endforelse
    </tbody>
</table>
