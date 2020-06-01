$(document).ready(function () {
    // Add timepicker for the start time
    $('#start_time').timepicker({
        timeFormat: 'H:mm:ss',
        minTime: '06',
        maxTime: '12:00:00pm',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        interval: 10
    });

    // Add timepicker for the out time
    $('#out_time').timepicker({
        timeFormat: 'H:mm:ss',
        minTime: '12',
        maxTime: '09:00:00pm',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        interval: 10
    });

});