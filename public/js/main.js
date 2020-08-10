$(document).ready(function () {
    $(document).on('click', '.delete-button', function () {
        return confirm('Are you sure want to delete this?')
    });

    var url = window.location;
    $('ul.nav a[href="' + url + '"]').parent().addClass('active');
    $('ul.nav a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');
});