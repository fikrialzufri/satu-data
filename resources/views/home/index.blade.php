@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-xl-8 col-md-8">
                <div class="col-xl-12 col-xl-12">
                    <div class="card bg-success">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-2 text-center">
                                    <img src="{{ asset('img/favicomahulu.png') }}" width="90%">
                                </div>
                                <div class="col d-flex align-middle">
                                    <div class="d-inline-block">
                                        <img src="{{ asset('img/logo-white.png') }}" width="43%" alt=""
                                            srcset="">
                                        <p>
                                            <b class="text-white">Sistem Informasi Statistik Sektoral Mahakam Ulu
                                                Terintegrasi
                                            </b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-12 col-xl-12 mb-10">
                    <div class="owl-container">
                        <div class="owl-carousel basic">

                            @foreach ($dataJenisUnit as $item)
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <span class="tx-13 mb-3">JENIS UNIT</span>
                                                <h5 class="card-text"> {{ $item->total }} {{ $item->nama }}</h5>
                                            </div>
                                            <div class="ml-auto mt-auto" width="110%">
                                                <button type="button" class="btn btn-icon "
                                                    style="background-color: {{ $item->warna }}"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12">
                    <div class="card sale-card">
                        <div class="card-header d-block">
                            <h3>Element Data Yang Telah Terkumpulkan</h3>
                            <p>Source : Dashbord Satu Data Kab. Mahakam Ulu</p>
                        </div>
                        <div class="card-block text-center">
                            <div id="line_chart" style="width:100%; height:1024px;"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                {{-- Pengunjung CKAN --}}
                <div class="card card-box">
                    <div class="card-header d-block">
                        <h6 class="mb-2">
                            <b>Pengunjung CKAN</b>
                        </h6>
                        <span>Ringkasan kunjungan CKAN berdasarkan periode</span>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $ckanPeriodLabels = [
                                'daily' => 'Per Hari',
                                'monthly' => 'Per Bulan',
                                'yearly' => 'Per Tahun',
                            ];
                        @endphp
                        @foreach ($ckanPeriodLabels as $key => $label)
                            @php
                                $totalCount = $ckanVisitorTotals[$key] ?? 0;
                                $latestDate = optional($ckanVisitors[$key] ?? null)->max('period_date');
                            @endphp
                            <div class="ckan-section mb-3 p-3 rounded border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-muted">{{ $label }}</h6>
                                    <h3 class="mb-0 text-success">
                                        {{ number_format($totalCount) }}
                                    </h3>
                                </div>
                                <small class="text-muted">
                                    Terakhir diperbarui:
                                    @if ($latestDate)
                                        {{ tanggal_indonesia($latestDate) }}
                                    @else
                                        -
                                    @endif
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
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
                                    @foreach ($dataUnit as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-middle">
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-1">{{ $item->nama }}</h6>
                                                        <p class="mb-0 tx-13 text-muted">{{ $item->total_element }} Element
                                                            Kategori </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="d-inline-block">
                                                    <h6 class="mb-2 tx-15 font-weight-semibold">
                                                        {{ format_uang($item->nilai_terakhir) }}</h6>
                                                    <p class="mb-0 tx-11 text-muted">
                                                        {{ tanggal_indonesia_waktu($item->updated_at) }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
    @if ($imageBanner != '')
        <div class="modal fade transparent-modal" id="transparentModal" tabindex="-1" role="dialog"
            aria-labelledby="transparentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content-iklan">
                    <div class="modal-header-iklan text-right">
                        <button type="button" class="btn btn-light rounded-pill text-right" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Replace the image source with your desired image -->
                        <img src=" {{ asset('storage/banner/' . $imageBanner) }}" alt="Modal Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif

@stop

@push('chart')
@endpush
@push('style')
    <style>
        .logox {
            height: 10px;
            top: 0;
        }

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

        .transparent-modal .modal-content-iklan {
            background: transparent !important;
            border: none;
        }

        .transparent-modal .modal-dialog {
            max-width: 80%;
            margin: 1.75rem auto;
        }

        .transparent-modal .modal-header-iklan {
            border: none;
        }


        .transparent-modal .close:hover {
            background-color: #ccc;
        }

        /* modal body iklan center img */
        .transparent-modal .modal-body-iklan {
            padding: 0;
        }

        .modal-body-iklan img {
            width: 10%;
        }
    </style>
    <link rel="stylesheet" href="http://radmin.test/plugins/owl.carousel/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="http://radmin.test/plugins/owl.carousel/dist/assets/owl.theme.default.min.css">
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
        let grafikGroup = @json($grafikGroup);

        console.log(grafikGroup);
        var chart = AmCharts.makeChart("line_chart", {
            "type": "serial",
            "dataProvider": grafikGroup,
            "categoryField": "country",
            "graphs": [{
                "valueField": "visits",
                "type": "line",
                "labelText": "[[value]]",
                "labelOffset": 4,
                "bullet": "round",
                "lineColor": "#28a745",
                "balloonText": "[[category]]: <b>[[value]]</b>"
            }],
            "categoryAxis": {
                // ... other category axis settings
                "labelRotation": 80,
                "autoGridCount": false,
                "gridCount": grafikGroup.length,
            },
        });

        $(document).ready(function() {
            $().owlCarousel && ($(".owl-carousel.basic").length > 0 && $(".owl-carousel.basic").owlCarousel({
                margin: 10,
                stagePadding: 15,
                loop: true,
                autoplay: true,
                dotsContainer: $(".owl-carousel.basic").parents(".owl-container").find(
                    ".slider-dot-container"),
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                }
            }).data("owl.carousel").onResize(), $(".owl-carousel.single").length > 0 && $(
                ".owl-carousel.single").owlCarousel({
                margin: 30,
                items: 1,
                loop: !0,
                stagePadding: 15,
                dotsContainer: $(".owl-carousel.single").parents(".owl-container").find(
                    ".slider-dot-container")
            }).data("owl.carousel").onResize(), $(".owl-dot").click(function() {
                $($(this).parents(".owl-container").find(".owl-carousel")).owlCarousel().trigger(
                    "to.owl.carousel", [$(this).index(), 300])
            }), $(".owl-prev").click(function(e) {
                e.preventDefault(), $($(this).parents(".owl-container").find(".owl-carousel"))
                    .owlCarousel().trigger("prev.owl.carousel", [300])
            }), $(".owl-next").click(function(e) {
                e.preventDefault(), $($(this).parents(".owl-container").find(".owl-carousel"))
                    .owlCarousel().trigger("next.owl.carousel", [300])
            }));
        });

        $(window).on('load', function() {
            $('#transparentModal').modal('show');
        });
    </script>
@endpush
