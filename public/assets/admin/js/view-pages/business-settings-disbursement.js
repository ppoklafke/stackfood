"use strict";
$(document).on('ready', function () {
    $('input[name="disbursement_type"]').on('change', function(){
        if(this.value === 'manual'){
            $('.automated_disbursement_section').hide();
        }else{
            $('.automated_disbursement_section').show();
            $('.automated_disbursement_section').removeClass('d-none');
        }
    })
    $('#restaurant_disbursement_time_period').on('change', function(){
        if(this.value === 'weekly'){
            $('#restaurant_time_period_section').removeClass('col-12');
            $('#restaurant_time_period_section').addClass('col-6');
            $('#restaurant_week_day_section').removeClass('d-none');
        }else{
            $('#restaurant_week_day_section').addClass('d-none');
            $('#restaurant_time_period_section').removeClass('col-6');
            $('#restaurant_time_period_section').addClass('col-12');
        }
    })
    $('#dm_disbursement_time_period').on('change', function(){
        if(this.value === 'weekly'){
            $('#dm_time_period_section').removeClass('col-12');
            $('#dm_time_period_section').addClass('col-6');
            $('#dm_week_day_section').removeClass('d-none');
        }else{
            $('#dm_week_day_section').addClass('d-none');
            $('#dm_time_period_section').removeClass('col-6');
            $('#dm_time_period_section').addClass('col-12');
        }
    })

});

$(document).on('click', '.restaurantDisbursementCommand', function () {
    copyToClipboard('restaurantDisbursementCommand');
});

$(document).on('click', '.dmDisbursementCommand', function () {
    copyToClipboard('dmDisbursementCommand');
});


function copyToClipboard(elementId) {
    let commandElement = document.getElementById(elementId);
    navigator.clipboard.writeText(commandElement.value);
    toastr.success('Copied to clipboard!');
}
