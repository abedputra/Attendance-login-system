$(function () {

    var getJsonData = baseUrl + 'main/getAttendanceToday';
    var cData;
    $.ajax({
        dataType: "json",
        url: getJsonData,
        success: function (dataJson) {
            cData = dataJson;
            var ctx = $("#bar-chart");

            var data = {
                labels: cData.name_chart,
                datasets: [
                    {
                        label: "Clock-In",
                        data: cData.late_time_chart,
                        "fill": false,
                        "borderColor": "#f3e26d",
                        "lineTension": 0.1,
                        "fontSize": 12
                    },
                    {
                        label: "Check-In User",
                        data: cData.check_in_chart,
                        "fill": true,
                        "borderColor": "rgb(75, 192, 192)",
                        "lineTension": 0.1,
                        "fontSize": 12
                    },
                ]
            };
            var options = {
                responsive: true,
                title: {
                    text: "Report Check-In Attendance Today",
                    display: true,
                    position: "top",
                    fontSize: 18,
                    fontColor: "#111"
                },
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        fontColor: "#333",
                        fontSize: 16
                    }
                }
            };

            new Chart(ctx, {
                type: "line",
                data: data,
                options: options
            });
        }
    });

});