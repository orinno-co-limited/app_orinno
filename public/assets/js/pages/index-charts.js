$(function () {
    "use strict";
    var options = {
        series: [{
            name: "Total",
            data: INVOICEMONTLYAMOUNT
        }],
        chart: {
            foreColor: '#9a9797',
            type: "bar",
            height: 270,
            toolbar: {
                show: !1
            },
            zoom: {
                enabled: !1
            },
            dropShadow: {
                enabled: 0,
                top: 3,
                left: 14,
                blur: 4,
                opacity: .12,
                color: "#3461ff"
            },
            sparkline: {
                enabled: !1
            }
        },
        markers: {
            size: 0,
            colors: ["#3461ff", "#12bf24"],
            strokeColors: "#fff",
            strokeWidth: 2,
            hover: {
                size: 7
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: "40%",
                endingShape: "rounded"
            }
        },
        legend: {
            show: false,
            position: 'top',
            horizontalAlign: 'left',
            offsetX: -20
        },
        dataLabels: {
            enabled: !1
        },
        grid: {
            show: false,
            borderColor: '#eee',
            strokeDashArray: 4,
        },
        stroke: {
            show: !0,
            curve: "smooth"
        },
        colors: ["#3686FC"],
        xaxis: {
            categories: MONTHS
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function (val) {
                    return currencyPrice(val)
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();
    // Chart 1 End
});
