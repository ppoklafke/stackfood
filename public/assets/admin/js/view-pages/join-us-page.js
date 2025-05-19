"use strict";


$(document).on('click', '.remove-input-fields-group', function () {
    let id = $(this).data('id');
    $('#'+id).remove();
    $('#file_rows_data_'+id).remove();
    $('#check_box_data'+id).remove();
    $('#check_box_data_main_'+id).remove();
    $('.delete_'+id).remove();
});


document.addEventListener('change', function(event) {
    if (event.target.classList.contains('fieldTypeSelect')) {
        let data = event.target.value;
        let key = $(event.target).data('key');
        optionSelected(data , key);
    }
});

$(document).on('click', '.remove-check-box', function () {

    let parent_id = $(this).data('parent-id');
    let child_id = $(this).data('child-id');
    $('#check_box_data_'+parent_id+'_'+child_id).remove();
});
$(document).on('click', '.add-check-box', function () {
    let parent_id = $(this).data('id');
    add_check_box(parent_id);
});



