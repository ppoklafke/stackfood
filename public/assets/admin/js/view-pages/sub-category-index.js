"use strict";
$(document).on('ready', function () {
    // INITIALIZATION OF DATATABLES
    // =======================================================
    // INITIALIZATION OF SELECT2
    // =======================================================
    $('.js-select2-custom').each(function () {
        let select2 = $.HSCore.components.HSSelect2.init($(this));
    });
});

$('#reset_btn').click(function(){
    $('#parent_id').val(null).trigger('change');
})

let forms = document.querySelectorAll('.priority-form');

forms.forEach(function(form) {
    let select = form.querySelector('.priority-select');

    select.addEventListener('change', function() {
        form.submit();
    });
});
