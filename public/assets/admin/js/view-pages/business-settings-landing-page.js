

"use strict";
$("img.svg").each(function() {
    let $img = jQuery(this);
    let imgID = $img.attr("id");
    let imgClass = $img.attr("class");
    let imgURL = $img.attr("src");

    jQuery.get(
        imgURL,
        function(data) {

            let $svg = jQuery(data).find("svg");


            if (typeof imgID !== "undefined") {
                $svg = $svg.attr("id", imgID);
            }

            if (typeof imgClass !== "undefined") {
                $svg = $svg.attr("class", imgClass + " replaced-svg");
            }


            $svg = $svg.removeAttr("xmlns:a");

            if (
                !$svg.attr("viewBox") &&
                $svg.attr("height") &&
                $svg.attr("width")
            ) {
                $svg.attr(
                    "viewBox",
                    "0 0 " + $svg.attr("height") + " " + $svg.attr("width")
                );
            }
            $img.replaceWith($svg);
        },
        "xml"
    );
});


$(document).ready(function () {
    $('.read-file').on('change', function () {
        readUrl(this);
    });
    function readUrl(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = (e) => {
                let imgName = input.files[0].name;
                input.setAttribute("data-title", imgName);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});
$('input[name="landing_integration_via"]').on('change', function() {
    $(`.__input-tab`).removeClass('active')
    $(`#${this.value}`).addClass('active')
})

let swiper = new Swiper(".mySwiper", {
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
});
