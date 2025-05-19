"use strict";


function readURL(input, viewer) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + viewer).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileEg1").change(function() {
    readURL(this, 'viewer');
    $('#image-viewer-section').show(1000);
});
$("#customFileEg2").change(function() {
    readURL(this, 'viewer2');
    $('#image-viewer-section3').show(1000);
});


$('.edit-cuisine').on('click',function (){
    $(".add_active").addClass('active');
    $(".update-lang_form").addClass('d-none');
    $(".add_active_2").removeClass('d-none');
});


$(".update-lang_link").click(function(e) {
    e.preventDefault();
    $(".update-lang_link").removeClass('active');
    $(".update-lang_form").addClass('d-none');
    $(".add_active").removeClass('active');
    $(this).addClass('active');
    let form_id = this.id;
    let reason_id = $(this).data('cuisine-id');
    let lang = form_id.substring(0, form_id.length - 5);
        $("#" + lang + "-langform_"+ reason_id).removeClass('d-none');
    if (lang === 'default') {
        $(".add_active_2").removeClass('d-none');
    }
});

$(".lang_link1").click(function(e) {
    e.preventDefault();
    $(".lang_link1").removeClass('active');
    $(".lang_form1").addClass('d-none');
    $(this).addClass('active');
    let form_id = this.id;
    let lang = form_id.substring(0, form_id.length - 6);
    $("#" + lang + "-form1").removeClass('d-none');
    if (lang === 'default') {
        $(".default-form1").removeClass('d-none');
    }
})
