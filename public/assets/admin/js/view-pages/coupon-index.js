"use strict";

$("#date_from").on("change", function () {
    $('#date_to').attr('min',$(this).val());
});

$("#date_to").on("change", function () {
    $('#date_from').attr('max',$(this).val());
});

$(document).on('ready', function () {
    $('#discount_type').on('change', function() {
        if($('#discount_type').val() == 'amount')
        {
            $('#max_discount').attr("readonly","true");
            $('#max_discount').val(0);
        }
        else
        {
            $('#max_discount').removeAttr("readonly");
        }
    });

    $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
    $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);


    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});
$('#zone_wise').hide();
function coupon_type_change(coupon_type) {
    if(coupon_type=='zone_wise')
    {
        $('#restaurant_wise').hide();
        $('#customer_wise').hide();
        $('#select_customer').val(null).trigger('change');
        $('#zone_wise').show();
    }
    else if(coupon_type=='restaurant_wise')
    {
        $('#restaurant_wise').show();
        $('#customer_wise').show();
        $('#zone_wise').hide();
    }
    else if(coupon_type=='first_order')
    {
        $('#zone_wise').hide();
        $('#restaurant_wise').hide();
        $('#coupon_limit').val(1);
        $('#coupon_limit').attr("readonly","true");
        $('#select_customer').val(null).trigger('change');
        $('#customer_wise').hide();
    }
    else{
        $('#zone_wise').hide();
        $('#restaurant_wise').hide();
        $('#coupon_limit').val('');
        $('#coupon_limit').removeAttr("readonly");
        $('#customer_wise').show();
    }

    if(coupon_type=='free_delivery')
    {
        $('#discount_type').attr("disabled","true");
        $('#discount_type').val("").trigger( "change" );
        $('#discount_type').attr("required","false");
        $('#max_discount').val(0);
        $('#max_discount').attr("readonly","true");
        $('#discount').val(0);
        $('#discount').attr("readonly","true");
    }
    else{
        $('#max_discount').removeAttr("readonly");
        $('#discount_type').removeAttr("disabled");
        $('#discount').removeAttr("readonly");
    }
}

$('#reset_btn').click(function(){
    $('input[name="title[]"]').val('');
    $('#coupon_type').val('restaurant_wise');
    $('#restaurant_wise').show();
    $('#zone_wise').hide();
    $('#coupon_code').val(null);
    $('#coupon_limit').val(null);
    $('#date_from').val(null);
    $('#date_to').val(null);
    $('#discount_type').val('amount');
    $('#discount').val(null);
    $('#max_discount').val(0);
    $('#min_purchase').val(0);
    $('#select_restaurant').val(null).trigger('change');
    $('#choice_zones').val(null).trigger('change');
    $('#select_customer').val(null).trigger('change');
})
$('#coupon_type').on('change',function () {
    let coupon_type = $(this).val();
    coupon_type_change(coupon_type)
})
