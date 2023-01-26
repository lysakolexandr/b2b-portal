jQuery(document).ready(function($) {
    $('#change-token').on('click', function() {
        $.ajax({
                url: '/change-token',
                type: "get",
                datatype: "html"
            })
            .done(function(data) {
                $('#api-token').val(data.token);
                $('#route-currency').val(data.route_currency);
                $('#route-categories-ru').val(data.route_categories_ru);
                $('#route-categories-ua').val(data.route_categories_ua);
                $('#route-products-ua').val(data.route_products_ua);
                $('#route-products-ru').val(data.route_products_ru);
                $('#route-available').val(data.route_available);
                toastr.options.positionClass = 'toast-top-center';
                toastr.options.timeOut = 1000;
                toastr.success(generate_api_key_text);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                console.log('No response from server');
            });
    })

    $('.copy').on('click', function() {
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