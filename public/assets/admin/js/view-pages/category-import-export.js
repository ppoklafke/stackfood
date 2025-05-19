"use strict";
$(document).on('ready', function (){
    $('#date_from').attr('max',(new Date()).toISOString().split('T')[0]);
    $('#date_to').attr('max',(new Date()).toISOString().split('T')[0]);
    $('.id_wise').hide();
    $('.date_wise').hide();
    $('#type').on('change', function()
    {
        $('.id_wise').hide();
        $('.date_wise').hide();
        $('.'+$(this).val()).show();
    })
});
$('#reset_btn').click(function(){
    $('#bulk__import').val(null);
})


$(document).on("click", ".submit_btn", function(e){
    e.preventDefault();
    var data = $(this).val();
    myFunction(data)
});
