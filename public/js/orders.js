$(document).on("click", "#go-filter-orders", function(e) {
    let number_search = $('#number-search').val();
    let date_from = $('#date-from').val();
    let date_to = $('#date-to').val();
    let source_order = $('#source-order').val();

    $.ajax({
            url: '/?number_search=' + number_search + "&date_from=" + date_from + "&date_to=" + date_to + "&source_order=" + source_order,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $('#ajax-reload').html(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
});

$(document).on("keypress","#number-search",function(e){
    if (e.keyCode == 13) {
        document.getElementById("go-filter-orders").click();
    }
})


$(document).on("click", "#clear-filter", function(e) {
    $('#number-search').val('');
    $('#date-from').val('');
    $('#date-to').val('');
    $('#source-order').val('');
    let number_search = $('#number-search').val();
    let date_from = $('#date-from').val();
    let date_to = $('#date-to').val();
    let source_order = $('#source-order').val();

    $.ajax({
            url: '/?number_search=' + number_search + "&date_from=" + date_from + "&date_to=" + date_to + "&source_order=" + source_order,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $('#ajax-reload').html(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
});

$(document).on("click", "#get-report", function(e) {
    let date_from = $('#date-from').val();
    let date_to = $('#date-to').val();
    $('#loading').show();
    $('#ajax-reload').hide();
    $.ajax({
            url: "reconciliation-act/?date_from=" + date_from + "&date_to=" + date_to,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {

            $('#ajax-reload').show();
            $("#my_pdf").attr("src",data);
            $('#loading').hide();
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
});
