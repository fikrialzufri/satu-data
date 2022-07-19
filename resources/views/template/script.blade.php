<script src="{{ asset('js/swall.js') }}"></script>
<script src="{{ asset('all.js') }}"></script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
<!-- Stack array for including inline js or scripts -->
<script>
    $("#success-alert").fadeTo(15000, 500).slideUp(15000, function() {
        $("#success-alert").slideUp(15000);
    });
    $(".alert").fadeTo(15000, 500).slideUp(500, function() {
        $(".alert").slideUp(15000);
    });

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
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
        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
    }
</script>


<script src="{{ asset('dist/js/theme.js') }}"></script>
{{-- <script src="{{ asset('js/chat.js') }}"></script> --}}
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

{{-- <script src="{{ asset('firebase-messaging-sw.js') }}"></script> --}}

<script>
    // if ('serviceWorker' in navigator) {
    //     window.addEventListener('load', () => {
    //         navigator.serviceWorker.register('{{ asset('firebase-messaging-sw.js') }}');
    //     });
    // }
</script>

{{-- Notifikasi --}}
<script>
    $(function() {


    });
</script>
<script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>

@stack('script')

@stack('form')
@stack('scriptdinamis')
