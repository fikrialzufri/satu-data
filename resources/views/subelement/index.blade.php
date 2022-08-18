@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Daftar {{ ucwords(str_replace([':', '_', '*'], ' ', $title)) }}
                        </h3>
                        {{ $data->appends(request()->input())->links() }}
                        <div class="">
                            @canany(['import-sub-element'])
                                <a href="{{ route('sub-element.import') }}?element_id={{ $Element_id }}&tahun={{ $year }}"
                                    class="btn btn-sm btn-warning float-right text-light mr-5">
                                    <i class="fa fa-file"></i> Import
                                </a>
                            @endcan

                            @canany(['download-sub-element'])
                                <a href="{{ route('sub-element.download') }}?element_id={{ $Element_id }}&tahun={{ $year }}"
                                    class="btn btn-sm btn-danger float-right text-light mr-5">
                                    <i class="fa fa-file"></i> Download
                                </a>
                            @endcan

                            @canany(['create-sub-element'])
                                <a href="{{ route('sub_element.create') }}?element_id={{ $Element_id }}"
                                    class="btn btn-sm btn-primary float-right text-light">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                @foreach ($searches as $key => $item)
                                    @if ($item['input'] != 'hidden')
                                        <div class="col-lg-2">
                                            <label for="{{ $item['name'] }}">{{ ucfirst($item['alias']) }}</label>
                                            @include('template.formsearch')
                                        </div>
                                    @else
                                        @include('template.formsearch')
                                    @endif
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
                                    <td class="text-center" width="1%">
                                        Parent
                                    </td>
                                    @foreach ($configHeaders as $key => $header)
                                        @if (isset($header['alias']))
                                            <th class="text-center">{{ ucfirst($header['alias']) }}</th>
                                        @else
                                            <th class="text-center">{{ ucfirst($header['name']) }}</th>
                                        @endif
                                    @endforeach
                                    <td class="text-center" width="10%">
                                        {{ $subYear }}
                                    </td>
                                    @canany(['input-nilai-sub-element'])
                                        <td class="text-center" width="10%">
                                            {{ $year }}
                                        </td>
                                    @endcan
                                    @canany(['edit-legenda-sub-element'])
                                        <td class="text-center">
                                            Nilai & Legenda
                                        </td>
                                    @endcan
                                    <th class="text-center" width="5%">Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                    <tr id="element_{{ $item->id }}">
                                        <td class="text-center">
                                            {{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}
                                        </td>

                                        <td class="text-center">
                                            <input type="checkbox" class="js-danger parent" data-id="{{ $item->id }}"
                                                {{ $item->parent == 'Y' ? 'checked' : '' }}>
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
                                        <td>
                                            {{ format_uang($item->hasSubElementTahun($subYear)) }}
                                        </td>
                                        @canany(['input-nilai-sub-element'])
                                            <td>
                                                <div class="form-group ">

                                                    <input data-id="{{ $item->id }}" data-tahun="{{ $year }}"
                                                        type="text" class="form-control numberOnly nilai"
                                                        id="tahun_{{ $item->id }}"
                                                        value="{{ format_uang($item->hasSubElementTahun($year)) }}">
                                                </div>
                                            </td>
                                        @endcanany

                                        @canany(['edit-legenda-sub-element'])
                                            <td>
                                                <div class="form-group ">
                                                    <select name="legenda" class="selected2 form-control cmblegenda"
                                                        id="legenda_{{ $item->id }}">
                                                        @foreach ($listLegenda as $legenda)
                                                            <option value="{{ $legenda->id }}"
                                                                @if ($item->hasSubElementLegend($year)) {{ $item->hasSubElementLegend($year) == $legenda->id ? 'selected' : 'bebel' }}
                                                            @else
                                                            {{ $legenda->nama == 'Tetap' ? 'selected' : 'bebel' }} @endif>
                                                                {{ $legenda->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        @endcanany
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm detailSubElementmodal"
                                                data-toggle="tooltip" data-placement="top" title="Detail"
                                                data-nama="{{ $item->nama }}" data-keterangan="{{ $item->keterangan }}"
                                                data-satuan="{{ $item->satuan }}"
                                                data-metode="{{ $item->metode_perhitungan }}"
                                                data-meta="{{ $item->meta_data }}" data-sumber="{{ $item->sumber_data }}">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            @canany(['edit-sub-element', 'delete-sub-element'])
                                                @if (isset($button))
                                                    @foreach ($button as $key => $val)
                                                        @include('template.button')
                                                    @endforeach
                                                @endif
                                                @can('edit-sub-element')
                                                    <a href="{{ route($route . '.edit', $item->id) }}?unit_id={{ $item->unit_id }}"
                                                        class="btn btn-sm btn-warning text-light" data-toggle="tooltip"
                                                        data-placement="top" title="Edit">
                                                        <i class="nav-icon fas fa-edit"></i></a>
                                                @endcan
                                                @can('delete-sub-element')
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
                                            @endcan
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

        <div class="modal fade" id="detailSubElementmodal" tabindex="-1" role="dialog"
            aria-labelledby="SubElementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="SubElementModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        Satuan
                                    </td>
                                    <td>
                                        <span id="satuan"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Keterangan
                                    </td>
                                    <td>
                                        <span id="keterangan"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Sumber Data
                                    </td>
                                    <td>
                                        <span id="sumberData"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Metode Perhitungan
                                    </td>
                                    <td>
                                        <span id="metodePerhitungan"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Meta Data
                                    </td>
                                    <td>
                                        <span id="metaData"></span>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/css/datatables.css') }}">
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}"></script>
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

    <script>
        $('.cmblegenda').select2({
            width: '100%'
        });

        $(document).on('keypress', '.numberOnly', function(event) {
            if (event.which < 46 ||
                event.which > 59) {
                event.preventDefault();
            } // prevent if not number/dot

            if (event.which == 46 &&
                $(this).val().indexOf('.') != -1) {
                event.preventDefault();
            } // prevent if already dot
        })

        $(".nilai").on('keyup', function(e) {
            var id = $(this).attr("data-id");
            var tahun = $(this).attr("data-tahun");
            var value = $(this).val();
            var legenda = $("#legenda_" + id).val();
            let val = formatRupiah(value, '');
            $(this).val(val);

            // ajakan ajax
            $.ajax({
                url: "{{ route('elemen.update.nilai') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    tahun: tahun,
                    value: value,
                    legenda_id: legenda
                },
                success: function(response) {

                }
            });
        });


        $(document).on('change', '.parent', function(e) {
            var id = $(this).attr("data-id");
            var parent = $(this).is(":checked");

            console.log(parent);
            // ajakan ajax
            $.ajax({
                url: "{{ route('elemen.update.nilai') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    parent: parent,
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });


        $(".cmblegenda").on("select2:select", function(e) {

            var select_val = $(e.currentTarget).val();
            var parentId = $(this).closest('tr').attr('id');
            let id = parentId.replace(/element_/g, "");
            let tahun = $("#tahun_" + id).attr("data-tahun");
            let value = $("#tahun_" + id).val();
            let legenda = select_val;
            // ajakan ajax
            $.ajax({
                url: "{{ route('elemen.update.nilai') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    tahun: tahun,
                    value: value,
                    legenda_id: legenda
                },
                success: function(response) {

                }
            });
        });

        $('.detailSubElementmodal').on('click', function() {
            $('#detailSubElementmodal').modal('show');
            let nama = $(this).data('nama');
            let satuan = $(this).data('satuan');
            let keterangan = $(this).data('keterangan');
            let metodePerhitungan = $(this).data('metode');
            let metaData = $(this).data('meta');
            let sumberData = $(this).data('sumber');
            SubElementModalLabel.innerHTML = nama;
            $('#satuan').html(satuan);
            $('#keterangan').html(keterangan);
            $('#metodePerhitungan').html(metodePerhitungan);
            $('#metaData').html(metaData);
            $('#sumberData').html(sumberData);

        });
    </script>
@endpush
