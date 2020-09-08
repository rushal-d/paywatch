$(function () {
    $.validate({
        validateOnBlur: false, // disable validation when input looses focus
        errorMessagePosition: 'inline', // Instead of 'inline' which is default
        scrollToTopOnError: true, // Set this property to true on longer forms
        // validateHiddenInputs : true,
    });


//search on enter

    $('.search-field').keypress(function (e) {
        // Enter pressed?
        if (e.which == 10 || e.which == 13) {
            this.form.submit();
        }
    });
});
