@extends('template.app')
@section('title', $title)

@push('head')
    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.css"
        integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
        crossorigin="">
    <style type="text/css">
        #map {
            height: 45vh;
        }
    </style>

    {{-- <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" /> --}}
@endpush

@section('content')
    <div class="container-fluid">
        <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        @if ($store === 'update')
                            {{ method_field('PUT') }}
                        @endif
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="nama" class=" form-control-label">Nama Unit</label>
                                        </div>
                                        <div>
                                            <input type="text" name="nama" placeholder="Nama Unit"
                                                class="{{ $errors->has('nama') ? 'form-control is-invalid' : 'form-control' }}"
                                                @if ($store == 'update') value="{{ $data->nama }}" @else value="{{ old('nama') }}" @endif
                                                required id="nama">
                                        </div>
                                        @if ($errors->has('nama'))
                                            <span class="text-danger">
                                                <strong id="textnama">Nama Unit wajib diisi!</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="singkat" class=" form-control-label">Nama Singkat</label>
                                        </div>
                                        <div>
                                            <input type="text" name="singkat" placeholder="Nama Singkat"
                                                class="{{ $errors->has('Singkat') ? 'form-control is-invalid' : 'form-control' }}"
                                                @if ($store == 'update') value="{{ $data->nama_singkat }}" @else value="{{ old('singkat') }}" @endif
                                                required id="Singkat">
                                        </div>
                                        @if ($errors->has('singkat'))
                                            <span class="text-danger">
                                                <strong id="textsingkat">Nama Singkat wajib diisi!</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="email" class=" form-control-label">Email</label>
                                        </div>
                                        <div>
                                            <input type="email" name="email" placeholder="Email"
                                                class="{{ $errors->has('email') ? 'form-control is-invalid' : 'form-control' }}"
                                                @if ($store == 'update') value="{{ $data->email }}" @else value="{{ old('email') }}" @endif
                                                required id="email">
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">
                                                <strong id="textemail">{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="username" class=" form-control-label">Username Admin</label>
                                        </div>
                                        <div>
                                            <input type="text" name="username" placeholder="Username Admin"
                                                class="{{ $errors->has('username') ? 'form-control is-invalid' : 'form-control' }}"
                                                @if ($store == 'update') value="{{ $data->username }}" @else value="{{ old('username') }}" @endif
                                                required id="username">
                                        </div>
                                        @if ($errors->has('username'))
                                            <span class="text-danger">
                                                <strong id="textusername">{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="password" class=" form-control-label">Password Admin</label>
                                        </div>
                                        <div>
                                            <input type="password" name="password" placeholder="Password Admin"
                                                class="{{ $errors->has('password') ? 'form-control is-invalid' : 'form-control' }}"
                                                value="{{ old('password') }}" required id="password">
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">
                                                <strong id="textpassword">{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="password" class=" form-control-label">Password Konfirmasi</label>
                                        </div>
                                        <div>
                                            <input type="password" name="passwordConfrim"
                                                placeholder="passwordConfrim Admin"
                                                class="{{ $errors->has('passwordConfrim') ? 'form-control is-invalid' : 'form-control' }}"
                                                value="{{ old('passwordConfrim') }}" required id="passwordConfrim">
                                        </div>
                                        @if ($errors->has('passwordConfrim'))
                                            <span class="text-danger">
                                                <strong
                                                    id="textpasswordConfrim">{{ $errors->first('passwordConfrim') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="telepon" class=" form-control-label">Telepon</label>
                                        </div>
                                        <div>
                                            <input type="text" name="telepon" placeholder="Telepon"
                                                class="{{ $errors->has('telepon') ? 'form-control is-invalid' : 'form-control' }}"
                                                @if ($store == 'update') value="{{ $data->telepon }}" @else value="{{ old('telepon') }}" @endif
                                                required id="telepon">
                                        </div>
                                        @if ($errors->has('telepon'))
                                            Telepon
                                            <span class="text-danger">
                                                <strong id="textkk">Telepon wajib diisi!</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group ">
                                        <label for="jenis_unit">Jenis Unit </label>
                                        <select name="jenis_unit" class="selected2 form-control" id="cmbjenis_unit">
                                            <option value="">Pilih Jenis Unit</option>
                                            @foreach ($listJenisUnit as $unit)
                                                <option value="{{ $unit->id }}"
                                                    @if ($store == 'update') {{ $data->jenis_unit_id == $unit->id ? 'selected' : 'bebel' }}  @else {{ old('jenis_unit') == $unit->id ? 'selected' : 'bebel' }} @endif>
                                                    {{ $unit->nama }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('jenis_unit'))
                                            <span class=" text-danger">
                                                <strong id="textjenis_unit">Jenis Unit salah</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="logo" class=" form-control-label">Logo</label>
                                        </div>
                                        <div>
                                            <input type="file" value="{{ old('logo') }}" name="logo"
                                                placeholder="Logo" id="logo" class="form-control"
                                                @if ($store == 'update') value="{{ $data->logo }}" @else value="{{ old('logo') }}" @endif>

                                            <br>

                                            @if ($store == 'update')
                                                <img class="img-profile img-responsive" width="20%"
                                                    @if ($data->logo == null) src="{{ asset('img/logo.png') }}" @else
                        src="{{ asset('storage/' . $route . '/thumbnail/' . $data->logo) }}" @endif>
                                            @else
                                                <div class="preview">
                                                    <img class="img-profile img-responsive" width="100%"
                                                        src="{{ asset('img/logo.png') }}">
                                                </div>
                                            @endif
                                        </div>
                                        @if ($errors->has('logo'))
                                            <span class="text-danger">
                                                <strong id="textlogo">Logo wajib diisi!</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="keterangan" class=" form-control-label">Keterangan</label>
                                        </div>
                                        <div>
                                            <textarea class="{{ $errors->has('keterangan') ? 'form-control is-invalid' : 'form-control' }}" name="keterangan"
                                                id="keterangan" rows="10" placeholder="Keterangan">
@if ($store == 'update')
{{ $data->keterangan }}@else{{ old('keterangan') }}
@endif
</textarea>
                                        </div>
                                        @if ($errors->has('keterangan'))
                                            Keterangan
                                            <span class="text-danger">
                                                <strong id="textkk">Keterangan wajib diisi!</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="detail_alamat" class=" form-control-label">Detail Alamat</label>
                                        </div>
                                        <div>
                                            <textarea class="{{ $errors->has('detail_alamat') ? 'form-control is-invalid' : 'form-control' }}"
                                                name="detail_alamat" id="detail_alamat" rows="10" placeholder="Detail alamat">
@if ($store == 'update')
{{ $data->detail_alamat }}@else{{ old('detail_alamat') }}
@endif
</textarea>
                                        </div>
                                        @if ($errors->has('detail_alamat'))
                                            Detail Alamat
                                            <span class="text-danger">
                                                <strong id="textkk">Detail Alamat wajib diisi!</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="lat_long" class=" form-control-label">Koordinat (Latitude,
                                                Longitude)</label>
                                        </div>
                                        <div>
                                            <input type="text" name="lat_long" placeholder="Koordinat"
                                                class="{{ $errors->has('lat_long') ? 'form-control is-invalid' : 'form-control' }}"
                                                @if ($store == 'update') value="{{ $data->lat_long }}" @else value="{{ old('username') }}" @endif
                                                required id="lat_long" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="lat_long" class=" form-control-label">Cari Alamat</label>
                                        </div>
                                        <div id="search">
                                            <input type="text" name="addr" class="form-control" value=""
                                                id="addr" size="10" />
                                            <button type="button" class="btn btn-primary mb-3"
                                                onclick="addr_search();">Cari</button>
                                            <div id="results" />
                                        </div>
                                        <div id="map"></div>
                                    </div>
                                </div>
                            </div> --}}


                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-footer clearfix">
                        <div>
                            <h6 class="main-content-label mb-1">Simpan Data</h6>
                            <p class="text-muted card-sub-title">Pastikan kembali data yang anda inputkan telah benar
                                adanya
                                dan tidak terdapat kekeliruan.</p>
                        </div>
                        <div class="form-group">
                            <div class="border-checkbox-section">
                                <div class="border-checkbox-group border-checkbox-group-danger">
                                    <input
                                        class=" border-checkbox {{ $errors->has('setuju') ? 'form-control is-invalid' : 'form-control' }}"
                                        type="checkbox" id="checkbox5" name="setuju" value="Y" required>
                                    <label class="border-checkbox-label" for="checkbox5">Saya Setuju</label>
                                    @if ($errors->has('setuju'))
                                        <span class="text-danger">
                                            <strong id="textkk">Wajib dicentang</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@stop

@push('script')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js"
        integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g=="
        crossorigin=""></script>

    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.js"
        integrity="sha512-8bfbGLq2FUlH5HesCEDH9UiuUCnBq0A84yYv+LkUNPk/C2z81PsX2Q/U2Lg6l/QRuKiT3y2De2fy9ZPLqjMVxQ=="
        crossorigin=""></script>
    <script src="{{ asset('leaflet/jquery-1.8.2.min.js') }}"></script>

    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(".preview").html("<img src='" + e.target.result +
                        "' width='310' id='image_logo}'>");
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function() {
            readURL(this);
            $('.img-responsive').remove();
        });

        $('.close').on('click', function() {
            $('#image_logo}').remove();
        });
    </script>
    @if ($store == 'update')
        <script>
            var lat_long = document.getElementById('lat_long').value;
            lat_long = lat_long.split(",");
            var lokasi = "{{ $data->alamat }}";
        </script>
    @else
        <script>
            var lat_long = [-0.47529, 117.146515];
            var lokasi = "Kab. Mahakam Ulu";
        </script>
    @endif
    <script>
        var map;
        var feature;
        var marker;
        var newMarker = {};
        var latitude = lat_long[0];
        var longitude = lat_long[1];

        $('#cmbjenis_unit').select2({
            placeholder: '--- Pilih Unit ---',
            width: '100%'
        });

        // map = new L.Map('map', {
        //     zoomControl: true
        // });

        // var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        //     osmAttribution = 'Map data &copy; 2012 <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
        //     osm = new L.TileLayer(osmUrl, {
        //         maxZoom: 18,
        //         attribution: osmAttribution
        //     });


        // marker = L.marker(lat_long).addTo(map)
        //     .bindPopup('<b>' + lokasi + '</b>').openPopup();


        // map.setView(new L.LatLng(latitude, longitude), 20).addLayer(osm);

        // map.on('click', onMapClick);
        // var geocodeService = L.esri.Geocoding.geocodeService({
        //     apikey: "AAPK8176d782dece458a826c6ad408eeadf1rNg3Erse47Uah_Ij6q4nyG-WI3ryr5IBT8nb3hRNh2TfpyCkl0wVQjdk3nzJbBFo" // replace with your api key - https://developers.arcgis.com
        // });

        function chooseAddr(lat1, lng1, lat2, lng2, osm_type) {
            var loc1 = new L.LatLng(lat1, lng1);
            var loc2 = new L.LatLng(lat2, lng2);
            var bounds = new L.LatLngBounds(loc1, loc2);

            if (feature) {
                map.removeLayer(feature);
            }
            if (osm_type == "node") {
                feature = L.circle(loc1, 100, {
                    color: 'green',
                    fill: false
                }).addTo(map);
                map.fitBounds(bounds);
                map.setZoom(18);
            } else {
                var loc3 = new L.LatLng(lat1, lng2);
                var loc4 = new L.LatLng(lat2, lng1);

                feature = L.polyline([loc1, loc4, loc2, loc3, loc1], {
                    color: 'red'
                }).addTo(map);
                map.fitBounds(bounds);
            }
        }

        function onMapClick(e) {
            // Auto Fill form lat_long
            document.getElementById('lat_long').value = e.latlng.toString();

            map.removeLayer(marker);

            geocodeService.reverse().latlng(e.latlng).run(function(error, result) {
                if (newMarker != undefined) {
                    map.removeLayer(newMarker);
                }

                newMarker = L.marker(e.latlng).addTo(map)
                    .bindPopup("Anda memilih koordinat: " + e.latlng.toString() + " Dengan alamat: " + result
                        .address.LongLabel).openPopup();

                // Auto Fill form alamat
                document.getElementById('alamat').value = result.address.LongLabel.toString()
            });
        }



        function addr_search() {
            var inp = document.getElementById("addr");

            $.getJSON('https://nominatim.openstreetmap.org/search.php?street=' + inp
                .value + '&city=samarinda&format=jsonv2',
                function(data) {
                    var items = [];

                    $.each(data, function(key, val) {
                        bb = val.boundingbox;
                        items.push("<li><a href='#' onclick='chooseAddr(" + bb[0] + ", " + bb[2] + ", " + bb[
                                1] + ", " + bb[3] + ", \"" + val.osm_type + "\");return false;'>" + val
                            .display_name + '</a></li>');
                    });

                    $('#results').empty();
                    if (items.length != 0) {
                        $('<p>', {
                            html: "Hasil Pencarian:"
                        }).appendTo('#results');
                        $('<ul/>', {
                            'class': 'my-new-list',
                            html: items.join('')
                        }).appendTo('#results');
                    } else {
                        $('<p>', {
                            html: "No results found"
                        }).appendTo('#results');
                    }
                });
        }
    </script>
@endpush
