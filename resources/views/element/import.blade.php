@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <a class="btn btn-success" href="{{ asset('/excel/Template Element.xlsx') }}">
                                    <i class="fas fa-download"></i> Download Template
                                    Element</a>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <label for="exampleInputFile">Pilih Group</label>
                                <select class="form-control select2" id="listGroup" name="group_id">
                                    <option selected="selected" value="">Pilih Group
                                    </option>
                                    @foreach ($listGroup as $index => $Group)
                                        <option value="{{ $Group->id }}" id="Group_{{ $Group }}">
                                            {{ $Group->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('group_id'))
                                    <span class="text-danger">Mohon pilih Group Id</span>
                                @endif
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <label for="exampleInputFile">Pilih Jenis Data</label>
                                <select class="form-control select2" id="listJenisData" name="jenis_data_id">
                                    <option selected="selected" value="">Pilih Jenis Data
                                    </option>
                                    @foreach ($listJenisData as $index => $JenisData)
                                        <option value="{{ $JenisData->id }}" id="JenisData_{{ $JenisData }}">
                                            {{ $JenisData->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('jenis_data_id'))
                                    <span class="text-danger">Mohon pilih Jenis Data</span>
                                @endif
                            </div>

                            @if ($unit_id === 'null')
                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <label for="exampleInputFile">Pilih Jenis Data</label>
                                    <select class="form-control select2" id="listUnit" name="unit_id">
                                        <option selected="selected" value="">Pilih Jenis Data
                                        </option>
                                        @foreach ($listUnit as $index => $unit)
                                            <option value="{{ $unit->id }}" id="unit_{{ $unit }}">
                                                {{ $unit->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('file'))
                                        <span class="text-danger">Mohon pilih Unit</span>
                                    @endif
                                </div>
                            @else
                                <input type="hidden" name="unit_id" value="{{ $unit_id }}">
                            @endif
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <label for="exampleInputFile">Silahkan Input File Element Di bawah</label>
                                <input type="file" name="file" class="file-upload-default">
                                <div class="input-group">
                                    <input type="text" class="form-control file-upload-info" disabled
                                        placeholder="Upload File Excel">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Pilih
                                            File</button>
                                    </span>
                                </div>
                                @if ($errors->has('file'))
                                    <span class="text-danger">Mohon upload dengan benar, file harus berekstensi .csv
                                        dengan format yang sesuai.</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@push('script')
    <script src="{{ asset('js/form-components.js') }}"></script>
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $('#listGroup').select2({
            width: '100%'
        });
        $('#listJenisData').select2({
            width: '100%'
        });
        $('#listUnit').select2({
            width: '100%'
        });
    </script>
@endpush
