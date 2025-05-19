"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    $('#type').on('change', function() {
        if($('#type').val() == 'restaurant')
        {
            $('#restaurant').removeAttr("disabled");
            $('#deliveryman').val("").trigger( "change" );
            $('#deliveryman').attr("disabled","true");
        }
        else if($('#type').val() == 'deliveryman')
        {
            $('#deliveryman').removeAttr("disabled");
            $('#restaurant').val("").trigger( "change" );
            $('#restaurant').attr("disabled","true");
        }
    });
});

$('.account-data').on('change', function (){
    let route = $(this).data('url');
    let data_id = $(this).val();
    let type = $(this).data('type');
    getAccountData(route, data_id, type);
})
