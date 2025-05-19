"use strict";
$(document).on('ready', function () {

    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});

$('#reset_btn').click(function(){
    $('#exampleFormControlSelect1').val(null).trigger('change');
})
let forms = document.querySelectorAll('.priority-form');

forms.forEach(function(form) {
    let select = form.querySelector('.priority-select');

    select.addEventListener('change', function() {
        form.submit();
    });
});
