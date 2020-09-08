<footer class="app-footer">
    <a target="_blank" href="{{ URL::to('/') }}">Paywatch</a> &copy; {{ date('Y') }} All Rights reserved.
    <span class="float-right">Powered by <a href="http://bmpinfology.com/">BMP Infology</a>
        </span>
</footer>

<!-- Bootstrap and necessary plugins -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
{{--<script src="{{ asset('assets/js/bundle.min.js') }}"></script>--}}

<script src="{{ asset('assets/js/popper.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/pace.min.js') }}"></script>
<script src="{{ asset('assets/selectize/dist/js/standalone/selectize.js') }}"></script>
<script src="{{ asset('assets/js/jquery.repeater.min.js') }}"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.2.17/jquery.timepicker.min.js"></script>

<script src="{{ asset('assets/js/jquery.businessHours.js') }}"></script>
<script src="{{ asset('assets/js/stacktable.js') }}"></script>
<script src="{{ asset('assets/js/lightbox.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script src="{{ asset('assets/js/dragscroll.js') }}"></script>
<script src="{{ asset('assets/js/print.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- Plugins and scripts required by this views -->
<!-- Custom scripts required by this view -->
<script src="{{ asset('assets/js/main.js').'?v='.rand(8989,78799) }}"></script>
<!-- Nepali Date Picker-->
<script>
    const regex = /http:\/\/.*\//g;
    const match = regex.exec(window.location.href);

    // iterate over all and find match
    if (match) {
        $.each($('#sidebarmenu ul li'), function (index, item) {
            var elem = $(this);
            // console.log(elem.parent());
            var href = elem.children().attr('href');
            // console.log('i am input'+ match['input']);
            if (match['input'].indexOf(href) >= 0) {
                // console.log(this);
                var selected = $(this).find('a');
                var mainParent = selected.parent().parent().parent();
                $(mainParent).addClass('open');
                selected.addClass("active");
                return false;
            }
        });
    }
</script>
<!-- main scripts -->
<script>
    $('select').selectize({});
</script>

<script>
    $(document).on('change', '.positive-integer-number', function () {
        this.value = Math.abs(this.value);
    });
</script>
@yield('script')
<script>
    $('.upload-file').text('Upload Your File Here')
</script>
</body>

</html>
