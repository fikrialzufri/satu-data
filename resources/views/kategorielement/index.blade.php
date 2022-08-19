@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body bg-success ">
                        <div class="d-block">

                            <div class="">
                                <b>
                                    URL
                                </b>
                                <br>
                                <code id="urlapi">{{ $url }}</code>
                                <br>
                                <button class="btn btn-xs btn-dark" id="buttonCopy" data-toggle="tooltip" type="button"
                                    data-original-title="Copy to clipboard"><i class="fa fa-copy"></i>
                                    Copy API Url</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title d-block">
                            <h3>
                                TABLE DATA ELEMENT
                            </h3>
                            <p class="font-weight-light">Terdapat {{$totaldata}} Data Element.</p>
                        </div>
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <input type="hidden" name="element_id" value="{{ $element_id }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tahun" id="subtahun"
                                            value="{{ $subtahun }}" name="subtahun">
                                        <span class="input-group-append">
                                            <label class="input-group-text">
                                                s/d
                                            </label>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Tahun" id="tahun"
                                            value="{{ $tahun }}" name="tahun">
                                        <span class="input-group-append">
                                            <button class="btn btn-danger">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">No</th>
                                    <th width="5%" class="text-center">Kode</th>
                                    <th class="text-center">Element Data</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center" colspan="{{ count($listtahun) }}">Realisasi Pencapaian</th>
                                    <th class="text-center">Group</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center" width="5%">Chart</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="4">

                                        Kelompok Data : {{ $title }}
                                    </th>
                                    {{-- loop list tahun --}}
                                    @foreach ($listtahun as $item)
                                        <th class="text-center bg-success">
                                            {{ $item }}
                                        </th>
                                    @endforeach
                                </tr>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $index + 1 }}
                                        </td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ ucfirst($item->nama) }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        @foreach ($listtahun as $li)
                                            <td style="background-color: {{$item->hasLegendaWarna($li)}}" class="text-center">
                                                
                                                {{ format_uang($item->hasSubElementTahun($li)) }}
                                            </td>
                                        @endforeach
                                        <td>
                                            {{ $item->group }}
                                        </td>
                                        <td>
                                            {{ $item->jenis }}
                                        </td>
                                        <td>

                                            <button class="btn btn-success btn-sm text-center chartjenis"
                                                data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                                data-toggle="tooltip" data-placement="top" title="Chart"><i
                                                    class="ik ik-bar-chart-2"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data
                                            {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }} tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <br>
                        <div class="d-block">
                            <div class="mb-10">
                                <b>
                                    UNIT PENGENTRI : {{ $nama_unit }}
                                </b>
                                <br>
                            </div>
                            <div class="">
                                <b>
                                    Keterangan Element
                                </b>
                                <br>
                                <p>
                                    {{ $keterangan }}
                                </p>
                            </div>
                            <div class="">
                                <b>
                                    Dokumentasi Element
                                </b>
                                <br>
                                <p>
                                    {{ $dokumentasi }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @foreach ($listLegenda as $legenda)
                                <div class="col-4">
                                    <div class="card custom-card">
                                        <div class="card-body" style="border-left : 5px solid {{ $legenda->warna }}">
                                            <div class="d-flex align-items-center">
                                                <div class="d-blok">
                                                    <h5 class="card-text"> {{ $legenda->nama }}</h5>
                                                    <p>Keterangan : {{ $legenda->keterangan }}</p>
                                                </div>
                                                <div class="ml-auto mt-auto" width="110%">
                                                    <button type="button" class="btn btn-icon "
                                                        style="background-color: {{ $legenda->warna }}"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <p>
                            Legenda adalah keterangan tambahan yang menjelasakan unsur-unsur dalam grafik, yang di tunjukan
                            dalam warna font.
                        </p>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">

                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
            <div class="modal fade " id="chartmodal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable ">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h5 id="chartModalLabel"></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>

                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="d-block">
                                        <p id="titleElement">

                                        </p>
                                        <p class="font-weight-light">
                                            Dari {{ $subtahun }} sampai {{ $tahun }}
                                        </p>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h5>Realisasi Pencapaian</h5>
                                        <p>Source : {{ url('/') }}</p>
                                        <div id="bar_chart" class="chart-shadow"></div>

                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-block">
                                        <p>
                                            <b>Keterangan Element Nilai</b>
                                        </p>
                                        <p class="font-weight-light" id="nilaiTitleElement">

                                        </p>
                                    </div>
                                    <hr>
                                    <div>
                                        <table class="table table-striped">
                                            <tbody id="listTahun">

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@push('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
        rel="stylesheet" />

    <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.chartjenis').on('click', function() {
                let title = $(this).data('nama');

                chartModalLabel.innerHTML = "Kelompok Data : " + title;

                $('#titleElement').html("<b>" + title + "</b>");
                $('#nilaiTitleElement').html(title);
                let id = $(this).data('id');
                let subtahun = $('#subtahun').val();
                let tahun = $('#tahun').val();

                console.log(id);
                // ajax kelompokelement
                $.ajax({
                    url: "{{ route('kelompokelement') }}",
                    type: "GET",
                    data: {
                        id,
                        tahun,
                        subtahun
                    },
                    success: function(res) {
                        //ambil data nilai lempar ke listTahun
                        const listnilai = res.data.nilai;
                        let html = "";
                        listnilai.forEach((element, index) => {
                            html += "<tr>";
                            html +=
                                `<td>
                                <h6>
                                <b> Tahun ${element.tahun} : ${element.nilai} ${element.satuan}</b>
                                </h6>
                            </td>
                            <br>`;
                            html += "</tr>";
                        });
                        $("#listTahun").html(html);
                        // AmCharts.makeChart("line_chart"

                        $('#chartmodal').modal('show');
                        $('#chartmodal').on('shown.bs.modal', function(e) {

                            var chart = AmCharts.makeChart("bar_chart", {
                                "type": "serial",
                                "dataProvider": listnilai,
                                "categoryField": "country",
                                "graphs": [{
                                    "valueField": "visits",
                                    "type": "line",
                                    "labelText": "[[value]]",
                                    "labelOffset": 4,
                                    "bullet": "round",
                                    "lineColor": "#28a745",
                                    "balloonText": `[[category]]: <b>[[value]]</b>`
                                }],
                                "categoryAxis": {
                                    // ... other category axis settings
                                    "labelRotation": 80,
                                    "autoGridCount": false,
                                    "gridCount": listnilai.length,
                                },
                                "export": {
                                    "enabled": true
                                },
                                "legend": {
                                    "useGraphSettings": true,
                                    "labelText": title
                                },
                            });
                        });
                    }
                });


            });
        })

        // click buttoncopy copy id urlapi
        $('#buttonCopy').on('click', function() {
            var copyText = $('#urlapi').text();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();
        });

        $("#tahun").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        })
        $("#subtahun").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        })
    </script>
@endpush
