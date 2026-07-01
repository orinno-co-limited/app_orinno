var options = {
    series: [{
        name: 'Total',
        data: CURRENTMONTHDAYTOTAL
    }],
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: CURRENTMONTHDAYS,
    },
    yaxis: {
        title: {
            text: moment(new Date).format("MMMM YYYY")
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return currencyPrice(val)
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart1"), options);
chart.render();

// chart 2
var options = {
    series: [{
        name: 'Total Subscription',
        data: MONTHLYSUBSCRIPTIONS
    }, {
        name: 'Total Users',
        data: MONTHLYUSERS
    }],
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: MONTHS,
    },
    yaxis: {
        title: {
            text: moment(new Date).format("YYYY")
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return currencyPrice(val)
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart2"), options);
chart.render();

