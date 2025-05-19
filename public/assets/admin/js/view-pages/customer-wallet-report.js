"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF FLATPICKR
    // =======================================================
    $('.js-flatpickr').each(function () {
        $.HSCore.components.HSFlatpickr.init($(this));
    });


    // INITIALIZATION OF NAV SCROLLER
    // =======================================================
    $('.js-nav-scroller').each(function () {
        new HsNavScroller($(this)).init()
    });


    // INITIALIZATION OF DATERANGEPICKER
    // =======================================================
    $('.js-daterangepicker').daterangepicker();

    $('.js-daterangepicker-times').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'M/DD hh:mm A'
        }
    });

    let start = moment();
    let end = moment();

    function cb(start, end) {
        $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
    }

    $('#js-daterangepicker-predefined').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);


    // INITIALIZATION OF CHARTJS
    // =======================================================
    $('.js-chart').each(function () {
        $.HSCore.components.HSChartJS.init($(this));
    });

    let updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

    // Call when tab is clicked
    $('[data-toggle="chart"]').click(function (e) {
        let keyDataset = $(e.currentTarget).attr('data-datasets')

        // Update datasets for chart
        updatingChart.data.datasets.forEach(function (dataset, key) {
            dataset.data = updatingChartDatasets[keyDataset][key];
        });
        updatingChart.update();
    })


    // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
    // =======================================================
    function generateHoursData() {
        let data = [];
        let dt = moment().subtract(365, 'days').startOf('day');
        let end = moment().startOf('day');
        while (dt <= end) {
            data.push({
                x: dt.format('YYYY-MM-DD'),
                y: dt.format('e'),
                d: dt.format('YYYY-MM-DD'),
                v: Math.random() * 24
            });
            dt = dt.add(1, 'day');
        }
        return data;
    }

    $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
        data: {
            datasets: [{
                label: 'Commits',
                data: generateHoursData(),
                width: function (ctx) {
                    let a = ctx.chart.chartArea;
                    return (a.right - a.left) / 70;
                },
                height: function (ctx) {
                    let a = ctx.chart.chartArea;
                    return (a.bottom - a.top) / 10;
                }
            }]
        },
        options: {
            tooltips: {
                callbacks: {
                    title: function () {
                        return '';
                    },
                    label: function (item, data) {
                        let v = data.datasets[item.datasetIndex].data[item.index];

                        if (v.v.toFixed() > 0) {
                            return '<span class="font-weight-bold">' + v.v.toFixed() + ' hours</span> on ' + v.d;
                        } else {
                            return '<span class="font-weight-bold">No time</span> on ' + v.d;
                        }
                    }
                }
            },
            scales: {
                xAxes: [{
                    position: 'bottom',
                    type: 'time',
                    offset: true,
                    time: {
                        unit: 'week',
                        round: 'week',
                        displayFormats: {
                            week: 'MMM'
                        }
                    },
                    ticks: {
                        "labelOffset": 20,
                        "maxRotation": 0,
                        "minRotation": 0,
                        "fontSize": 12,
                        "fontColor": "rgba(22, 52, 90, 0.5)",
                        "maxTicksLimit": 12,
                    },
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    type: 'time',
                    offset: true,
                    time: {
                        unit: 'day',
                        parser: 'e',
                        displayFormats: {
                            day: 'ddd'
                        }
                    },
                    ticks: {
                        "fontSize": 12,
                        "fontColor": "rgba(22, 52, 90, 0.5)",
                        "maxTicksLimit": 2,
                    },
                    gridLines: {
                        display: false
                    }
                }]
            }
        }
    });


    // INITIALIZATION OF CLIPBOARD
    // =======================================================
    $('.js-clipboard').each(function () {
        let clipboard = $.HSCore.components.HSClipboard.init(this);
    });


    // INITIALIZATION OF CIRCLES
    // =======================================================
    $('.js-circle').each(function () {
        let circle = $.HSCore.components.HSCircles.init($(this));
    });
});

$('#from_date,#to_date').change(function () {
    let fr = $('#from_date').val();
    let to = $('#to_date').val();
    if (fr != '' && to != '') {
        if (fr > to) {
            $('#from_date').val('');
            $('#to_date').val('');
            toastr.error('Invalid date range!', Error, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    }

})

$('#reset_btn').click(function(){
    $('#customer').val(null).trigger('change');
})
