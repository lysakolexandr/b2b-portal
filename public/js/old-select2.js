var KTSelect2 = {
    init: function() {
        $("#kt_select2_6").select2({
            placeholder: "Введіть назву або артикул...",
            allowClear: !0,
            ajax: {
                url: '/product/list',
                dataType: "json",
                delay: 250,
                data: function(e) {
                    return {
                        q: e.term,
                        page: e.page
                    }
                },
                processResults: function(e, t) {
                    return t.page = t.page || 1, {
                        results: e.items,
                        pagination: {
                            more: 30 * t.page < e.total_count
                        }
                    }
                },
                cache: !0
            },
            escapeMarkup: function(e) {
                return e
            },
            minimumInputLength: 6,
            templateResult: function(e) {
                if (e.loading)
                    return e.text;
                var t = "<div class='select2-result-repository clearfix'><div class='select2-result-repository__meta'><div class='select2-result-repository__title'>" + e.text + "</div>";
                return e.text && (t += ""),
                    t + "<div class='select2-result-repository__statistics'><div class='select2-result-repository__forks'><i class='fa fa-money-bill-alt'></i>Персональна ціна: " + e.price + " " + e.currency + " </div><div class='select2-result-repository__stargazers'><i class='fa fa-warehouse'></i> Залишок: " + e.qty + " шт.</div></div></div></div>"
            },
            templateSelection: function(e) {
                return e.text || e.id
            }
        })
    }
};
jQuery(document).ready((function() {
    KTSelect2.init()
}));


$('#kt_select2_6').on('select2:select', function(e) {
    var data = e.params.data;
    $('#add_price').val(data.price);
    $('#add_qty').val(1);
    $('#add_id').val(data.id);
    $('#add_currency').text(data.currency);
    console.log(data);
});

$(document).on('click', '#add-product-to-draft', function() {
    let product_id = $('#add_id').val();
    let qty = $("#add_qty").val();
    let draft_id = $("#draft_id").val();
    $.ajax({
            url: '/draft/add?product_id=' + product_id + "&qty=" + qty + "&draft_id=" + draft_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            //$("#kt_datatable").empty().html(data);
            //history.pushState(null, null, '?page=' + page + '&category_id=' + category + '&search=' + search);
            //location.hash = page;
            //$('#add_id').val('');
            $('#add_qty').val(0);
            //$('#add_price').val('');

            getDataDraft();
            //$('#draft-qty').text(data);

        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
})

function getDataDraft() {

    $.ajax({
            url: '',
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $("#ajax_table").empty().html(data);
            //history.pushState(null, null, '?page=' + page + '&category_id=' + category + '&search=' + search);
            //location.hash = page;
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
}

$(document).on('click', '#copy-draft', function() {
    let draft_id = $(this).data('draftId');
    $.ajax({
            url: '/draft/copy?id=' + draft_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            window.location = "/draft-edit/" + data;
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
})
$(document).on('click', '#delete-draft', function() {
    let draft_id = $(this).data('draftId');
    $.ajax({
            url: '/draft/delete?id=' + draft_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            window.location = "/drafts/";
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
})
$(document).on('click', '#add-draft-to-order', function() {
    // let draft_id = $(this).data('draftId');
    // let draft_id = $(this).data('draftId');
    // $.ajax({
    //         url: '/draft/copy?id=' + draft_id,
    //         type: "get",
    //         datatype: "html"
    //     })
    //     .done(function(data) {
    //         window.location = "/draft-edit/" + data;
    //     })
    //     .fail(function(jqXHR, ajaxOptions, thrownError) {
    //         console.log('No response from server');
    //     });
})

$(document).on('click', '.delete-draft-row', function() {
    let product_id = $(this).data('productId');
    let row_id = $(this).data('rowId');
    let qty = 'delete';
    $.ajax({
            url: '/draft/add?qty=' + qty + "&row_id=" + row_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            getDataDraft();
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
})

$(document).on('click', '.nav-draft', function() {
    let draft_id = $(this).data('draftId');
    $('#copy_draft_button')[0].href = '/draft/copy/' + draft_id;
    $('#edit_draft_button')[0].href = '/draft-edit/' + draft_id;
    $('#delete_draft_button')[0].href = '/draft/delete/' + draft_id;
    //console.log(draft_id);
})

$(document).on('click', '#create_draft', function() {
    let name = $('#draft_name').val();
    window.location = "/draft-edit/?name=" + name;
    //console.log(name);
})