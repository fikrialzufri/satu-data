<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title', '') | Satu Data</title>
    <!-- initiate head with meta tags, css and script -->
    @include('template.head')
    <style>
        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }
    </style>
</head>

<body id="app">
    <div class="wrapper">
        <!-- initiate header-->
        @include('template.header')
        <div class="page-wrap">
            <!-- initiate sidebar-->
            @include('template.menu')

            <div class="main-content">
                @include('template.breadcrumb')
                <!-- yeild contents here -->

                @yield('content')
                @auth

                    <div class="modal fade " id="jenisdatamodal" tabindex="-1" role="dialog"
                        aria-labelledby="jeniDataModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="jeniDataModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered " id="tableElement">

                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
                @guest
                    <div class="modal fade " id="jenisdatamenumodal" tabindex="-1" role="dialog"
                        aria-labelledby="jeniDataMenuModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 id="jeniDataMenuModalLabel"></h5>

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>

                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-8 col-lg-10">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <label class="input-group-text">
                                                        Pencarian
                                                    </label>
                                                </span>
                                                <input type="text" class="form-control" placeholder="Cari"
                                                    id="searchMenuElement">
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered " id="tableMenuElement">

                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                @endguest

            </div>
            {{-- <!-- initiate chat section-->
            @include('template.chat') --}}


            <!-- initiate footer section-->
            @include('template.footer')

        </div>
    </div>

    <!-- initiate modal menu section-->
    @include('template.modalmenu')
    @auth
        <script>
            $('.jenisdatamodal').on('click', function() {
                $('#jenisdatamodal').modal('show');
                let title = $(this).data('nama');
                jeniDataModalLabel.innerHTML = title;

                let id = $(this).data('id');
                let url = $(this).data('url');
                // ajax jenis_data.getDetail
                $.ajax({
                    url: "{{ route('jenisdata.detail') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        $("#tableElement tbody tr").remove();
                        let html = '';
                        data.forEach((element, index) => {
                            let no = parseInt(index) + 1;
                            html += '<tr>';
                            html += '<td colspan="2" class="text-center"><b>' + element.group +
                                ' (' +
                                element.total +
                                ') </b></td>';
                            // element tidak sama dengan null
                            html += '</tr>';
                            if (element.element != null) {
                                element.element.forEach((detail, index) => {
                                    let no = parseInt(index) + 1;
                                    html += '<tr>';

                                    html += '<td> <a href="' + url + detail.id + '">' +
                                        detail
                                        .nama + '<a/></td>';
                                    html += '<td>' + detail.total_sub_element + '</td>';
                                    html += '</tr>';
                                });
                            }
                        });
                        $("#tableElement tbody").append(html);

                    }
                });


            });
        </script>
    @endauth
    <script>
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
    </script>
    @guest
        <script>
            $('.jenisdatamenumodal').on('click', function() {
                $('#jenisdatamenumodal').modal('show');
                let title = $(this).data('nama');
                jeniDataMenuModalLabel.innerHTML = title;

                let id = $(this).data('id');
                let url = $(this).data('url');
                // ajax jenis_data.getDetail
                $.ajax({
                    url: "{{ route('jenisdata.detail') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $("#tableMenuElement tbody tr").remove();
                        let html = '';
                        data.forEach((element, index) => {
                            let no = parseInt(index) + 1;
                            html += '<tr>';
                            html += '<th colspan="2" class="text-center"><b>' + element.group +
                                '</b></th>';
                            // element tidak sama dengan null
                            html += '</tr>';
                            if (element.element != null) {
                                element.element.forEach((detail, index) => {
                                    let no = parseInt(index) + 1;
                                    html += '<tr>';

                                    html += '<td> <a href="' + url + detail.id + '">' +
                                        detail
                                        .nama + '<a/></td>';
                                    html += '<td>' + detail.total_sub_element + '</td>';
                                    html += '</tr>';
                                });
                            }
                        });
                        $("#tableMenuElement tbody").append(html);

                    }
                });
            });
            // cari hanya text didalam td di table mengunakan searchMenuElement
            $("#searchMenuElement").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableMenuElement tbody tr").filter(function() {
                    // hanya td saja yang di hapus atau di filter di toggle\
                    $(this).find('td').toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // jika modal di tutup maka searchMenuElement di kosongkan
            $('#jenisdatamenumodal').on('hidden.bs.modal', function() {
                $("#searchMenuElement").val('');
            });
        </script>
    @endguest
    <!-- initiate scripts-->
    @include('template.script')

</body>

</html>
