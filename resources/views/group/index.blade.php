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

                            @canany(['create-' . str_replace('_', '-', $route)])
                                <a href="{{ route($route . '.create') }}" class="btn btn-sm btn-primary float-right text-light">
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
                        <table class="table table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="1%">No</th>
                                    <th rowspan="2" width="5%">Kode</th>
                                    <th rowspan="2">Nama Urusan</th>
                                    <th colspan="2" class="text-center">Jenis</th>
                                    <th colspan="2" class="text-center">Element</th>
                                    <th rowspan="2" class="text-center" width="15%">Created</th>
                                    @canany(['edit-' . $route, 'delete-' . $route, 'edit-element', 'delete-element'])
                                        <th class="text-center" rowspan="2" width="10%">Aksi</th>
                                    @endcan
                                </tr>
                                <tr>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                    <tr class="table-danger">
                                        <td class="text-center">
                                            {{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}
                                        </td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">{{ $item->total_element }}</td>
                                        <td class="text-center">{{ $item->total_sub_element }}</td>
                                        <td class="text-center">{{ tanggal_indonesia($item->created_at) }}</td>
                                        @canany(['edit-' . $route, 'delete-' . $route])
                                            <td class="text-center" class="text-center">
                                                @if (isset($button))
                                                    @foreach ($button as $key => $val)
                                                        @include('template.button')
                                                    @endforeach
                                                @endif
                                                @can('edit-' . $route)
                                                    <a href="{{ route($route . '.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning text-light" data-toggle="tooltip"
                                                        data-placement="top" title="Edit"><i class="nav-icon fas fa-edit"></i></a>
                                                @endcan
                                                @can('delete-' . $route)
                                                    <form id="form-{{ $item->id }}"
                                                        action="{{ route($route . '.destroy', $item->id) }}" method="POST"
                                                        style="display: none;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                    </form>
                                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                                                        title="Hapus" onclick=deleteconf("{{ $item->id }}")><i
                                                            class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        @endcan
                                    </tr>

                                    @if ($item->hasElement)
                                        @foreach ($item->hasElement as $key => $value)
                                            <tr class="table-success">
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                <td>{{ $value->kode_hasil }}</td>
                                                <td>{{ $value->nama }}</td>
                                                <td class="text-center">{{ $value->jenis_data }}</td>
                                                <td class="text-center">{{ $value->jenis_unit }}</td>
                                                <td colspan="2" class="text-center">{{ $value->total_sub_element }}
                                                    Element Data</td>
                                                <td class="text-center">{{ $value->unit }}</td>
                                                @canany(['edit-element', 'delete-element'])
                                                    <td class="text-center" class="text-center">
                                                        @can('edit-element')
                                                            <a href="{{ route($route . '.edit', $value->id) }}"
                                                                class="btn btn-sm btn-warning text-light" data-toggle="tooltip"
                                                                data-placement="top" title="Edit">
                                                                <i class="nav-icon fas fa-edit"></i></a>
                                                        @endcan
                                                        @can('delete-element')
                                                            <form id="form-{{ $value->id }}"
                                                                action="{{ route($route . '.destroy', $value->id) }}"
                                                                method="POST" style="display: none;">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                            </form>

                                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                                data-placement="top" title="Hapus"
                                                                onclick=deleteconf("{{ $value->id }}")>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endcan
                                                    </td>
                                                @endcan
                                            </tr>
                                            @if ($value->hasSubElement)
                                                @foreach ($value->hasSubElement as $no => $sub)
                                                    <tr class="table-info">
                                                        <td class="text-center">{{ $no + 1 }}</td>
                                                        <td>{{ $sub->kode_hasil }}</td>
                                                        <td>{{ $sub->nama }}</td>
                                                        <td class="text-center">{{ $value->jenis_data }}</td>
                                                        <td class="text-center">{{ $value->jenis_unit }}</td>
                                                        <td colspan="2" class="text-center">
                                                            {{ $sub->total }}
                                                            Element Data</td>
                                                        <td class="text-center">{{ $value->unit }}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
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
    </div>
@endsection

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/css/datatables.css') }}">
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}"></script>
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
    </script>
@endpush
