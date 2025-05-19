"use strict";
$("#date_from").on("change", function () {
    $('#date_to').attr('min',$(this).val());
});

$("#date_to").on("change", function () {
    $('#date_from').attr('max',$(this).val());
});
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
        $('#zone_wise').hide();
        $('#customer_wise').show();

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
        $('#max_discount').val(0);
        $('#discount_type').attr("required","false");
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
    })
$('#coupon_type').on('change',function () {
    let coupon_type = $(this).val();
    coupon_type_change(coupon_type)
})


$('#reset_btn').click(function(){
    location.reload(true);

})
