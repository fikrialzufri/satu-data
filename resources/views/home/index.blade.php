@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-xl-8 col-md-8">
                <div class="col-xl-12 col-xl-12">
                    <div class="card custom-card card-box mb-3">
                        <div class="card-body p-4">
                            <div class="row align-items-center">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-12 col-xl-12">
                    <div class="card sale-card">
                        <div class="card-header">
                            <h3>Grafik Tahun ini</h3>
                        </div>
                        <div class="card-block text-center">
                            <div id="line_chart" class="chart-shadow"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card card-box">
                    <div class="card-header d-block">
                        <h6>
                            <b>
                                RECENT TRANSCATIONS
                            </b>
                        </h6>
                        <span>Projects where development work is on completion</span>


                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <div class="d-flex align-middle">
                                                <div class="d-inline-block">
                                                    <h6 class="mb-1">Badan Kesatuan Bangsa dan Politik</h6>
                                                    <p class="mb-0 tx-13 text-muted">6 Element Kategori </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="d-inline-block">
                                                <h6 class="mb-2 tx-15 font-weight-semibold">25<i
                                                        class="fa fa-level-up-alt ml-2 text-success m-l-10"></i></h6>
                                                <p class="mb-0 tx-11 text-muted">12 Jan 2020</p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
@stop

@push('chart')
@endpush
@push('style')
    <style>
        @media (max-width: 500px) {
            #perda {
                height: 52px;
            }
        }

        .modal {
            text-align: center;
        }

        @media screen and (min-width: 768px) {
            .modal:before {
                display: inline-block;
                vertical-align: middle;
                content: " ";
                position: absolute;
                height: 100%;

            }
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
            top: 50%;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

    <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>


    <script src="{{ asset('js/widget-statistic.js') }}"></script>
    <script src="{{ asset('js/widget-data.js') }}"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    <script>
        let grafikperbulan = @json($aduanPerbulanGrafik);

        var chart = AmCharts.makeChart("line_chart", {
            "type": "serial",
            "theme": "light",
            "dataDateFormat": "YYYY-MM-DD",
            "precision": 0,
            "valueAxes": [{
                "id": "v1",
                "position": "left",
                "autoGridCount": false,
                "labelFunction": function(value) {
                    return "$" + Math.round(value) + "M";
                }
            }, {
                "id": "v2",
                "gridAlpha": 0,
                "autoGridCount": false
            }],
            "graphs": [{
                "id": "g1",
                "valueAxis": "v2",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "bulletSize": 8,
                "hideBulletsCount": 50,
                "lineThickness": 3,
                "lineColor": "#2ed8b6",
                "title": "Data Dasar",
                "useLineColorForBulletBorder": true,
                "valueField": "datadasar",
                "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            }, {
                "id": "g2",
                "valueAxis": "v2",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "bulletSize": 8,
                "hideBulletsCount": 50,
                "lineThickness": 3,
                "lineColor": "#e95753",
                "title": "Pegawai",
                "useLineColorForBulletBorder": true,
                "valueField": "pegawai",
                "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            }],
            "chartCursor": {
                "pan": true,
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha": 0,
                "valueLineAlpha": 0.2
            },
            "categoryField": "date",
            "categoryAxis": {
                "parseDates": true,
                "dashLength": 1,
                "minorGridEnabled": true
            },
            "legend": {
                "useGraphSettings": true,
                "position": "top"
            },
            "balloon": {
                "borderThickness": 1,
                "shadowAlpha": 0
            },
            "dataProvider": grafikperbulan
        });
    </script>
@endpush
