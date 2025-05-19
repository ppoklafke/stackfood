function newdonutChart(id, value, labels, legendPosition = "bottom") {
    var options = {
        series: value,
        labels: labels,
        colors: ["#93f0cf", "#99d5ff", "#ff7a00", "#167bc3"],
        chart: {
            width: "100%",
            height: 420,
            type: "donut",
        },
        responsive: [
            {
                breakpoint: undefined,
                options: {},
            },
        ],
        legend: {
            position: legendPosition,
        },
        tooltip: {
            enabled: false,
        },
        plotOptions: {
            pie: {
                startAngle: 0,
                endAngle: 360,
                expandOnClick: true,
                offsetX: 0,
                offsetY: 0,
                customScale: 1,
                dataLabels: {
                    offset: 0,
                    minAngleToShowLabel: 10,
                },
                donut: {
                    size: "65%",
                    background: "transparent",
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: "15px",
                            fontFamily: "Helvetica, Arial, sans-serif",
                            fontWeight: 500,
                            color: undefined,
                            offsetY: -10,
                            formatter: function (val) {
                                return val;
                            },
                        },
                        value: {
                            show: true,
                            fontSize: "16px",
                            fontFamily: "Helvetica, Arial, sans-serif",
                            fontWeight: 700,
                            color: undefined,
                            offsetY: 16,
                            formatter: function (val) {
                                return val;
                            },
                        },
                        total: {
                            show: true,
                            showAlways: false,
                            label: "Total",
                            fontSize: "15px",
                            fontFamily: "Helvetica, Arial, sans-serif",
                            fontWeight: 500,
                            color: "#373d3f",
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b;
                                }, 0);
                            },
                        },
                    },
                },
            },
        },
    };
    var chart = new ApexCharts(document.querySelector(id), options);
    chart.render();
}
