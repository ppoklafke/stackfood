"use strict";
    $('#select-all').on('change', function(){
        if($(this).is(':checked')){
            $('.package-available-feature').prop('checked', true);
        }else{
            $('.package-available-feature').prop('checked', false);
        }
    })
    $('.package-available-feature').on('change', function(){
        if($(this).is(':checked')){
            if($('.package-available-feature').length == $('.package-available-feature:checked').length){
                $('#select-all').prop('checked', true);
            }
        }else{
            $('#select-all').prop('checked', false);
        }
    }).trigger('change');

    $('.limit-input').on('change', function() {

        var closestLimitItemCard = $(this).closest('.limit-item-card');
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            if ($(this).val() == 'Use_Limit') {
                closestLimitItemCard.find('.custom-limit-box').show();
                closestLimitItemCard.find('.max_required').prop('required', true);
            } else {
                closestLimitItemCard.find('.custom-limit-box').hide();
                closestLimitItemCard.find('.max_required').removeAttr('required');
            }
        }
    }).trigger('change');



    $(document).on("click", "#reset_btn", function () {
    setTimeout(reset, 10);
    });

    function reset(){
    $('.limit-input').trigger('change');
    }

$(document).on("click", ".btn--reset", function () {
    $('.custom-limit-box').hide();
    $('.max_required').removeAttr('required');
});
