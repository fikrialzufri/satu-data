@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body bg-success">
                        <div class="d-block">
                            <div class="">
                                <b>
                                    URL
                                </b>
                                <br>
                                <code id="urlapi">{{ route('infografik.api') }}</code>
                                <br>
                                <button class="btn btn-xs btn-dark" id="buttonCopy" data-toggle="tooltip" type="button"
                                    data-original-title="Copy to clipboard"><i class="fa fa-copy"></i>
                                    Copy API Url</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <p class="text-muted mb-0">Temukan informasi dan data dalam bentuk grafik yang mudah dipahami.</p>
                    </div>
                    @can('create-infografik')
                        <a href="{{ route('infografik.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Infografik
                        </a>
                    @endcan
                </div>

                <form method="GET" action="{{ route('infografik.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0 d-flex flex-column">
                            <label for="judul" class="form-label mb-1" style="height: 20px;">Cari infografik</label>
                            <div class="input-group flex-grow-1">
                                <span class="input-group-text">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    value="{{ $search }}" placeholder="Cari infografik..." style="height: 38px;">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0 d-flex flex-column">
                            <label for="kategori_infografik_id" class="form-label mb-1" style="height: 20px;">Pilih
                                Topik</label>
                            <select class="form-control" id="kategori_infografik_id" name="kategori_infografik_id"
                                style="height: 38px;">
                                <option value="">Semua Topik</option>
                                @foreach ($kategoriOptions as $option)
                                    <option value="{{ $option['id'] }}"
                                        {{ $kategoriId == $option['id'] ? 'selected' : '' }}>
                                        {{ $option['value'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0 d-flex flex-column">
                            <label for="sort" class="form-label mb-1" style="height: 20px;">Urutkan</label>
                            <select class="form-control" id="sort" name="sort" style="height: 38px;">
                                <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ $sort == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="a-z" {{ $sort == 'a-z' ? 'selected' : '' }}>A-Z</option>
                                <option value="z-a" {{ $sort == 'z-a' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex flex-column">
                            <label class="form-label mb-1" style="height: 20px; visibility: hidden;">Filter</label>
                            <button type="submit" class="btn btn-primary" style="height: 38px;">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mb-3">
                    <p class="text-muted mb-0">
                        <strong>{{ number_format($total, 0, ',', '.') }}</strong> Infografik ditemukan
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse ($data as $infografik)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm position-relative" style="transition: transform 0.2s, box-shadow 0.2s;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                        @canany(['edit-infografik', 'delete-infografik'])
                            <div class="position-absolute top-0 end-0 p-2"
                                style="z-index: 10; background: rgba(255, 255, 255, 0.9); border-radius: 0.25rem;">
                                <div class="btn-group" role="group">
                                    @can('edit-infografik')
                                        <a href="{{ route('infografik.edit', $infografik->id) }}"
                                            class="btn btn-sm btn-warning text-white" data-toggle="tooltip" data-placement="top"
                                            title="Edit" onclick="event.stopPropagation();"
                                            style="box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete-infografik')
                                        <form id="form-{{ $infografik->id }}"
                                            action="{{ route('infografik.destroy', $infografik->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                            data-placement="top" title="Hapus"
                                            onclick="event.stopPropagation(); deleteconf('{{ $infografik->id }}');"
                                            style="box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        @endcanany
                        <a href="{{ route('infografik.detail', $infografik->slug) }}" class="text-decoration-none">
                            @if ($infografik->thumbnail)
                                <img src="{{ asset('storage/gallery/' . $infografik->thumbnail) }}" class="card-img-top"
                                    alt="{{ $infografik->judul }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark mb-2" style="font-size: 1rem; line-height: 1.4;">
                                    {{ Str::limit($infografik->judul, 60) }}
                                </h5>
                                @if ($infografik->isi_infografik)
                                    <p class="card-text text-muted small flex-grow-1" style="font-size: 0.875rem;">
                                        {{ Str::limit(strip_tags($infografik->isi_infografik), 80) }}
                                    </p>
                                @endif
                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if ($infografik->kategoriInfografik)
                                                <span class="badge badge-primary">
                                                    {{ $infografik->kategoriInfografik }}
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            <i class="fa fa-eye"></i> {{ number_format(rand(1000, 10000), 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fa fa-info-circle"></i> Tidak ada infografik ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        @if ($data->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection


@push('script')
    <script>
        $('#buttonCopy').on('click', function() {
            var copyText = $('#urlapi').text();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();
        });
    </script>
@endpush
