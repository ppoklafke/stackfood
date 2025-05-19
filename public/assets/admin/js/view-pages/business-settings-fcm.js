"use strict";
$('[data-slide]').on('click', function(){
    let serial = $(this).data('slide')
    $(`.tab--content .item`).removeClass('show')
    $(`.tab--content .item:nth-child(${serial})`).addClass('show')
})

function checkedFunc() {
    $('.switch--custom-label .toggle-switch-input').each( function() {
        if(this.checked) {
            $(this).closest('.switch--custom-label').addClass('checked')
        }else {
            $(this).closest('.switch--custom-label').removeClass('checked')
        }
    })
}
checkedFunc()
$('.switch--custom-label .toggle-switch-input').on('click', checkedFunc)
