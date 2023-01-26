/////// execute code when page loads
document.addEventListener("DOMContentLoaded", function() {
    // ... then some code


});
// end DOMContentLoaded


/////// Enable tooltip of Bootstrap5
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

/////// Prevent closing from click inside dropdown
document.querySelectorAll('.dropdown-menu').forEach(function(element) {
    element.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
// end querySelectorAll



// window.addEventListener("resize", function() {
//     "use strict";
//     window.location.reload();
// });


document.addEventListener("DOMContentLoaded", function() {

    /////// Prevent closing from click inside dropdown
    document.querySelectorAll('.dropdown-menu').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // make it as accordion for smaller screens
    if (window.innerWidth < 992) {

        // close all inner dropdowns when parent is closed
        document.querySelectorAll('.navbar .dropdown').forEach(function(everydropdown) {
            everydropdown.addEventListener('hidden.bs.dropdown', function() {
                // after dropdown is hidden, then find all submenus
                this.querySelectorAll('.megasubmenu').forEach(function(everysubmenu) {
                    // hide every submenu as well
                    everysubmenu.style.display = 'none';
                });
            })
        });

        document.querySelectorAll('.has-megasubmenu a').forEach(function(element) {
            element.addEventListener('click', function(e) {

                let nextEl = this.nextElementSibling;
                if (nextEl && nextEl.classList.contains('megasubmenu')) {
                    // prevent opening link if link needs to open dropdown
                    e.preventDefault();

                    if (nextEl.style.display == 'block') {
                        nextEl.style.display = 'none';
                    } else {
                        nextEl.style.display = 'block';
                    }

                }
            });
        })
    }
    // end if innerWidth
});
// DOMContentLoaded  end

function darken_screen(yesno) {
    if (yesno == true) {
        document.querySelector('.screen-darken').classList.add('active');
    } else if (yesno == false) {
        document.querySelector('.screen-darken').classList.remove('active');
    }
}

function close_offcanvas() {
    darken_screen(false);
    document.querySelector('.mobile-offcanvas.show').classList.remove('show');
    document.body.classList.remove('offcanvas-active');
}

function show_offcanvas(offcanvas_id) {
    darken_screen(true);
    document.getElementById(offcanvas_id).classList.add('show');
    document.body.classList.add('offcanvas-active');
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('[data-trigger]').forEach(function(everyelement) {

        let offcanvas_id = everyelement.getAttribute('data-trigger');

        everyelement.addEventListener('click', function(e) {
            e.preventDefault();
            show_offcanvas(offcanvas_id);

        });
    });

    document.querySelectorAll('.filter-close').forEach(function(everybutton) {

        everybutton.addEventListener('click', function(e) {
            e.preventDefault();
            close_offcanvas();
        });
    });

    try {
        document.querySelector('.screen-darken').addEventListener('click', function(event) {
            close_offcanvas();
        });
    } catch (error) {

    }


});
// DOMContentLoaded  end


$(document).on("click", ".add-to-cart", function(e) {
    var product_id = $(this).data('productId');
    var qty = $("#qty-" + product_id).val();
    $.ajax({
            url: '/cart/add?product_id=' + product_id + "&qty=" + qty,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $('#cart-qty').text(data);
            $('#cart-qty-m').text(data);
            toastr.options.positionClass = 'toast-top-center';
            toastr.options.timeOut = 1000;
            toastr.success(add_to_cart_text);

        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
        $(this).addClass('btn-success');
            $(this).addClass('text-white');
            $(this).addClass('widget-icontop');
            $(this).addClass('position-relative');
            $(this).removeClass('btn-primary');
            $(this).text(added_to_cart_text);
});

$(document).on("click", ".add-to-wishlist", function(e) {
    let product_id = $(this).data('productId');
    var btn = $(this);
    let qty = $("#qty-" + product_id).val();
    btn.toggleClass('btn-primary');
    btn.toggleClass('btn-light');
    $.ajax({
            url: '/wishlist/add?product_id=' + product_id + "&qty=" + qty,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $('#wishlist-qty').text(data);
            $('#wishlist-qty-m').text(data);
            toastr.options.showMethod = 'slideDown';
            toastr.options.hideMethod = 'slideUp';
            toastr.options.closeMethod = 'slideUp';
            //toastr.options.closeHtml = '<button><i class="icon-off"></i></button>';
            if (btn.hasClass('btn-primary')) {
                toastr.options.positionClass = 'toast-top-center';
                toastr.options.timeOut = 1000;
                toastr.success(add_to_wishlist_text);
            } else {
                toastr.options.positionClass = 'toast-top-center';
                toastr.options.timeOut = 1000;
                toastr.error(delete_wishlist_text);
            }

        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
});

$(document).on("click", ".delete-wishlist", function(e) {
    let product_id = $(this).data('productId');
    $.ajax({
            url: '/wishlist/delete?product_id=' + product_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $("#products_wrap").empty().html(data);
            $.ajax({
                    url: '/wishlist/add',
                    type: "get",
                    datatype: "html"
                })
                .done(function(data) {
                    $('#wishlist-qty').text(data);
                    $('#wishlist-qty-m').text(data);
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.options.timeOut = 1000;
                    toastr.error(delete_wishlist_text);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log('No response from server');
                });
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });

});

$(document).on('click', '.qty-plus', function() {
    let product_id = $(this).data('productId');
    let mutliplisity = Number($(this).data('multiplisity'));
    let qty = $('#qty-' + product_id + '').val();
    qty = Number(qty) + Number(mutliplisity);

    $('#qty-' + product_id + '').val(qty);

})

$(document).on('click', '.qty-minus', function() {
    let product_id = $(this).data('productId');
    let qty = $('#qty-' + product_id + '').val();
    let mutliplisity = Number($(this).data('multiplisity'));
    qty = qty - mutliplisity;
    if (qty < mutliplisity) {
        qty = mutliplisity;
    }

    $('#qty-' + product_id + '').val(qty);

})

function setQtyCart(row_id, qty) {
    $.ajax({
            url: '/cart/set?qty=' + qty + "&row_id=" + row_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            getDataCart();
            $('#cart-qty').text(data);

        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
}

function getDataCart() {

    $.ajax({
            url: '',
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $(".cart-items").empty().html(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
}



$(document).on('click', '.button-plus', function() {
    let row_id = $(this)[0].dataset.productId;
    let mutliplicity = Number($(this)[0].dataset.multiplicity);
    let is_cart = Number($(this)[0].dataset.is_cart);
    let cart_row_id = $(this)[0].dataset.rowId;
    let qty = Number($('#qty-' + cart_row_id + '').val());
    qty = qty + mutliplicity;

    $('#qty-' + cart_row_id + '').val(qty);
    if (is_cart == 1) {
        setQtyCart(cart_row_id, qty);
    }
})

$(document).on('click', '.button-minus', function() {

    let row_id = $(this)[0].dataset.productId;
    let mutliplicity = Number($(this)[0].dataset.multiplicity);
    let cart_row_id = $(this)[0].dataset.rowId;
    let is_cart = Number($(this)[0].dataset.is_cart);
    let qty = Number($('#qty-' + cart_row_id + '').val());
    let prev_qty = qty;
    qty = qty - mutliplicity;
    if (qty < mutliplicity) {
        qty = mutliplicity;
    }

    $('#qty-' + cart_row_id + '').val(qty);
    if (is_cart == 1) {
        setQtyCart(cart_row_id, qty);
    }
})

$(document).on("ajaxSend", function(e) {
    NProgress.start();
});

$(document).ajaxSend(function() {
    NProgress.start();
});

$(document).on("ajaxComplete", function(e) {
    NProgress.done();
});



$(document).ready(function() {
    let url2 = window.location.pathname;
    let arr_prop = [];
    let arr_prop_with_sort = [];
    if (url2.search("product/") > -1) {
        prop_json = $('#properties').val();
        var result = jQuery.parseJSON(prop_json);
        //result.forEach((prop) => {
        var arr_temp = Object.entries(result);
        arr_temp.sort(function(a, b) {
            return a[1].sort - b[1].sort
        });
        for (const [key, value] of arr_temp) {

            var pos = arr_prop.indexOf(value.name);
            if (pos < 0) {
                arr_prop.push(value.name);
                var t_arr = [];
                t_arr['name'] = value.name;
                t_arr['sort'] = value.sort;
                t_arr['value'] = value.value;
                arr_prop_with_sort.push(t_arr);
            }

        }
        arr_prop_with_sort.sort(function(a, b) {
            return b.sort - a.sort
        });
        arr_prop = [];
        for (var i = 0; i < arr_prop_with_sort.length; i++) {
            arr_prop.push(arr_prop_with_sort[i].name);
        }
        //console.log(prop.name);
        //})
        f = 2;
        arr_prop_with_sort.forEach(
            function(currentValue) {
                var row = document.createElement("tr");
                var th = document.createElement("th");
                var td = document.createElement("td");
                th.innerText = '' + currentValue.name + '';
                td.innerText = '' + currentValue.value + '';
                row.appendChild(th);
                row.appendChild(td);
                $("#table-prop")[0].after(row);
            }
        )
    }
})

$(document).on("click", ".delete-user", function(e) {
    let user_id = $(this).data('userId');
    $.ajax({
            url: '/profile/delete?user_id=' + user_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $("#ajax-trusted-user").empty().html(data);

        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });

});

function changePassword() {
    var pass_repeat = document.getElementById("password_repeat").value;
    var pass = document.getElementById("password").value;
    if (pass != pass_repeat) {
        //будемо видавати помилку
        $('#password_repeat').addClass('is-invalid');
    } else if (pass == '') {
        $('#password').addClass('is-invalid');
    } else {
        //змінимо пароль
        $('#btn-loading').show();
        $.post("changePassword", {
                _token: $('#token').val(),
                password: pass,
            },
            function(data, status) {
                if (status === 'success') {
                    $('#btn-loading').hide();
                    $('#password_repeat').removeClass('is-invalid');
                    $('#password').removeClass('is-invalid');
                    toastr.options.positionClass = 'toast-top-center';
                    toastr.options.timeOut = 1000;
                    toastr.success(change_password_text);
                };
            });
    }
}

function changeHidePrice() {

        //$('#btn-loading').show();
        $.get("set-hide-price", {

            },
            function(data, status) {
                if (status === 'success') {

                    toastr.options.positionClass = 'toast-top-center';
                    toastr.options.timeOut = 1000;
                    toastr.success(value_hide_price_was_changed_text);
                };
            });

}

$(document).on('click', '.delete-cart-row', function() {
    let row_id = $(this).data('rowId');
    let qty = 'delete';
    $.ajax({
            url: '/cart/add?qty=' + qty + "&row_id=" + row_id,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            //$("#kt_datatable").empty().html(data);
            //history.pushState(null, null, '?page=' + page + '&category_id=' + category + '&search=' + search);
            //location.hash = page;
            //$('#add_id').val('');
            //$('#add_qty').val(0);
            //$('#add_price').val('');

            getDataCart();
            $('#cart-qty').text(data);

        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('No response from server');
        });
});
