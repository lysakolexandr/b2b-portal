$(document).on("click", ".delete-price", function(e) {
    let price_id = $(this).data('priceId');
    let price_type = $(this).data('priceType');
    $.ajax({
            url: '/prices/delete?price_id=' + price_id + '&price_type=' + price_type,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $('#body-ajax').html(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
});

jQuery(document).ready(function($) {
    $('.copy-link').on('click', function() {
        let copyText = $(this).prev();

        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = copyText.val(); //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
        toastr.options.positionClass = 'toast-top-center';
        toastr.options.timeOut = 1000;
        toastr.success(copy_link_text);
    });
});

function copy() {
    let copyText = $(this).prev();
    copyText.select();
    document.execCommand("copy");
}
