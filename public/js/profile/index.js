$(document).ready(function () {
    $('#checkbox-pass').change(function () {
        if ($(this).is(":checked")) {
            $('#hide-pass').css('display', 'block');
            $('#check-change-pass').val('yes');
        } else {
            $('#hide-pass').css('display', 'none');
            $('#check-change-pass').val('no');
        }
    });
});