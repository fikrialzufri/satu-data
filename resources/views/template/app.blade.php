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
    <!-- initiate scripts-->
    @include('template.script')

</body>

</html>
