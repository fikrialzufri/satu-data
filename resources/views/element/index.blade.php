@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Daftar {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }}
                        </h3>
                        {{ $data->appends(request()->input())->links() }}
                        <div class="">

                            @canany(['import-' . $route])
                                <a href="{{ route($route . '.import') }}"
                                    class="btn btn-sm btn-warning float-right text-light mr-5">
                                    <i class="fa fa-file"></i> Import
                                </a>
                            @endcan

                            @if ($unit_id)
                                @canany(['download-' . $route])
                                    <button class="btn btn-sm btn-danger float-right text-light mr-5 btnDownloadElement">
                                        <i class="fa fa-file"></i> Download
                                    </button>
                                @endcan
                                @canany(['create-' . $route])
                                    <a href="{{ route($route . '.create') }}?unit_id={{ $unit_id }}"
                                        class="btn btn-sm btn-primary float-right text-light">
                                        <i class="fa fa-plus"></i> Tambah Data
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                @foreach ($searches as $key => $item)
                                    <div class="col-lg-2">

                                        <label for="{{ $item['name'] }}">{{ ucfirst($item['alias']) }}</label>
                                        @include('template.formsearch')
                                    </div>
                                @endforeach

                                <div class="col-lg-3">
                                    <label for="">Aksi</label>
                                    <div class="input-group">


                                        <button type="submit" class="btn btn-warning">
                                            <span class="fa fa-search"></span>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-bordered " id="example">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th class="text-center">Element</th>
                                    @foreach ($configHeaders as $key => $header)
                                        @if (isset($header['alias']))
                                            <th class="text-center">{{ ucfirst($header['alias']) }}</th>
                                        @else
                                            <th class="text-center">{{ ucfirst($header['name']) }}</th>
                                        @endif
                                    @endforeach

                                    @canany(['edit-' . $route, 'delete-' . $route])
                                        <th class="text-center">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                    <tr>
                                        <td class="text-center">
                                            {{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}</td>
                                        <td>
                                            <a @auth
href="{{ route('sub_element.index') }}?element_id={{ $item->id }}" @endauth
                                                @guest
href="{{ route('kategorielement') }}?element_id={{ $item->id }}" @endguest>

                                                <div style='background-color:#19b159; color:white; width:100%; '
                                                    class="badge badge-pill mb-1 d-flex justify-content-between">

                                                    <i class="fa fa-plus pr-2"></i>
                                                    <span>

                                                        {{ $item->total_sub_element }} Element Data
                                                    </span>
                                                    <span></span>
                                                </div>
                                            </a>
                                        </td>
                                        @foreach ($configHeaders as $key => $header)
                                            @if (isset($header['input']))
                                                @if ($header['input'] == 'rupiah')
                                                    <td class="text-center">Rp. {{ format_uang($item[$header['name']]) }}
                                                    </td>
                                                @elseif ($header['input'] == 'warna')
                                                    <td width="200">
                                                        <span
                                                            style='background-color:{{ $item[$header['name']] }}; color:white; width:100%; display:block;''
                                                            class="badge badge-pill mb-1">
                                                            {{ $item[$header['name']] }}</span>
                                                    </td class="text-center">
                                                @elseif ($header['input'] == 'date')
                                                    <td class="text-center">
                                                        @if ($item[$header['name']] != null || $item[$header['name']] != '')
                                                            {{ tanggal_indonesia($item[$header['name']]) }}
                                                        @endif
                                                    </td>
                                                @endif
                                            @elseif ($header['name'] === 'jenis_unit')
                                                <td class="text-center">

                                                    <div style='background-color:{{ $item->jenis_unit_warna }}; color:white; width:100%; '
                                                        class="badge badge-pill mb-1 d-flex justify-content-between">

                                                        <i class="fa fa-info"></i>
                                                        <span>

                                                            {{ $item[$header['name']] }}
                                                        </span>
                                                        <span></span>
                                                    </div>
                                                </td>
                                            @else
                                                <td class="text-center">{{ $item[$header['name']] }}</td>
                                            @endif
                                        @endforeach

                                        @canany(['edit-' . $route, 'delete-' . $route, 'create-' . $route])
                                            <td class="text-center">
                                                @if (isset($button))
                                                    @foreach ($button as $key => $val)
                                                        @include('template.button')
                                                    @endforeach
                                                @endif
                                                @can('edit-' . $route)
                                                    <a href="{{ route($route . '.edit', $item->id) }}?unit_id={{ $item->unit_id }}"
                                                        class="btn btn-sm btn-warning text-light" data-toggle="tooltip"
                                                        data-placement="top" title="Edit">
                                                        <i class="ik ik-edit-2"></i></a>
                                                @endcan
                                                @can('delete-' . $route)
                                                    <form id="form-{{ $item->id }}"
                                                        action="{{ route($route . '.destroy', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                    </form>

                                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                                                        title="Hapus" onclick=deleteconf("{{ $item->id }}")>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                                @can('create-' . $route)
                                                    @php
                                                        // get page
                                                        $page = request()->get('page');
                                                    @endphp
                                                    <a href="{{ route($route . '.ckan', $item->id) }}?page={{ $page }}"
                                                        class="btn btn-sm btn-success text-light" data-toggle="tooltip"
                                                        data-placement="top" title="Send CKAN">
                                                        <i class="ik ik-send"></i></a>
                                                @endcan
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data
                                            {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }} tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $data->appends(request()->input())->links('template.pagination') }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->

        <div class="modal fade " id="downloadElement" tabindex="-1" role="dialog"
            aria-labelledby="downloadElementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 id="downloadElementLabel">Download {{ ucwords(str_replace([':', '_', '*'], ' ', $title)) }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>

                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tahun1">Tahun Pertama</label>
                            <div class="input-group input-group-danger">
                                <input type="text" id="tahun1" class="form-control" placeholder="2024">
                            </div>
                            <b id="textDownloadElementError1" class="text-danger"></b>
                        </div>
                        <div class="form-group">
                            <label for="tahun2">Tahun Kedua</label>
                            <div class="input-group input-group-danger">
                                <input type="text" id="tahun2" class="form-control" placeholder="2025">
                            </div>
                            <b id="textDownloadElementError2" class="text-danger"></b>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                            title="Download" id="btnDownloadDataElement">Download <i class="fa fa-file"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <link href="{{ asset('dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
@endpush
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/css/datatables.css') }}">
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}"></script>

    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dist/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dist/js/bootstrap-datepicker.js') }}"></script>
    <script>
        // $('#example').DataTable({
        //   "paging": true,
        //   "lengthChange": true,
        //   "searching": true,
        //   "ordering": true,
        //   "info": true,
        //   "autoWidth": true,
        //   "pageLength": 20,
        // });

        $(document).ready(function() {
            // btnDownloadElement click
            $('.btnDownloadElement').on('click', function(e) {
                e.preventDefault();
                $('#downloadElement').modal('show');
            });

            // Initialize datepicker when modal is shown
            $('#downloadElement').on('shown.bs.modal', function() {
                // Destroy existing datepicker if any
                if ($("#tahun1").data('datepicker')) {
                    $("#tahun1").datepicker('destroy');
                }
                if ($("#tahun2").data('datepicker')) {
                    $("#tahun2").datepicker('destroy');
                }

                // Initialize datepicker
                $("#tahun1").datepicker({
                    format: " yyyy", // Notice the Extra space at the beginning
                    viewMode: "years",
                    minViewMode: "years"
                });
                $("#tahun2").datepicker({
                    format: " yyyy", // Notice the Extra space at the beginning
                    viewMode: "years",
                    minViewMode: "years"
                });
            });

            // clear error after tahun change
            $(document).on('change', '#tahun1', function(e) {
                $('#textDownloadElementError1').html('');
                $('#textDownloadElementError2').html('');
            });
            $(document).on('change', '#tahun2', function(e) {
                $('#textDownloadElementError1').html('');
                $('#textDownloadElementError2').html('');
            });

            // btnDownloadDataElement click
            $('#btnDownloadDataElement').on('click', function(e) {
                e.preventDefault();
                var tahun1 = $('#tahun1').val();
                var tahun2 = $('#tahun2').val();

                // jika tahun1 kosong
                if (tahun1 == '') {
                    $('#textDownloadElementError1').html('Tahun pertama tidak boleh kosong');
                    return false;
                }

                // jika tahun2 kosong
                if (tahun2 == '') {
                    $('#textDownloadElementError2').html('Tahun kedua tidak boleh kosong');
                    return false;
                }

                // redirect to route
                window.location.href =
                    "{{ route($route . '.download') }}?unit_id={{ $unit_id }}&tahun1=" +
                    tahun1 +
                    "&tahun2=" + tahun2;
            });
        })
    </script>
@endpush
