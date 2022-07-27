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
            <th style="border: 1px solid #000000;" width="10">Total Element</th>
            <th style="border: 1px solid #000000;" width="10">Kode</th>
            <th style="border: 1px solid #000000;" width="50">Nama</th>
            <th style="border: 1px solid #000000;" width="100">Group</th>
            <th style="border: 1px solid #000000;" width="100">Jenis Data</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $index => $item)
            <tr style="border: 1px solid #000000;">
                <td style="border: 1px solid #000000;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000;">
                    {{ $item->total_sub_element === '' ? 0 : $item->total_sub_element }}</td>
                <td style="border: 1px solid #000000;">{{ $item->kode_hasil }}</td>
                <td style="border: 1px solid #000000;">{{ $item->nama }}</td>
                <td style="border: 1px solid #000000;">{{ $item->group }}</td>
                <td style="border: 1px solid #000000;">{{ $item->jenis_data }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">Data Element tidak ada</td>
            </tr>
        @endforelse
    </tbody>
</table>
