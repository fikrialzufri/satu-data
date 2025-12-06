<div class="form-group form-textinput" id="form_{{ $item['name'] }}">

    @if (isset($item['input']) && $item['input'] !== 'hidden')
        <div>
            <label for="{{ $item['name'] }}" class=" form-control-label">{{ $item['alias'] }}</label>
        </div>
    @endif
    @if (!isset($item['input']))
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @else
        @if ($item['input'] == 'hidden')
            <input type="hidden" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ isset($item['value']) ? $item['value'] : '' }}" @endif>
        @endif
        @if ($item['input'] == 'combo')
            <select class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }} selected2"
                @if (isset($item['multiple'])) name="{{ $item['name'] }}[]" multiple @else name="{{ $item['name'] }}" @endif
                id="cmb{{ $item['name'] }}">
                <option value="">--Pilih {{ $item['alias'] }}--</option>
                @if (isset($item['value']))

                    @foreach ($item['value'] as $key => $val)
                        @if (isset($val['id']))
                            <option value="{{ $val['id'] }}"
                                @if ($store == 'update') @if (gettype($data[$item['name']]) == 'array')
                                         {{ in_array($val['id'], $data[$item['name']]) ? 'selected' : '' }}
                                        @else
                                        @if (is_array($data[$item['name'] . 'id']))
                                        {{ in_array($val['id'], $data[$item['name'] . 'id']) ? 'selected' : '' }}
                                        @else
                                        {{ $data[$item['name']] == $val['id'] ? 'selected' : '' }} @endif
                                @endif
                            @else
                                {{ old($item['name']) == $val['id'] ? 'selected' : '' }}
                        @endif>
                        @if (isset($val['value']))
                            {{ ucfirst($val['value']) }}
                        @else
                            Array salah harus menggunakan value
                        @endif
                        </option>
                    @else
                        <option value="{{ $val }}"
                            @if ($store == 'update') @if (isset($item['array']))
                                    {{ in_array($val, $data[$item['name']]) ? 'selected' : '' }}
                                    @else
                                    {{ $data[$item['name']] == $val ? 'selected' : '' }} @endif
                        @else {{ old($item['name']) == $val ? 'selected' : '' }} @endif>
                            {{ ucfirst($val) }}
                        </option>
                    @endif
                    @if (isset($item['array']))
                        @if ($store == 'update')
                            <option value="{{ $val }}">
                                {{ $item['name'] }}
                                {{ implode(' ', $data[$item['name']]) == $val ? 'jhabuk' : 'kada' }}

                            </option>

                        @endif
                    @endif
                @endforeach
        @endif
        </select>
        {{-- @endif
            @endif --}}

    @endif
    @if ($item['input'] == 'rupiah')
        <div class="input-group mb-2 mr-sm-2">
            <div class="input-group-prepend">
                <div class="input-group-text">Rp.</div>
            </div>

            <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                placeholder="{{ $item['alias'] }}"
                class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                @if ($store == 'update') value="{{ format_uang($data[$item['name']]) }}" @else value="{{ old($item['name']) }}" @endif>
        </div>


    @endif

    @if ($item['input'] == 'warna')
        {{ old($item['name']) }}
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            id="text-field" class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }} warna"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) === old($item['name']) ? $item['default'] : old($item['name']) }}" @endif>


    @endif
    @if ($item['input'] == 'radio')
        <div class="form-radio">
            @foreach ($item['value'] as $key => $val)
                <div class="radio radiofill radio-inline">
                    <label>
                        <input type="radio" name="{{ $item['name'] }}" value="{{ $val }}"
                            @if ($store == 'update') {{ $data[$item['name']] == $val ? 'checked' : '' }} @else {{ old($item['name']) == $val ? 'checked' : '' }} {{ $item['default'] == $val ? 'checked' : '' }} @endif>
                        <i class="helper"></i>{{ ucfirst($val) }}
                    </label>
                </div>
            @endforeach
        </div>
    @endif
    @if ($item['input'] == 'persen')
        <div class="form-group row">
            <div class="col-sm-3">
                <div class="input-group ">
                    <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" class="form-control"
                        @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif
                        placeholder="Isi {{ $item['name'] }}">
                    <span class="input-group-append">
                        <label class="input-group-text">%</label>
                    </span>
                </div>
            </div>
        </div>
    @endif
    @if ($item['input'] == 'nominal')
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @endif
    @if ($item['input'] == 'datetimepicker')
        <input type="text" id="{{ $item['name'] }}" name="{{ $item['name'] }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}">
    @endif
    @if ($item['input'] == 'year')
        <input type="text" id="{{ $item['name'] }}" name="{{ $item['name'] }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}">
    @endif
    @if ($item['input'] == 'date')
        <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
            placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @endif
    @if ($item['input'] == 'image')
        <input type="file" value="{{ old($item['name']) }}" name="{{ $item['name'] }}"
            placeholder="{{ $item['alias'] }}" id="{{ $item['name'] }}" class="form-control"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>

        <br>
        <div class="preview"></div>
        @if ($store == 'update')
            <img class="img-profile img-responsive" width="20%"
                @if ($data[$item['name']] == null) src="{{ asset('img/default-icon.png') }}"
                        @else
                        src="{{ asset('storage/' . $route . '/thumbnail/' . $data[$item['name']]) }}" @endif>

        @endif
    @endif
    @if ($item['input'] == 'textarea')
        <textarea class="form-control" rows="3" placeholder="{{ $item['alias'] }}" name="{{ $item['name'] }}">
@if ($store == 'update'){{ $data[$item['name']] }}@else{{ old($item['name']) }}@endif
</textarea>
    @endif
    @if ($item['input'] == 'gallery-modal')
        @php
            $isMultiple = isset($item['multiple']) && $item['multiple'] === true;
            $selectedGalleries = [];
            $selectedIds = [];

            if ($store == 'update') {
                if ($isMultiple && isset($data[$item['name']]) && is_array($data[$item['name']])) {
                    $selectedIds = $data[$item['name']];
                } elseif (!$isMultiple && isset($data[$item['name']])) {
                    $selectedIds = [$data[$item['name']]];
                } elseif (!$isMultiple && isset($data[$item['name'] . '_id'])) {
                    $selectedIds = [$data[$item['name'] . '_id']];
                }
            } elseif (old($item['name'])) {
                $selectedIds = is_array(old($item['name'])) ? old($item['name']) : [old($item['name'])];
            }
        @endphp

        <input type="hidden" name="{{ $item['name'] }}{{ $isMultiple ? '[]' : '' }}" id="hidden_{{ $item['name'] }}"
            @if ($store == 'update' && !empty($selectedIds)) value="{{ $isMultiple ? implode(',', $selectedIds) : $selectedIds[0] ?? '' }}"
            @elseif (old($item['name']))
                value="{{ is_array(old($item['name'])) ? implode(',', old($item['name'])) : old($item['name']) }}" @endif>

        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
            data-target="#galleryModal{{ $item['name'] }}">
            <i class="ik ik-image"></i> Pilih {{ $item['alias'] }}
        </button>

        <div id="selectedGallery{{ $item['name'] }}" class="mt-2">
            @if (!empty($selectedIds))
                <div class="selected-gallery-preview">
                    <small class="text-muted">Terpilih: <span
                            id="count{{ $item['name'] }}">{{ count($selectedIds) }}</span> item</small>
                </div>
            @endif
        </div>

        <div class="modal fade" id="galleryModal{{ $item['name'] }}" tabindex="-1" role="dialog"
            aria-labelledby="galleryModalLabel{{ $item['name'] }}" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="galleryModalLabel{{ $item['name'] }}">Pilih
                            {{ $item['alias'] }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchGallery{{ $item['name'] }}"
                                placeholder="Cari gallery...">
                        </div>
                        <div class="form-group border-top pt-3">
                            <label class="form-control-label">Upload Gambar Baru</label>
                            <div class="d-flex gap-2">
                                <input type="file" class="form-control-file"
                                    id="uploadGallery{{ $item['name'] }}" accept="image/*" style="flex: 1;">
                                <button type="button" class="btn btn-success btn-sm"
                                    id="btnUploadGallery{{ $item['name'] }}">
                                    <i class="ik ik-upload"></i> Upload
                                </button>
                            </div>
                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF (Max: 5MB)</small>
                            <div id="uploadProgress{{ $item['name'] }}" class="mt-2" style="display: none;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="gallery-grid" id="galleryGrid{{ $item['name'] }}"
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; max-height: 500px; overflow-y: auto;">
                            <div class="text-center">Memuat gallery...</div>
                        </div>
                        <div id="galleryPagination{{ $item['name'] }}" class="mt-3 d-flex justify-content-center">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary"
                            id="confirmGallery{{ $item['name'] }}">Pilih</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($item['input'] == 'row-data')
        @php
            $rowDataValues = [];
            if ($store == 'update' && isset($data[$item['name']])) {
                $rowDataValues = $data[$item['name']];
            } elseif (old($item['name'])) {
                $rowDataValues = old($item['name']);
            } elseif (isset($item['value']) && is_array($item['value'])) {
                $rowDataValues = $item['value'];
            }

            if (is_string($rowDataValues)) {
                $decoded = json_decode($rowDataValues, true);
                $rowDataValues = is_array($decoded) ? $decoded : [];
            }

            if (!is_array($rowDataValues) || empty($rowDataValues)) {
                $rowDataValues = [['field' => '', 'value' => '']];
            }
        @endphp
        <div class="row-data-wrapper border rounded p-3" id="rowData{{ $item['name'] }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="font-weight-bold">{{ $item['alias'] ?? 'Row Data' }}</span>
                <button type="button" class="btn btn-primary btn-sm" id="addRowData{{ $item['name'] }}">
                    Tambah Field
                </button>
            </div>
            <div class="row-data-items">
                @foreach ($rowDataValues as $index => $row)
                    <div class="row row-data-item mb-2" data-index="{{ $index }}">
                        <div class="col-md-5">
                            <input type="text" class="form-control"
                                name="{{ $item['name'] }}[{{ $index }}][field]" placeholder="Nama field"
                                value="{{ $row['field'] ?? '' }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control"
                                name="{{ $item['name'] }}[{{ $index }}][value]" placeholder="Value"
                                value="{{ $row['value'] ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-center mt-2 mt-md-0">
                            <button type="button" class="btn btn-danger btn-sm remove-row-data">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (
        $item['input'] == 'text' ||
            $item['input'] == 'number' ||
            $item['input'] == 'email' ||
            $item['input'] == 'password' ||
            $item['input'] == 'time')
        <div>
            <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                @if (isset($item['required'])) {{ $item['required'] === true ? 'required' : '' }} @endif
                @if ($item['input'] == 'password') autocomplete="on" @else placeholder="{{ $item['alias'] }}" @endif
                class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
        </div>
    @endif
    @endif

    @if ($errors->has($item['name']))
        <span class="text-danger text-capitalize">
            <strong id="text{{ $item['name'] }}">
                @if (isset($item['alias']))
                    {{ $item['alias'] }} {{ str_replace('_id', '', $errors->first($item['name'])) }}
                @else
                    {{ str_replace('_id', '', $errors->first($item['name'])) }}
                @endif
            </strong>
        </span>
    @endif
</div>


@push('head')
    @if (isset($item['input']))
        @if ($item['input'] == 'datetimepicker' || $item['input'] == 'year')
            <link rel="stylesheet" type="text/css"
                href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        @endif
    @endif
    @if (isset($item['input']))
        @if ($item['input'] == 'warna')
            <link rel="stylesheet"
                href="{{ asset('plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
            <link rel="stylesheet" href="{{ asset('plugins/jquery-minicolors/jquery.minicolors.css') }}">
            <link rel="stylesheet" href="{{ asset('plugins/datedropper/datedropper.min.css') }}">
        @endif
    @endif
@endpush

@push('scriptdinamis')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>

    @if (isset($item['input']))
        @if ($item['input'] == 'warna')
            <script src="{{ asset('plugins/moment/moment.js') }}"></script>
            <script src="{{ asset('plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
            <script src="{{ asset('plugins/jquery-minicolors/jquery.minicolors.min.js') }}"></script>

            <script>
                $('.warna').each(function() {
                    //
                    // Dear reader, it's actually very easy to initialize MiniColors. For example:
                    //
                    //  $(selector).minicolors();
                    //
                    // The way I've done it below is just for the demo, so don't get confused
                    // by it. Also, data- attributes aren't supported at this time...they're
                    // only used for this demo.
                    //
                    $(this).minicolors({
                        control: $(this).attr('data-control') || 'hue',
                        defaultValue: $(this).attr('data-defaultValue') || '',
                        format: $(this).attr('data-format') || 'hex',
                        keywords: $(this).attr('data-keywords') || '',
                        inline: $(this).attr('data-inline') === 'true',
                        letterCase: $(this).attr('data-letterCase') || 'lowercase',
                        opacity: $(this).attr('data-opacity'),
                        position: $(this).attr('data-position') || 'bottom left',
                        swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
                        change: function(value, opacity) {
                            if (!value) return;
                            if (opacity) value += ', ' + opacity;
                        },
                        theme: 'bootstrap'
                    });

                });
            </script>
        @endif
    @endif
    <script type="text/javascript">
        $(function() {
            @if (isset($item['input']))
                @if ($item['input'] == 'combo')
                    $("#cmb{{ $item['name'] }}").select2({
                        placeholder: '--- Pilih ' + {!! json_encode($item['alias']) !!} + ' ---',
                        width: '100%'
                    });
                    $("#cmb{{ $item['name'] }}").on("change", function(e) {
                        $("#{{ $item['name'] }}").removeClass("is-invalid");
                        $("#text{{ $item['name'] }}").html("");
                    });
                @endif
            @endif
            @if (isset($item['input']))
                @if ($item['input'] == 'rupiah')
                    let rupiah = document.getElementsByClassName('rupiah');
                    $('#{{ $item['name'] }}').on("input", function() {

                        let val = formatRupiah(this.value, '');
                        $('#{{ $item['name'] }}').val(val);
                    });

                    /* Fungsi formatRupiah */
                    function formatRupiah(angka, prefix) {
                        var number_string = angka.replace(/[^,\d]/g, '').toString(),
                            split = number_string.split(','),
                            sisa = split[0].length % 3,
                            rupiah = split[0].substr(0, sisa),
                            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                        // tambahkan titik jika yang di input sudah menjadi angka ribuan
                        if (ribuan) {
                            separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }

                        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
                    }
                @endif
            @endif

            $("#{{ $item['name'] }}").keypress(function() {

                $("#{{ $item['name'] }}").removeClass("is-invalid");
                $("#text{{ $item['name'] }}").html("");
            });
            $("#{{ $item['name'] }}").change(function() {
                $("#{{ $item['name'] }}").removeClass("is-invalid");
                $("#text{{ $item['name'] }}").html("");
            });
            @if (isset($item['input']))
                @if ($item['input'] == 'radio')
                    if ($("input:radio[name=" + {!! json_encode($item['name']) !!} + "]").is(":checked")) {
                        $("#{{ $item['name'] }}").removeClass("is-invalid");
                        $("#text{{ $item['name'] }}").html("");
                    }
                @endif
                @if ($item['input'] == 'nominal')
                    $("#{{ $item['name'] }}").keypress(function(e) {
                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                            return false;
                        }
                    });
                @endif
            @endif
        });
    </script>
    @if (isset($item['input']))
        @if ($item['input'] == 'datetimepicker')
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            @if ($store == 'update')
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#{{ $item['name'] }}").daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerIncrement: 1,
                            timePickerSeconds: true,
                            locale: {
                                format: 'DD/M/Y HH:mm:ss'
                            }
                        })
                    })
                </script>
            @else
                <script type="text/javascript">
                    $(document).ready(function() {
                        let start = moment().startOf('month')
                        let end = moment().endOf('month')
                        $("#{{ $item['name'] }}").daterangepicker({
                            startDate: start,
                            endDate: end,
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerIncrement: 1,
                            timePickerSeconds: true,
                            minDate: start,
                            locale: {
                                format: 'DD/M/Y HH:mm:ss'
                            }
                        })
                    })
                </script>
            @endif
        @endif
        @if ($item['input'] == 'year')
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
                rel="stylesheet" />

            @if ($store == 'update')
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#{{ $item['name'] }}").datepicker({
                            format: "yyyy",
                            viewMode: "years",
                            minViewMode: "years"
                        })
                    })
                </script>
            @else
                <script type="text/javascript">
                    $(document).ready(function() {

                        $("#{{ $item['name'] }}").datepicker({
                            format: " yyyy", // Notice the Extra space at the beginning
                            viewMode: "years",
                            minViewMode: "years"

                        })
                    })
                </script>
            @endif
        @endif
        @if ($item['input'] == 'image')
            <script>
                (function() {
                    const fieldName = {!! json_encode($item['name']) !!};

                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();

                            reader.onload = function(e) {
                                $(".preview").html("<img src='" + e.target.result +
                                    "' width='310' id='image_" + fieldName + "'>");
                            }

                            reader.readAsDataURL(input.files[0]);
                        }
                    }

                    $("#" + fieldName).change(function() {
                        readURL(this);
                        $('.img-responsive').remove();
                    });

                    $('.close').on('click', function() {
                        $('#image_' + fieldName).remove();
                    });
                })();
            </script>
        @endif
        @if ($item['input'] == 'gallery-modal')
            <script>
                (function() {
                    const fieldName = {!! json_encode($item['name']) !!};
                    const isMultiple = {{ isset($item['multiple']) && $item['multiple'] === true ? 'true' : 'false' }};
                    const modalId = '#galleryModal' + fieldName;
                    const gridId = '#galleryGrid' + fieldName;
                    const searchId = '#searchGallery' + fieldName;
                    const hiddenId = '#hidden_' + fieldName;
                    const confirmId = '#confirmGallery' + fieldName;
                    const selectedPreviewId = '#selectedGallery' + fieldName;
                    const countId = '#count' + fieldName;
                    const paginationId = '#galleryPagination' + fieldName;
                    const uploadId = '#uploadGallery' + fieldName;
                    const btnUploadId = '#btnUploadGallery' + fieldName;
                    const uploadProgressId = '#uploadProgress' + fieldName;

                    let allGalleries = [];
                    let allLoadedGalleries = [];
                    let selectedGalleryIds = [];
                    let currentPage = 1;
                    let lastPage = 1;
                    let perPage = 12;
                    let searchTerm = '';
                    let isLoading = false;

                    @if ($store == 'update' && !empty($selectedIds))
                        selectedGalleryIds = {!! json_encode($selectedIds) !!}.map(function(id) {
                            return String(id).toLowerCase();
                        });
                    @elseif (old($item['name']))
                        selectedGalleryIds = (
                            {!! is_array(old($item['name'])) ? json_encode(old($item['name'])) : json_encode([old($item['name'])]) !!}
                        ).map(function(id) {
                            return String(id).toLowerCase();
                        });
                    @endif

                    function normalizeId(id) {
                        return String(id).toLowerCase().trim();
                    }

                    function loadGalleries(page = 1, search = '') {
                        if (isLoading) return;

                        isLoading = true;
                        $(gridId).html('<div class="text-center">Memuat gallery...</div>');

                        $.ajax({
                            url: '{{ route('galleries.api') }}',
                            method: 'GET',
                            data: {
                                page: page,
                                per_page: perPage,
                                search: search
                            },
                            success: function(response) {
                                if (response.success) {
                                    allGalleries = response.data;
                                    currentPage = response.pagination.current_page;
                                    lastPage = response.pagination.last_page;

                                    response.data.forEach(function(gallery) {
                                        const existingIndex = allLoadedGalleries.findIndex(g => String(g
                                            .id) === String(gallery.id));
                                        if (existingIndex === -1) {
                                            allLoadedGalleries.push(gallery);
                                        }
                                    });

                                    renderGalleries();
                                    renderPagination();
                                    updatePreview();
                                }
                                isLoading = false;
                            },
                            error: function() {
                                $(gridId).html('<div class="text-danger">Gagal memuat gallery</div>');
                                isLoading = false;
                            }
                        });
                    }

                    function renderPagination() {
                        if (lastPage <= 1) {
                            $(paginationId).html('');
                            return;
                        }

                        let paginationHtml = '<nav><ul class="pagination pagination-sm mb-0">';

                        // Previous button
                        if (currentPage > 1) {
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage -
                                1) + '">Sebelumnya</a></li>';
                        } else {
                            paginationHtml += '<li class="page-item disabled"><span class="page-link">Sebelumnya</span></li>';
                        }

                        // Page numbers
                        let startPage = Math.max(1, currentPage - 2);
                        let endPage = Math.min(lastPage, currentPage + 2);

                        if (startPage > 1) {
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
                            if (startPage > 2) {
                                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            if (i === currentPage) {
                                paginationHtml += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
                            } else {
                                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' +
                                    i + '</a></li>';
                            }
                        }

                        if (endPage < lastPage) {
                            if (endPage < lastPage - 1) {
                                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + lastPage +
                                '">' + lastPage + '</a></li>';
                        }

                        // Next button
                        if (currentPage < lastPage) {
                            paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage +
                                1) + '">Selanjutnya</a></li>';
                        } else {
                            paginationHtml += '<li class="page-item disabled"><span class="page-link">Selanjutnya</span></li>';
                        }

                        paginationHtml += '</ul></nav>';
                        $(paginationId).html(paginationHtml);

                        // Attach click handlers
                        $(paginationId + ' .page-link[data-page]').on('click', function(e) {
                            e.preventDefault();
                            const page = parseInt($(this).data('page'));
                            if (page !== currentPage) {
                                loadGalleries(page, searchTerm);
                            }
                        });
                    }

                    function escapeHtml(text) {
                        const map = {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        };
                        return text.replace(/[&<>"']/g, function(m) {
                            return map[m];
                        });
                    }

                    function renderGalleries() {
                        let html = '';
                        if (allGalleries.length === 0) {
                            html = '<div class="text-center text-muted">Tidak ada gallery ditemukan</div>';
                        } else {
                            allGalleries.forEach(function(gallery) {
                                const normalizedGalleryId = normalizeId(gallery.id);
                                const isSelected = selectedGalleryIds.includes(normalizedGalleryId);
                                const galleryId = escapeHtml(String(gallery.id));
                                const galleryGambar = escapeHtml(gallery.gambar || '');
                                const galleryNama = escapeHtml(gallery.nama || '');
                                html += '<div class="gallery-item border rounded p-2 text-center ' + (isSelected ?
                                        'bg-primary text-white' : '') + '" ' +
                                    'data-id="' + galleryId + '" ' +
                                    'style="cursor: pointer; ' + (isSelected ? 'border: 2px solid #007bff !important;' :
                                        '') + '">' +
                                    '<img src="' + galleryGambar + '" alt="' + galleryNama + '" ' +
                                    'class="img-thumbnail mb-2" ' +
                                    'style="width: 100%; height: 120px; object-fit: cover;">' +
                                    '<div class="small">' + galleryNama + '</div>' +
                                    (isSelected ? '<i class="ik ik-check-circle"></i>' : '') +
                                    '</div>';
                            });
                        }
                        $(gridId).html(html);

                        $(gridId + ' .gallery-item').on('click', function() {
                            const rawGalleryId = $(this).data('id');
                            const galleryId = normalizeId(rawGalleryId);

                            if (isMultiple) {
                                const index = selectedGalleryIds.indexOf(galleryId);
                                if (index > -1) {
                                    selectedGalleryIds.splice(index, 1);
                                } else {
                                    selectedGalleryIds.push(galleryId);
                                }
                            } else {
                                selectedGalleryIds = [galleryId];
                                renderGalleries();
                                updatePreview();

                                setTimeout(function() {
                                    $(confirmId).click();
                                }, 300);
                            }

                            if (isMultiple) {
                                renderGalleries();
                                updatePreview();
                            }
                        });
                    }

                    function updatePreview() {
                        if (selectedGalleryIds.length > 0) {
                            const selectedIds = selectedGalleryIds.map(function(normalizedId) {
                                const gallery = allLoadedGalleries.find(g => normalizeId(g.id) === normalizedId);
                                return gallery ? gallery.id : null;
                            }).filter(id => id !== null);

                            if (selectedIds.length === 0) {
                                loadSelectedGalleriesPreview();
                                return;
                            }

                            const selectedGalleries = allLoadedGalleries.filter(g =>
                                selectedIds.includes(String(g.id))
                            );

                            let previewHtml =
                                '<div class="selected-gallery-preview"><small class="text-muted">Terpilih: <span id="count' +
                                fieldName + '">' + selectedGalleryIds.length + '</span> item</small>';

                            if (selectedGalleries.length > 0) {
                                previewHtml += '<div class="mt-2" style="display: flex; flex-wrap: wrap; gap: 10px;">';
                                selectedGalleries.forEach(function(gallery) {
                                    const galleryGambar = escapeHtml(gallery.gambar || '');
                                    const galleryNama = escapeHtml(gallery.nama || '');
                                    previewHtml += '<div class="border rounded p-1" style="width: 80px;">' +
                                        '<img src="' + galleryGambar + '" alt="' + galleryNama + '" ' +
                                        'style="width: 100%; height: 60px; object-fit: cover;" class="rounded">' +
                                        '<div class="small text-truncate" style="font-size: 10px;">' + galleryNama +
                                        '</div>' +
                                        '</div>';
                                });
                                previewHtml += '</div>';
                            }

                            previewHtml += '</div>';
                            $(selectedPreviewId).html(previewHtml);
                        } else {
                            $(selectedPreviewId).html('');
                        }
                    }

                    function loadSelectedGalleriesPreview() {
                        if (selectedGalleryIds.length === 0) {
                            updatePreview();
                            return;
                        }

                        const selectedIds = selectedGalleryIds.map(function(normalizedId) {
                            const gallery = allLoadedGalleries.find(g => normalizeId(g.id) === normalizedId);
                            return gallery ? String(gallery.id) : normalizedId;
                        });

                        $.ajax({
                            url: '{{ route('galleries.api') }}',
                            method: 'GET',
                            data: {
                                per_page: 1000,
                                ids: selectedIds.join(',')
                            },
                            success: function(response) {
                                if (response.success) {
                                    response.data.forEach(function(gallery) {
                                        const existingIndex = allLoadedGalleries.findIndex(g => String(g
                                            .id) === String(gallery.id));
                                        if (existingIndex === -1) {
                                            allLoadedGalleries.push(gallery);
                                        }
                                    });

                                    updatePreview();
                                }
                            }
                        });
                    }

                    $(modalId).on('show.bs.modal', function() {
                        currentPage = 1;
                        searchTerm = '';
                        $(searchId).val('');
                        loadGalleries(1, '');
                    });

                    $(confirmId).on('click', function() {
                        if (selectedGalleryIds.length === 0) {
                            alert('Pilih minimal 1 gallery');
                            return;
                        }

                        const getOriginalId = function(normalizedId) {
                            const gallery = allLoadedGalleries.find(g => normalizeId(g.id) === normalizedId);
                            return gallery ? gallery.id : normalizedId;
                        };

                        if (isMultiple) {
                            const originalIds = selectedGalleryIds.map(getOriginalId);
                            $(hiddenId).val(originalIds.join(','));
                            $('input[name="' + fieldName + '[]"]').not(hiddenId).remove();
                            originalIds.forEach(function(id) {
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: fieldName + '[]',
                                    value: id
                                }).appendTo('form');
                            });
                        } else {
                            const selectedId = getOriginalId(selectedGalleryIds[0]);
                            $(hiddenId).val(selectedId);
                            $('input[name="' + fieldName + '"]').not(hiddenId).remove();
                            $('<input>').attr({
                                type: 'hidden',
                                name: fieldName,
                                value: selectedId
                            }).appendTo('form');
                        }

                        updatePreview();
                        $(modalId).modal('hide');
                    });

                    let searchTimeout;
                    $(searchId).on('keyup', function() {
                        clearTimeout(searchTimeout);
                        searchTerm = $(this).val();
                        currentPage = 1;

                        searchTimeout = setTimeout(function() {
                            loadGalleries(1, searchTerm);
                        }, 500);
                    });

                    function uploadGallery() {
                        const fileInput = $(uploadId)[0];
                        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                            alert('Pilih gambar terlebih dahulu');
                            return;
                        }

                        const file = fileInput.files[0];
                        const formData = new FormData();
                        formData.append('gambar', file);
                        formData.append('nama', file.name);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                        $(btnUploadId).prop('disabled', true).html('<i class="ik ik-loader"></i> Mengupload...');
                        $(uploadProgressId).show();
                        $(uploadProgressId + ' .progress-bar').css('width', '0%');

                        $.ajax({
                            url: '{{ route('galleries.upload') }}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            xhr: function() {
                                const xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener('progress', function(e) {
                                    if (e.lengthComputable) {
                                        const percentComplete = (e.loaded / e.total) * 100;
                                        $(uploadProgressId + ' .progress-bar').css('width',
                                            percentComplete + '%');
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(response) {
                                if (response.success) {
                                    const newGallery = response.data;
                                    const normalizedId = normalizeId(newGallery.id);

                                    allLoadedGalleries.push(newGallery);

                                    if (!isMultiple) {
                                        selectedGalleryIds = [normalizedId];
                                    } else {
                                        if (!selectedGalleryIds.includes(normalizedId)) {
                                            selectedGalleryIds.push(normalizedId);
                                        }
                                    }

                                    currentPage = 1;
                                    searchTerm = '';
                                    $(searchId).val('');
                                    loadGalleries(1, '');
                                    updatePreview();

                                    if (!isMultiple) {
                                        setTimeout(function() {
                                            $(confirmId).click();
                                        }, 500);
                                    } else {
                                        alert('Gambar berhasil diupload dan terpilih');
                                    }

                                    $(uploadId).val('');
                                } else {
                                    alert('Gagal mengupload gambar');
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Gagal mengupload gambar';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    const errors = Object.values(xhr.responseJSON.errors).flat();
                                    errorMessage = errors.join(', ');
                                }
                                alert(errorMessage);
                            },
                            complete: function() {
                                $(btnUploadId).prop('disabled', false).html('<i class="ik ik-upload"></i> Upload');
                                $(uploadProgressId).hide();
                                $(uploadProgressId + ' .progress-bar').css('width', '0%');
                            }
                        });
                    }

                    $(btnUploadId).on('click', function() {
                        uploadGallery();
                    });

                    $(uploadId).on('change', function() {
                        if (this.files && this.files[0]) {
                            $(btnUploadId).prop('disabled', false);
                        }
                    });

                    updatePreview();
                    loadSelectedGalleriesPreview();
                })();
            </script>
        @endif
        @if ($item['input'] == 'row-data')
            <script>
                (function() {
                    const container = $("#rowData{{ $item['name'] }}");
                    if (!container.length) {
                        return;
                    }

                    let index = container.find(".row-data-item").length;

                    $("#addRowData{{ $item['name'] }}").on("click", function() {
                        const fieldName = {!! json_encode($item['name']) !!};
                        const newRow = `
                            <div class="row row-data-item mb-2" data-index="${index}">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="${fieldName}[${index}][field]" placeholder="Nama field">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="${fieldName}[${index}][value]" placeholder="Value">
                                </div>
                                <div class="col-md-2 d-flex align-items-center mt-2 mt-md-0">
                                    <button type="button" class="btn btn-danger btn-sm remove-row-data">Hapus</button>
                                </div>
                            </div>`;

                        container.find(".row-data-items").append(newRow);
                        index++;
                    });

                    container.on("click", ".remove-row-data", function() {
                        $(this).closest(".row-data-item").remove();
                    });
                })();
            </script>
        @endif
    @endif
@endpush
