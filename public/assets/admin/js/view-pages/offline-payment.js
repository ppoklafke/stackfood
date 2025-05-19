"use strict";
function openModal(contentArgument) {
    if (contentArgument === "bkashInfo") {
        $("#sectionViewModal #offline_payment_top_part").addClass("active");
        $("#sectionViewModal #offline_payment_bottom_part").removeClass("active");

        let methodName = $('#method_name').val();

        if (methodName !== '') {
            $('#payment_modal_method_name').text(methodName + ' ' + 'Info');
        }

        function extractPaymentData() {
            let data = [];

            $('.field-row-payment').each(function(index) {
                console.log('modal')
                let title = $(this).find('input[name="input_name[]"]').val();
                let dataValue = $(this).find('input[name="input_data[]"]').val();
                data.push({ title: title, data: dataValue });
            });

            return data;
        }

        let extractedData = extractPaymentData();


        function displayPaymentData() {
            let displayDiv = $('#displayDataDiv');
            let methodNameDisplay = $('#methodNameDisplay');
            methodNameDisplay.empty();
            displayDiv.empty();

            let paymentElement = $('<span>').text('Payment Method');
            let payementDataElement = $('<span>').html(methodName);

            let dataRow = $('<div>').addClass('d-flex gap-3 align-items-center mb-2');
            dataRow.append(paymentElement).append($('<span>').text(':')).append(payementDataElement);


            methodNameDisplay.append(dataRow);

            extractedData.forEach(function(item) {
                let titleElement = $('<span>').text(item.title);
                let dataElement = $('<span>').html(item.data);

                let dataRow = $('<div>').addClass('d-flex gap-3 align-items-center');

                if (item.title !== '') {
                    dataRow.append(titleElement).append($('<span>').text(':')).append(dataElement);
                    displayDiv.append(dataRow);
                }

            });
        }
        displayPaymentData();

        //customer info
        function extractCustomerData() {
            let data = [];

            $('.field-row-customer').each(function(index) {
                let fieldName = $(this).find('input[name="customer_input[' + index + ']"]').val();
                let placeholder = $(this).find('input[name="customer_placeholder[' + index + ']"]').val();
                let isRequired = $(this).find('input[name="is_required[' + index + ']"]').prop('checked');
                data.push({ fieldName: fieldName, placeholder: placeholder, isRequired: isRequired });
            });

            return data;
        }

        let extractedCustomerData = extractCustomerData();
        $('#customer-info-display-div').empty();

        // Loop through the extracted data and populate the display div
        $.each(extractedCustomerData, function(index, item) {
            let isRequiredAttribute = item.isRequired ? 'required' : '';
            let displayHtml = `
                        <input type="text" class="form-control mb-2" name="payment_by" readonly
                        id="payment_by" placeholder="${item.placeholder}"  ${isRequiredAttribute}>
                    `;
            $('#customer-info-display-div').append(displayHtml);
        });

    } else {
        $("#sectionViewModal #offline_payment_top_part").removeClass("active");
        $("#sectionViewModal #offline_payment_bottom_part").addClass("active");

        let methodName = $('#method_name').val();

        if (methodName !== '') {
            $('#payment_modal_method_name').text(methodName + ' ' + 'Info');
        }

        // $('.payment_modal_method_name').text(methodName);

        function extractPaymentData() {
            let data = [];

            $('.field-row-payment').each(function(index) {
                console.log('modal')
                let title = $(this).find('input[name="input_name[]"]').val();
                let dataValue = $(this).find('input[name="input_data[]"]').val();
                data.push({ title: title, data: dataValue });
            });

            return data;
        }

        let extractedData = extractPaymentData();


        function displayPaymentData() {
            let displayDiv = $('#displayDataDiv');
            let methodNameDisplay = $('#methodNameDisplay');
            methodNameDisplay.empty();
            displayDiv.empty();

            let paymentElement = $('<span>').text('Payment Method');
            let payementDataElement = $('<span>').html(methodName);

            let dataRow = $('<div>').addClass('d-flex gap-3 align-items-center mb-2');
            dataRow.append(paymentElement).append($('<span>').text(':')).append(payementDataElement);


            methodNameDisplay.append(dataRow);

            extractedData.forEach(function(item) {
                let titleElement = $('<span>').text(item.title);
                let dataElement = $('<span>').html(item.data);

                let dataRow = $('<div>').addClass('d-flex gap-3 align-items-center');

                if (item.title !== '') {
                    dataRow.append(titleElement).append($('<span>').text(':')).append(dataElement);
                    displayDiv.append(dataRow);
                }

            });
        }
        displayPaymentData();

        //customer info
        function extractCustomerData() {
            let data = [];

            $('.field-row-customer').each(function(index) {
                let fieldName = $(this).find('input[name="customer_input[' + index + ']"]').val();
                let placeholder = $(this).find('input[name="customer_placeholder[' + index + ']"]').val();
                let isRequired = $(this).find('input[name="is_required[' + index + ']"]').prop('checked');
                data.push({ fieldName: fieldName, placeholder: placeholder, isRequired: isRequired });
            });

            return data;
        }

        let extractedCustomerData = extractCustomerData();
        $('#customer-info-display-div').empty();

        // Loop through the extracted data and populate the display div
        $.each(extractedCustomerData, function(index, item) {
            let isRequiredAttribute = item.isRequired ? 'required' : '';
            let displayHtml = `
                        <input type="text" class="form-control mb-2" name="payment_by" readonly
                            id="payment_by" placeholder="${item.placeholder}"  ${isRequiredAttribute}>
                    `;
            $('#customer-info-display-div').append(displayHtml);
        });
    }

    // Open the modal
    $("#sectionViewModal").modal("show");
}
$(document).ready(function() {
    $("#bkashInfoModalButton").on('click', function() {
        let contentArgument = "bkashInfo";
        openModal(contentArgument);
    });

    $("#paymentInfoModalButton").on('click', function() {
        let contentArgument = "paymentInfo";
        openModal(contentArgument);
    });



});



