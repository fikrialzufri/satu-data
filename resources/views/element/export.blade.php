<style>
    table,
    td,
    th {
        border: 1px solid;
    }

    table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    tbody tr {
        margin-bottom: 5px;
    }

    tbody td {
        padding: 5px;
    }
</style>

@php
    $tahunCount = !empty($tahunList) ? count($tahunList) : 2;
    $totalCols = 6 + $tahunCount + 1;
@endphp
<table width="100%">
    <thead>
        <tr>
            <th colspan="{{ $totalCols }}">Unit : {{ htmlspecialchars($unit->nama ?? '', ENT_QUOTES, 'UTF-8') }} </th>
        </tr>
        <tr>
            <th colspan="{{ $totalCols }}"></th>
        </tr>
        <tr style="border: 1px solid #000000;">
            <th style="border: 1px solid #000000;" width="5">No</th>
            <th style="border: 1px solid #000000;" width="10">Total Element</th>
            <th style="border: 1px solid #000000;" width="10">Kode</th>
            <th style="border: 1px solid #000000;" width="50">Nama</th>
            <th style="border: 1px solid #000000;" width="100">Group</th>
            <th style="border: 1px solid #000000;" width="100">Jenis Data</th>
            @for ($i = 0; $i < $tahunCount; $i++)
                <th style="border: 1px solid #000000;" width="50"></th>
            @endfor
            <th style="border: 1px solid #000000;" width="50"></th>
        </tr>
        <tr style="border: 1px solid #000000;">
            <th style="border: 1px solid #000000;" width="5"></th>
            <th style="border: 1px solid #000000;" width="10"></th>
            <th style="border: 1px solid #000000;" width="10">Kode</th>
            <th style="border: 1px solid #000000;" width="50">Nama</th>
            <th style="border: 1px solid #000000;" width="50">Satuan</th>
            @if (!empty($tahunList))
                @foreach ($tahunList as $tahun)
                    <th style="border: 1px solid #000000;" width="50">{{ $tahun }}</th>
                @endforeach
            @else
                <th style="border: 1px solid #000000;" width="50">{{ $tahun1 ?? '2024' }}</th>
                <th style="border: 1px solid #000000;" width="50">{{ $tahun2 ?? '2025' }}</th>
            @endif
            <th style="border: 1px solid #000000;" width="100">Nilai &amp; Legenda</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $index => $item)
            <tr style="border: 1px solid #000000;">
                <td style="border: 1px solid #000000; padding: 8px;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; padding: 8px;">
                    {{ $item->total_sub_element === '' ? 0 : $item->total_sub_element }}</td>
                <td style="border: 1px solid #000000; padding: 8px;">
                    {{ htmlspecialchars($item->kode_hasil ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                <td style="border: 1px solid #000000; padding: 8px;">
                    {{ htmlspecialchars($item->nama ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                <td style="border: 1px solid #000000; padding: 8px;">
                    {{ htmlspecialchars($item->group ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                <td style="border: 1px solid #000000; padding: 8px;">
                    {{ htmlspecialchars($item->jenis_data ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                @for ($i = 0; $i < $tahunCount; $i++)
                    <td style="border: 1px solid #000000; padding: 8px;"></td>
                @endfor
                <td style="border: 1px solid #000000; padding: 8px;"></td>
            </tr>
            @if ($item->hasSubElement && $item->hasSubElement->count() > 0)
                @foreach ($item->hasSubElement as $subIndex => $subElement)
                    <tr style="border: 1px solid #000000;">
                        <td style="border: 1px solid #000000; padding: 8px;"></td>
                        <td style="border: 1px solid #000000; padding: 8px;"></td>
                        <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                            {{ htmlspecialchars($subElement->kode_hasil ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                        <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                            {{ htmlspecialchars($subElement->nama ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                        <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                            {{ htmlspecialchars($subElement->satuan ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                        @if (!empty($tahunList))
                            @foreach ($tahunList as $tahun)
                                <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                                    @php
                                        $nilai = $subElement->hasSubElementTahun($tahun) ?? 0;
                                        $nilai = is_numeric($nilai) ? $nilai : 0;
                                    @endphp
                                    {{ format_uang($nilai) }}
                                </td>
                            @endforeach
                        @else
                            <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                                @php
                                    $nilai1 = $subElement->hasSubElementTahun($tahun1 ?? 2024) ?? 0;
                                    $nilai1 = is_numeric($nilai1) ? $nilai1 : 0;
                                @endphp
                                {{ format_uang($nilai1) }}
                            </td>
                            <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                                @php
                                    $nilai2 = $subElement->hasSubElementTahun($tahun2 ?? 2025) ?? 0;
                                    $nilai2 = is_numeric($nilai2) ? $nilai2 : 0;
                                @endphp
                                {{ format_uang($nilai2) }}
                            </td>
                        @endif
                        <td style="border: 1px solid #000000; padding: 8px; padding-left: 30px;">
                            @php
                                $tahunTerakhir = !empty($tahunList) ? end($tahunList) : $tahun2 ?? 2025;
                            @endphp
                            {{ htmlspecialchars($subElement->hasLegenda($tahunTerakhir) ?? '', ENT_QUOTES, 'UTF-8') }}
                        </td>
                    </tr>
                @endforeach
            @endif
        @empty
            <tr>
                <td colspan="{{ $totalCols }}">Data Element tidak ada</td>
            </tr>
        @endforelse
    </tbody>
</table>
