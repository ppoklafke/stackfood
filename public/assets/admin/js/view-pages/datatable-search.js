

"use strict";
$(document).on('ready', function () {
    let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
    $('#column1_search').on('keyup', function () {
        datatable
            .search(this.value)
            .draw();
    });

    $('#column2_search').on('keyup', function () {
        datatable
            .columns(2)
            .search(this.value)
            .draw();
    });

    $('#column3_search').on('change', function () {
        datatable
            .columns(3)
            .search(this.value)
            .draw();
    });

    $('#column4_search').on('keyup', function () {
        datatable
            .columns(4)
            .search(this.value)
            .draw();
    });
});
