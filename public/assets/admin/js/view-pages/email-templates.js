

"use strict";

let editor = CKEDITOR.replace('ckeditor');


editor.on( 'change', function( evt ) {
    $('#mail-body').empty().html(evt.editor.getData());
});


$('input[data-id="mail-title"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('#'+dataId).text(value);
});
$('input[data-id="mail-button"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('#'+dataId).text(value);
});
$('input[data-id="mail-footer"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('#'+dataId).text(value);
});
$('input[data-id="mail-copyright"]').on('keyup', function() {
    let dataId = $(this).data('id');
    let value = $(this).val();
    $('#'+dataId).text(value);
});

function read_URL(input, viewer) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $('#' + viewer).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#mail-logo").change(function() {
    read_URL(this, 'logoViewer');
});

$("#mail-banner").change(function() {
    read_URL(this, 'bannerViewer');
});

$("#mail-icon").change(function() {
    read_URL(this, 'iconViewer');
});


$('.check-mail-element').on('change', function() {
    let id = $(this).data('id');
        console.log(id);
        if ($('.' + id).is(':checked')) {
            $('#' + id).show();
        } else {
            $('#' + id).hide();
        }
});
document.getElementById('see-how-it-works').addEventListener('click', function() {
    $('#email-modal').show();
});


document.getElementById('mail-route-selector').addEventListener('change', function() {
    let value = this.value;
    location.href = baseUrl + '/admin/business-settings/email-setup/' + value + '/' + (value === 'admin' ? 'forgot-password' : 'registration');
});
