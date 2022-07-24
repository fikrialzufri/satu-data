<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title', '') | Satu Data</title>
    <!-- initiate head with meta tags, css and script -->
    @include('template.head')

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

                <div class="modal fade" id="jenisdatamodal" tabindex="-1" role="dialog"
                    aria-labelledby="jeniDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="jeniDataModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th class="text-center" width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                1
                                            </td>
                                            <td>
                                                <a href="#">
                                                    Admin
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- <!-- initiate chat section-->
            @include('template.chat') --}}


            <!-- initiate footer section-->
            @include('template.footer')

        </div>
    </div>

    <!-- initiate modal menu section-->
    @include('template.modalmenu')
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

        $('.jenisdatamodal').on('click', function() {
            $('#jenisdatamodal').modal('show');
            let title = $(this).data('nama');
            jeniDataModalLabel.innerHTML = title;
        });
    </script>
    <!-- initiate scripts-->
    @include('template.script')

</body>

</html>
