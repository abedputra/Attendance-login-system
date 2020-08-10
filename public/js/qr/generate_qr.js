$('input[type=radio][name=type]').change(function () {
    if (this.value == 0) {
        $('.with-user').hide();
        $('.without-user').show();
    } else if (this.value == 1) {
        $('.without-user').hide();
        $('.with-user').show();
    }
});