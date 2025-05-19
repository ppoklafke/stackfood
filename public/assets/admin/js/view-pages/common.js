"use strict";

    //Text max limit
    document.addEventListener("DOMContentLoaded", function () {
        const textareas = document.querySelectorAll('textarea[data-maxlength]');
        textareas.forEach(function (textarea) {
        const maxLength = parseInt(textarea.getAttribute('data-maxlength'), 10);
        const counter = textarea.parentElement.querySelector('.text-body-light');

        const updateCounter = () => {
            if (textarea.value.length > maxLength) {
            textarea.value = textarea.value.slice(0, maxLength);
            }
            if (counter) {
            counter.textContent = `${textarea.value.length}/${maxLength}`;
            }
        };
        textarea.addEventListener('input', updateCounter);
        // Set initial counter value on load
        updateCounter();
        });
    });
    //Text max limit

    $(document).ready(function () {
        if ($(".upload-file").length) {
            initFileUpload();
            checkPreExistingImages();
        }
    });

    function initFileUpload() {
        $(document).on("change", ".single_file_input", function (e) {
            handleFileChange($(this), e.target.files[0]);
        });

        // $(document).on("click", ".remove_btn", function () {
        //     resetFileUpload($(this).closest(".upload-file"));
        // });

        $(document).on("click", ".edit_btn", function (e) {
            e.stopImmediatePropagation();
            let $card = $(this).closest(".upload-file");

            $card.removeClass("input-disabled");
            let $input = $card.find(".single_file_input");
            $input.trigger("click");
        });

        $(document).on("click", "button[type=reset]", function () {
            $(this)
                .closest("form")
                .find(".upload-file")
                .each(function () {
                    resetFileUpload($(this));
                });
        });
    }

    function checkPreExistingImages() {
        $(".upload-file").each(function () {
            var $card = $(this);
            var $textbox = $card.find(".upload-file-textbox");
            var $imgElement = $card.find(".upload-file-img");
            var $removeBtn = $card.find(".remove_btn");
            let $overlay = $card.find(".overlay");

            // If there's already a valid image source
            if (
                $imgElement.attr("src") &&
                $imgElement.attr("src") !== window.location.href &&
                $imgElement.attr("src") !== ""
            ) {
                $textbox.hide();
                $imgElement.show();
                $overlay.addClass("show");
                $removeBtn.css("opacity", 1);
                $card.addClass("input-disabled");
            }
        });
    }

    function handleFileChange($input, file) {
        let $card = $input.closest(".upload-file");
        let $textbox = $card.find(".upload-file-textbox");
        let $imgElement = $card.find(".upload-file-img");
        let $removeBtn = $card.find(".remove_btn");
        let $overlay = $card.find(".overlay");
        $card.addClass("input-disabled");

        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $textbox.hide();
                $imgElement.attr("src", e.target.result).show();
                $removeBtn.css("opacity", 1);
                $overlay.addClass("show");
            };
            reader.readAsDataURL(file);
        }
    }

    function resetFileUpload($card) {
        let $input = $card.find(".single_file_input");
        let $imgElement = $card.find(".upload-file-img");
        let $textbox = $card.find(".upload-file-textbox");
        let $removeBtn = $card.find(".remove_btn");
        let $overlay = $card.find(".overlay");
        let defaultSrc = $imgElement.data("default-src") || "";

        $input.val("");

        if (defaultSrc) {
            $imgElement.attr("src", defaultSrc).show();
            $textbox.hide();
            $overlay.addClass("show");
            $removeBtn.css("opacity", 1);
            $card.addClass("input-disabled");
        } else {
            $imgElement.hide().attr("src", "");
            $textbox.show();
            $overlay.removeClass("show");
            $removeBtn.css("opacity", 0);
            $card.removeClass("input-disabled");
        }
    }


document.addEventListener("DOMContentLoaded", function () {
    let checkboxes = document.querySelectorAll(".dynamic-checkbox");
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("click", function (event) {
            event.preventDefault();
            const checkboxId = checkbox.getAttribute("data-id");
            const imageOn = checkbox.getAttribute("data-image-on");
            const imageOff = checkbox.getAttribute("data-image-off");
            const titleOn = checkbox.getAttribute("data-title-on");
            const titleOff = checkbox.getAttribute("data-title-off");
            const textOn = checkbox.getAttribute("data-text-on");
            const textOff = checkbox.getAttribute("data-text-off");

            const isChecked = checkbox.checked;

            if (isChecked) {
                $("#toggle-status-title").empty().append(titleOn);
                $("#toggle-status-message").empty().append(textOn);
                $("#toggle-status-image").attr("src", imageOn);
                $("#toggle-status-ok-button").attr(
                    "toggle-ok-button",
                    checkboxId
                );
                $("#toggle-ok-button").attr("toggle-ok-button", checkboxId);

                console.log("Checkbox " + checkboxId + " is checked");
            } else {
                $("#toggle-status-title").empty().append(titleOff);
                $("#toggle-status-message").empty().append(textOff);
                $("#toggle-status-image").attr("src", imageOff);
                $("#toggle-status-ok-button").attr(
                    "toggle-ok-button",
                    checkboxId
                );
                $("#toggle-ok-button").attr("toggle-ok-button", checkboxId);
                console.log("Checkbox " + checkboxId + " is unchecked");
            }

            $("#toggle-status-modal").modal("show");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    let checkboxes = document.querySelectorAll(".dynamic-checkbox-toggle");
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("click", function (event) {
            event.preventDefault();
            const checkboxId = checkbox.getAttribute("data-id");
            const imageOn = checkbox.getAttribute("data-image-on");
            const imageOff = checkbox.getAttribute("data-image-off");
            const titleOn = checkbox.getAttribute("data-title-on");
            const titleOff = checkbox.getAttribute("data-title-off");
            const textOn = checkbox.getAttribute("data-text-on");
            const textOff = checkbox.getAttribute("data-text-off");

            const isChecked = checkbox.checked;

            if (isChecked) {
                $("#toggle-title").empty().append(titleOn);
                $("#toggle-message").empty().append(textOn);
                $("#toggle-image").attr("src", imageOn);
                $("#toggle-ok-button").attr("toggle-ok-button", checkboxId);
            } else {
                $("#toggle-title").empty().append(titleOff);
                $("#toggle-message").empty().append(textOff);
                $("#toggle-image").attr("src", imageOff);
                $("#toggle-ok-button").attr("toggle-ok-button", checkboxId);
            }

            $("#toggle-modal").modal("show");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    let imageData = document.querySelectorAll(".remove-image");
    imageData.forEach(function (image) {
        image.addEventListener("click", function (event) {
            event.preventDefault();
            const imageId = image.getAttribute("data-id");
            const title = image.getAttribute("data-title");
            const text = image.getAttribute("data-text");

            $("#toggle-status-title").empty().append(title);
            $("#toggle-status-message").empty().append(text);
            $("#toggle-status-ok-button").attr("toggle-ok-button", imageId);
            $("#toggle-ok-button").attr("toggle-ok-button", imageId);

            $("#toggle-status-modal").modal("show");
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const langLinks = document.querySelectorAll(".lang_link");

    langLinks.forEach(function (langLink) {
        langLink.addEventListener("click", function (e) {
            e.preventDefault();
            langLinks.forEach(function (link) {
                link.classList.remove("active");
            });
            this.classList.add("active");
            document.querySelectorAll(".lang_form").forEach(function (form) {
                form.classList.add("d-none");
            });
            let form_id = this.id;
            let lang = form_id.substring(0, form_id.length - 5);

            $("#" + lang + "-form").removeClass("d-none");
            $("#" + lang + "-form1").removeClass("d-none");
            $("#" + lang + "-form2").removeClass("d-none");
            $("#" + lang + "-form3").removeClass("d-none");
            $("#" + lang + "-form4").removeClass("d-none");
            if (lang === "default") {
                $(".default-form").removeClass("d-none");
            }
        });
    });
});

$(document).ready(function () {
    var $imageBox = undefined;
    $("#image-input, #image-input2, .image-input").on("change", function (e) {
        if (e.target.files[0]) {
            $imageBox = $(this).parents(".image-box");
            $imageBox.find(".upload-icon, .upload-text, .upload-text2").hide();

            if (this.files[0].type.includes("image/")) {
                readImageURL(this);
            } else {
                $imageBox.find(".upload-icon, .preview-image").hide();
                $imageBox.find(".upload-text").text(this.files[0].name).show();
                $imageBox
                    .find(".upload-text2")
                    .text(formatFileSize(this.files[0].size))
                    .show();
            }
        }

        if ($(this).val()) {
            $(this).siblings(".delete_image").css("display", "flex");
        }
    });

    // Function to read and display the image preview
    function readImageURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(input)
                    .parents(".image-box")
                    .find(".preview-image")
                    .attr("src", e.target.result)
                    .show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    }

    $("#upload_excel").change(function () {
        var fileName = $(this).val().split("\\").pop();
        $(this)
            .parents(".image-box")
            .addClass("active")
            .find(".upload-text")
            .text(fileName);
    });

    $(".delete_image").click(function () {
        $imageBox.find(".preview-image").hide();
        $imageBox.find(".upload-icon, .upload-text, .upload-text2").show();
        $imageBox
            .find(".upload-text")
            .text($imageBox.find(".upload-text").data("text"));
        $imageBox
            .find(".upload-text2")
            .text($imageBox.find(".upload-text2").data("text"));
        $imageBox.find("#image-input, #image-input2, .image-input").val("");
        $(this).css("display", "none");
    });

    $('button[type="reset"]').on("click", function () {
        $(this)
            .parents("form")
            .find(".preview-image, .delete_image")
            .attr("src", "#")
            .hide();
        $(this)
            .parents("form")
            .find(".upload-icon, .upload-text, .upload-text2")
            .show();
        $(this)
            .parents("form")
            .find("#image-input, #image-input2, .image-input")
            .val("");
    });
});

$("[data-slide]").on("click", function () {
    let serial = $(this).data("slide");
    $(`.tab--content .item`).removeClass("show");
    $(`.tab--content .item:nth-child(${serial})`).addClass("show");
});
$(document).ready(function () {
    $(".add-required-attribute").on("click", function () {
        let status = $(this).attr("id");
        let name = $(this).data("textarea-name");
        if ($("#" + status).is(":checked")) {
            $("#en-form ." + name).attr("required", true);
        } else {
            $("#en-form ." + name).removeAttr("required");
        }
    });
});

$(document).on("click", ".location-reload", function () {
    location.reload();
});
$(document).on("click", ".redirect-url", function () {
    location.href = $(this).data("url");
});
function readURL(input, viewer = "viewer") {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $("#" + viewer).attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    "use strict";
    $(
        ".upload-img-3, .upload-img-4, .upload-img-2, .upload-img-5, .upload-img-1, .upload-img"
    ).each(function () {
        let targetedImage = $(this).find(".img");
        let targetedImageSrc = $(this).find(".img img");
        function proPicURL(input) {
            if (input.files && input.files[0]) {
                let uploadedFile = new FileReader();
                uploadedFile.onload = function (e) {
                    targetedImageSrc.attr("src", e.target.result);
                    targetedImage.addClass("image-loaded");
                    targetedImage.hide();
                    targetedImage.fadeIn(650);
                };
                uploadedFile.readAsDataURL(input.files[0]);
            }
        }
        $(this)
            .find("input")
            .on("change", function () {
                proPicURL(this);
            });
    });

    $(".read-url").on("change", function () {
        readURL(this);
    });
});
$(document).on("ready", function () {
    $(".js-toggle-password").each(function () {
        new HSTogglePassword(this).init();
    });

    $(".js-validate").each(function () {
        $.HSCore.components.HSValidation.init($(this), {
            rules: {
                confirmPassword: {
                    equalTo: "#signupSrPassword",
                },
            },
        });
    });
    // Chart.plugins.unregister(ChartDataLabels);

    // $('.js-chart').each(function () {
    //     $.HSCore.components.HSChartJS.init($(this));
    // });

    // let updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
});

$(".route-alert").on("click", function () {
    let route = $(this).data("url");
    let message = $(this).data("message");
    let title = $(this).data("title");
    route_alert(route, message, title);
});
$(".set-filter").on("change", function () {
    const id = $(this).val();
    const url = $(this).data("url");
    const filter_by = $(this).data("filter");
    let nurl = new URL(url);
    nurl.searchParams.delete("page");
    nurl.searchParams.set(filter_by, id);
    location.href = nurl;
});
$(document).ready(function () {
    $(".onerror-image").on("error", function () {
        let img = $(this).data("onerror-image");
        $(this).attr("src", img);
    });

    $(".onerror-image").each(function () {
        let defaultImage = $(this).data("onerror-image");
        if ($(this).attr("src").endsWith("/")) {
            $(this).attr("src", defaultImage);
        }
    });
});

$(document).on("click", ".confirm-Status-Toggle", function () {
    let Status_toggle = $("#toggle-status-ok-button").attr("toggle-ok-button");
    if ($("#" + Status_toggle).is(":checked")) {
        $("#" + Status_toggle)
            .prop("checked", false)
            .val(0);
    } else {
        $("#" + Status_toggle)
            .prop("checked", true)
            .val(1);
    }
    $("#" + Status_toggle + "_form").submit();
});
$(document).on("click", ".confirm-Toggle", function () {
    let toggle_id = $("#toggle-ok-button").attr("toggle-ok-button");
    if ($("#" + toggle_id).is(":checked")) {
        $("#" + toggle_id).prop("checked", false);
    } else {
        $("#" + toggle_id).prop("checked", true);
    }
    $("#toggle-modal").modal("hide");

    if (toggle_id === "free_delivery_over_status") {
        if ($("#free_delivery_over_status").is(":checked")) {
            $("#free_delivery_over").removeAttr("readonly");
        } else {
            $("#free_delivery_over").attr("readonly", true).val(null);
        }
    }
    if (toggle_id === "product_gallery") {
        if ($("#product_gallery").is(":checked")) {
            $(".access_all_products").removeClass("d-none");
        } else {
            $(".access_all_products").addClass("d-none");
        }
    }
    if (toggle_id === "product_approval") {
        if ($("#product_approval").is(":checked")) {
            $(".access_product_approval").removeClass("d-none");
        } else {
            $(".access_product_approval").addClass("d-none");
        }
    }
    if (toggle_id === "additional_charge_status") {
        if ($("#additional_charge_status").is(":checked")) {
            $("#additional_charge_name")
                .removeAttr("readonly")
                .attr("required", true);
            $("#additional_charge")
                .removeAttr("readonly")
                .attr("required", true);
        } else {
            $("#additional_charge_name")
                .attr("readonly", true)
                .removeAttr("required");
            $("#additional_charge")
                .attr("readonly", true)
                .removeAttr("required");
        }
    }
    if (toggle_id === "cash_in_hand_overflow") {
        if ($("#cash_in_hand_overflow").is(":checked")) {
            $("#cash_in_hand_overflow_restaurant_amount")
                .removeAttr("readonly")
                .attr("required", true);
            $("#min_amount_to_pay_restaurant")
                .removeAttr("readonly")
                .attr("required", true);
            $("#min_amount_to_pay_dm")
                .removeAttr("readonly")
                .attr("required", true);
            $("#dm_max_cash_in_hand")
                .removeAttr("readonly")
                .attr("required", true);
        } else {
            $("#cash_in_hand_overflow_restaurant_amount")
                .attr("readonly", true)
                .removeAttr("required");
            $("#min_amount_to_pay_restaurant")
                .attr("readonly", true)
                .removeAttr("required");
            $("#min_amount_to_pay_dm")
                .attr("readonly", true)
                .removeAttr("required");
            $("#dm_max_cash_in_hand")
                .attr("readonly", true)
                .removeAttr("required");
        }
    }
    if (toggle_id === "customer_date_order_sratus") {
        if ($("#customer_date_order_sratus").is(":checked")) {
            $("#customer_order_date")
                .removeAttr("readonly")
                .attr("required", true);
        } else {
            $("#customer_order_date")
                .attr("readonly", true)
                .removeAttr("required");
        }
    }

    if (toggle_id === "free_delivery_distance_status") {
        if ($("#free_delivery_distance_status").is(":checked")) {
            $("#free_delivery_distance").removeAttr("readonly");
        } else {
            $("#free_delivery_distance").attr("readonly", true).val(null);
        }
    }

    if (toggle_id === "app_url_android_status") {
        if ($("#app_url_android_status").is(":checked")) {
            $("#app_url_android").removeAttr("readonly");
        } else {
            $("#app_url_android").attr("readonly", true);
        }
    }
    if (toggle_id === "app_url_ios_status") {
        if ($("#app_url_ios_status").is(":checked")) {
            $("#app_url_ios").removeAttr("readonly");
        } else {
            $("#app_url_ios").attr("readonly", true);
        }
    }
    if (toggle_id === "web_app_url_status") {
        if ($("#web_app_url_status").is(":checked")) {
            $("#web_app_url").removeAttr("readonly");
        } else {
            $("#web_app_url").attr("readonly", true);
        }
    }
    if (toggle_id === "new_customer_discount_status") {
        if ($("#new_customer_discount_status").is(":checked")) {
            $("#new_customer_discount_amount")
                .removeAttr("readonly")
                .attr("required", true);
            $("#new_customer_discount_amount_validity")
                .removeAttr("readonly")
                .attr("required", true);
            $("#new_customer_discount_amount_type")
                .removeAttr("disabled")
                .attr("required", true);
            $("#new_customer_discount_validity_type")
                .removeAttr("disabled")
                .attr("required", true);
        } else {
            $("#new_customer_discount_amount")
                .attr("readonly", true)
                .removeAttr("required");
            $("#new_customer_discount_amount_validity")
                .attr("readonly", true)
                .removeAttr("required");
            $("#new_customer_discount_amount_type")
                .attr("disabled", true)
                .removeAttr("required");
            $("#new_customer_discount_validity_type")
                .attr("disabled", true)
                .removeAttr("required");
        }
    }
    if (toggle_id === "customer_loyalty_point") {
        if ($("#customer_loyalty_point").is(":checked")) {
            $("#loyalty_point_exchange_rate")
                .removeAttr("readonly")
                .attr("required", true);
            $("#item_purchase_point")
                .removeAttr("readonly")
                .attr("required", true);
            $("#minimum_transfer_point")
                .removeAttr("readonly")
                .attr("required", true);
        } else {
            $("#loyalty_point_exchange_rate")
                .attr("readonly", true)
                .removeAttr("required");
            $("#item_purchase_point")
                .attr("readonly", true)
                .removeAttr("required");
            $("#minimum_transfer_point")
                .attr("readonly", true)
                .removeAttr("required");
        }
    }
    if (toggle_id === "wallet_status") {
        if ($("#wallet_status").is(":checked")) {
            $(".text-muted").removeClass("text-muted");
            $("#new_customer_discount_status").removeAttr("disabled");
            $("#add_fund_status").removeAttr("disabled");
            $("#ref_earning_status").removeAttr("disabled");
            $("#refund_to_wallet").removeAttr("disabled");

            $("#ref_earning_exchange_rate")
                .removeAttr("readonly")
                .attr("required", true);
            $("#new_customer_discount_amount")
                .removeAttr("readonly")
                .attr("required", true);
            $("#new_customer_discount_amount_validity")
                .removeAttr("readonly")
                .attr("required", true);
            $("#new_customer_discount_amount_type")
                .removeAttr("disabled")
                .attr("required", true);
            $("#new_customer_discount_validity_type")
                .removeAttr("disabled")
                .attr("required", true);
        } else {
            $("#new_customer_discount_status")
                .attr("disabled", true)
                .parent("label")
                .addClass("text-muted");
            $("#add_fund_status")
                .attr("disabled", true)
                .parent("label")
                .addClass("text-muted");
            $("#ref_earning_status")
                .attr("disabled", true)
                .parent("label")
                .addClass("text-muted");
            $("#refund_to_wallet")
                .attr("disabled", true)
                .parent("label")
                .addClass("text-muted");

            $("#ref_earning_exchange_rate")
                .attr("readonly", true)
                .removeAttr("required");
            $("#new_customer_discount_amount")
                .attr("readonly", true)
                .removeAttr("required");
            $("#new_customer_discount_amount_validity")
                .attr("readonly", true)
                .removeAttr("required");
            $("#new_customer_discount_amount_type")
                .attr("disabled", true)
                .removeAttr("required");
            $("#new_customer_discount_validity_type")
                .attr("disabled", true)
                .removeAttr("required");
        }
    }

    if (toggle_id === "extra_packaging_status") {
        if ($("#extra_packaging_status").is(":checked")) {
            $("#extra_packaging_amount")
                .removeAttr("readonly")
                .attr("required", true);
        } else {
            $("#extra_packaging_amount")
                .attr("readonly", true)
                .removeAttr("required");
        }
    }
});

document.querySelectorAll('[name="search"]').forEach(function (element) {
    element.addEventListener("input", function (event) {
        if (this.value === "" && window.location.search !== "") {
            let baseUrl = window.location.origin + window.location.pathname;
            window.location.href = baseUrl;
        }
    });
});

$(document).on("click", ".print-Div", function () {
    if ($("html").attr("dir") === "rtl") {
        $("html").attr("dir", "ltr");
        let printContents = document.getElementById("printableArea").innerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        $(".initial-38-1").attr("dir", "rtl");
        window.print();
        document.body.innerHTML = originalContents;
        $("html").attr("dir", "rtl");
        location.reload();
    } else {
        let printContents = document.getElementById("printableArea").innerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    let modalData = document.querySelectorAll(".new-dynamic-submit-model");
    modalData.forEach(function (data) {
        data.addEventListener("click", function (event) {
            event.preventDefault();
            const dataId = data.getAttribute("data-id");
            const title = data.getAttribute("data-title");
            const text = data.getAttribute("data-text");
            const image = data.getAttribute("data-image");
            const type = data.getAttribute("data-type");
            const btn_class = data.getAttribute("data-btn_class");
            const cancel_btn_text = data.getAttribute("data-2nd_btn_text");

            $("#get-text-note").val("");
            $("#modal-title").empty().append(title);
            $("#modal-text").empty().append(text);
            $("#image-src").attr("src", image);
            $("#new-dynamic-submit-model").modal("show");
            $("#new-dynamic-ok-button").addClass("btn-outline-danger");
            $("#new-dynamic-ok-button-show").addClass("d-none");
            $("#hide-buttons").addClass("d-none");

            if (type === "delete") {
                $("#new-dynamic-ok-button").attr("toggle-ok-button", dataId);
                $("#note-data").addClass("d-none");
                $("#hide-buttons").removeClass("d-none");
            } else if (type === "pause") {
                $("#new-dynamic-ok-button").attr("toggle-ok-button", dataId);
                $("#hide-buttons").removeClass("d-none");
                $("#note-data").removeClass("d-none");
                $("#get-text-note").attr("get-text-note-id", dataId);
            } else if (type === "deny") {
                $("#new-dynamic-ok-button").attr("toggle-ok-button", dataId);
                $("#hide-buttons").removeClass("d-none");
                $("#note-data").removeClass("d-none");
                $("#get-text-note").attr("get-text-note-id", dataId);
                $("#new-dynamic-ok-button")
                    .removeClass("btn-outline-danger")
                    .addClass(btn_class);
                $("#cancel_btn_text").text(cancel_btn_text);
            } else if (type === "resume") {
                $("#new-dynamic-ok-button").attr("toggle-ok-button", dataId);
                $("#hide-buttons").removeClass("d-none");
                $("#note-data").addClass("d-none");
                $("#new-dynamic-ok-button")
                    .removeClass("btn-outline-danger")
                    .addClass(btn_class);
            } else {
                $("#note-data").addClass("d-none");
                $("#hide-buttons").addClass("d-none");
                $("#new-dynamic-ok-button-show").removeClass("d-none");
            }
        });
    });
});

$(document).on("click", ".confirm-model", function () {
    let Status_toggle = $("#new-dynamic-ok-button").attr("toggle-ok-button");
    $("#" + Status_toggle + "_form").submit();
});
$(document).on("keyup", "#get-text-note", function () {
    let text_data = $("#get-text-note").attr("get-text-note-id");
    $("#" + text_data + "_note").val($(this).val());
});

document.addEventListener("DOMContentLoaded", function () {
    const activeLink = document.querySelector(".nav-link.active");

    if (activeLink) {
        activeLink.scrollIntoView({
            behavior: "smooth",
            block: "nearest",
            inline: "center",
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    $(function () {
        $(".date-range-picker").daterangepicker({
            // "timePicker": true,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
            // minDate: new Date(),
            // startDate: moment().startOf('hour'),
            maxDate: moment(),
            startDate: $(this).data("startDate"),
            // endDate: moment().startOf('hour').add(10, 'day'),
            endDate: $(this).data("endDate"),
            autoUpdateInput: false,
            locale: {
                cancelLabel: "Clear",
            },
            alwaysShowCalendars: true,
        });

        $(".date-range-picker").attr("placeholder", "Select date");

        $(".date-range-picker").on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
            }
        );

        $(".date-range-picker").on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
            }
        );
    });
});

$(document).on("ready", function () {

    $.fn.select2DynamicDisplay = function () {
        const limit = 5;
        function updateDisplay($element) {
            var $rendered = $element
                .siblings(".select2-container")
                .find(".select2-selection--multiple")
                .find(".select2-selection__rendered");
            var $container = $rendered.parent();
            var containerWidth = $container.width();
            var totalWidth = 0;
            var itemsToShow = [];
            var remainingCount = 0;

            // Get all selected items
            var selectedItems = $element.select2("data");

            // Create a temporary container to measure item widths
            var $tempContainer = $("<div>")
                .css({
                    display: "inline-block",
                    padding: "0 15px",
                    "white-space": "nowrap",
                    visibility: "hidden",
                })
                .appendTo($container);

            // Calculate the width of items and determine how many fit
            selectedItems.forEach(function (item) {
                var $tempItem = $("<span>")
                    .text(item.text)
                    .css({
                        display: "inline-block",
                        padding: "0 12px",
                        "white-space": "nowrap",
                    })
                    .appendTo($tempContainer);

                var itemWidth = $tempItem.outerWidth(true);

                if (totalWidth + itemWidth <= containerWidth - 40) {
                    totalWidth += itemWidth;
                    itemsToShow.push(item);
                } else {
                    remainingCount = selectedItems.length - itemsToShow.length;
                    return false;
                }
            });

            $tempContainer.remove();

            const $searchForm = $rendered.find(".select2-search");

            var html = "";
            itemsToShow.forEach(function (item) {
                html += `<li class="name">
                                        <span>${item.text}</span>
                                        <span class="close-icon" data-id="${item.id}"><i class="tio-clear"></i></span>
                                        </li>`;
            });
            if (remainingCount > 0) {
                html += `<li class="ms-auto">
                                        <div class="more">+${remainingCount}</div>
                                        </li>`;
            }

            if (selectedItems.length < limit) {
                html += $searchForm.prop("outerHTML");
            }

            $rendered.html(html);

            function debounce(func, wait) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Attach event listener with debouncing
            $(".select2-search input").on(
                "input",
                debounce(function () {
                    const inputValue = $(this).val().toLowerCase();

                    const $listItems = $(".select2-results__options li");

                    $listItems.each(function () {
                        const itemText = $(this).text().toLowerCase();
                        $(this).toggle(itemText.includes(inputValue));
                    });
                }, 100)
            );

            $(".select2-search input").on("keydown", function (e) {
                if (e.which === 13) {
                    e.preventDefault();

                    const inputValue = $(this).val();
                    if (
                        !inputValue ||
                        itemsToShow.find((item) => item.text === inputValue) ||
                        selectedItems.find((item) => item.text === inputValue)
                    ) {
                        $(this).val("");
                        return null;
                    }

                    if (inputValue) {
                        $element.append(
                            new Option(inputValue, inputValue, true, true)
                        );
                        $element.val([...$element.val(), inputValue]);
                        $(this).val("");
                        $(".multiple-select2").select2DynamicDisplay();
                    }
                }
            });
        }
        return this.each(function () {
            var $this = $(this);

            $this.select2({
                tags: true,
                maximumSelectionLength: limit,
            });

            // Bind change event to update display
            $this.on("change", function () {
                updateDisplay($this);
            });

            // Initial display update
            updateDisplay($this);

            $(window).on("resize", function () {
                updateDisplay($this);
            });
            $(window).on("load", function () {
                updateDisplay($this);
            });

            // Handle the click event for the remove icon
            $(document).on(
                "click",
                ".select2-selection__rendered .close-icon",
                function (e) {
                    e.stopPropagation();
                    var $removeIcon = $(this);
                    var itemId = $removeIcon.data("id");
                    var $this2 = $removeIcon
                        .closest(".select2")
                        .siblings(".multiple-select2");
                    $this2.val(
                        $this2.val().filter(function (id) {
                            return id != itemId;
                        })
                    );
                    $this2.trigger("change");
                }
            );
        });
    };
    $(".multiple-select2").select2DynamicDisplay();

});

