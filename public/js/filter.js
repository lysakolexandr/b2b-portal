$(document).ready(function() {
    startFilters();
});

function replaceSpace() {
    let filter_json_str = jQuery("#filter_json").val();
    filter_json_str = filter_json_str.trim();
    filter_json_str = filter_json_str.replace(/ :/g, ':');
    filter_json_str = filter_json_str.replace(/: /g, ':');
    filter_json_str = filter_json_str.replace(/ /g, '_');
    filter_json_str = filter_json_str.replace(/`/g, "___");
    filter_json_str = filter_json_str.replace(/\\u2033/g, "___d");
    filter_json_str = filter_json_str.replace(/\\\//g, "ForwardSlash");

    filter_json_str = filter_json_str.replace(/!/g, 'exclamation');
    filter_json_str = filter_json_str.replace(/[+]/g, 'plus');
    filter_json_str = filter_json_str.replace(/[.]/g, 'dot');
    filter_json_str = filter_json_str.replace(/(?=[0-9] ||),(?=\d.*)/g, 'comma');
    //filter_json_str = filter_json_str.replace(/(?<=\d),(?=\d)/g, 'comma');
    filter_json_str = filter_json_str.replace(/[(]/g, 'o_parenthesis');
    filter_json_str = filter_json_str.replace(/[)]/g, 'c_parenthesis');

    jQuery("#filter_json").val(filter_json_str);


    let filtered_json_str = jQuery("#filtered_products_json").val();
    filtered_json_str = filtered_json_str.trim();
    filtered_json_str = filtered_json_str.replace(/ :/g, ':');
    filtered_json_str = filtered_json_str.replace(/: /g, ':');
    filtered_json_str = filtered_json_str.replace(/ /g, '_');
    filtered_json_str = filtered_json_str.replace(/`/g, "___");
    filtered_json_str = filtered_json_str.replace(/\\u2033/g, "___d");
    filtered_json_str = filtered_json_str.replace(/\\\//g, "ForwardSlash");
    filtered_json_str = filtered_json_str.replace(/[(]/g, 'o_parenthesis');
    filtered_json_str = filtered_json_str.replace(/[)]/g, 'c_parenthesis');

    filtered_json_str = filtered_json_str.replace(/[.]/g, 'dot');
    filtered_json_str = filtered_json_str.replace(/!/g, 'exclamation');

    filtered_json_str = filtered_json_str.replace(/(?=[0-9] ||),(?=\d.*)/g, 'comma');
    //filtered_json_str = filtered_json_str.replace(/(?<=\d),(?=\d)/g, 'comma');

    jQuery("#filtered_products_json").val(filtered_json_str);
}

function startFilters() {
    const url = new URL(window.location);
    // var available_str = url.searchParams.get('available');
    // var available = false;
    // if (available_str == "true") {
    //     available = true;
    // };
    // $('#only-available').prop('checked', available);
    // $('#only-available-m').prop('checked', available);
    setFilterToPages();

    window.change_page = 0;
    window.request_id = [];

    replaceSpace();

    var result = jQuery.parseJSON(jQuery("#filter_json").val());
    var arr_full_last = [];
    let arr_prop = [];
    let arr_prop_with_sort = [];
    result.forEach((product) => {
        if (product.properties_filter != null) {
            let prop = jQuery.parseJSON(product.properties_filter);

            var arr_temp = Object.entries(prop);
            arr_temp.sort(function(a, b) {
                return a[1].sort - b[1].sort
            });
            for (const [key, value] of arr_temp) {
                let url2 = window.location.pathname;
                if (url2.search("wishlist") > -1) {
                    if (value.name == 'Бренд') {
                        var pos = arr_prop.indexOf(value.name);
                        if (pos < 0) {
                            arr_prop.push(value.name);
                            var t_arr = [];
                            t_arr['name'] = value.name;
                            t_arr['sort'] = value.sort;
                            arr_prop_with_sort.push(t_arr);



                        }
                    }
                } else {
                    var pos = arr_prop.indexOf(value.name);
                    if (pos < 0) {
                        arr_prop.push(value.name);
                        var t_arr = [];
                        t_arr['name'] = value.name;
                        t_arr['sort'] = value.sort;
                        arr_prop_with_sort.push(t_arr);
                    }
                }
            }
        }
    });

    arr_prop_with_sort.sort(function(a, b) {
        return a.sort - b.sort
    });
    arr_prop = [];
    for (var i = 0; i < arr_prop_with_sort.length; i++) {
        arr_prop.push(arr_prop_with_sort[i].name);
    }
    arr_full = [];
    arr_prop.forEach((prop) => {
        arr_values = [];
        for (let product of result) {
            let qty = 0;
            if (product.properties_filter != null) {
                let prop_str = jQuery.parseJSON(product.properties_filter);
                for (const [key, value] of Object.entries(prop_str)) {
                    if (prop == value.name) {
                        qty++;
                        var pos = arr_values.indexOf(value.value);
                        if (pos < 0) {
                            arr_values.push(value.value);
                        }
                    }
                }
            }
        }
        item = [];
        item["prop"] = prop;
        item["values"] = arr_values;
        arr_full.push(item);
    });
    for (let product of arr_full) {
        let arr1 = [];
        product["values"].sort();
        for (let val of product["values"]) {
            arr_values = [];
            let qty = 0;
            for (let product of result) {
                if (product.properties_filter != null) {
                    let prop_str = jQuery.parseJSON(product.properties_filter);
                    for (const [key, value] of Object.entries(prop_str)) {
                        if (val == value.value) {
                            qty++;
                        }
                    }
                }
            }
            arr_values["val"] = val;
            arr_values["qty"] = qty;
            arr1.push(arr_values);
        }
        item = [];
        item["prop"] = product["prop"];
        item["rows"] = arr1;
        arr_full_last.push(item);
    }

    var first = true;
    for (let property of arr_full_last) {
        var article = document.createElement("article");
        article.className = "filter-group";
        var header = document.createElement("header");
        header.className = "card-header";
        var a = document.createElement("a");
        if (first) {
            a.className = "title";
        } else {
            a.className = "title collapsed";
        }
        a.setAttribute("data-bs-toggle", "collapse");
        a.setAttribute(
            "href",
            "#Filter" + translit(property.prop) + "Aside_1XXL"
        );
        a.setAttribute("role", "button");
        a.setAttribute("aria-expanded", "false");
        a.setAttribute(
            "aria-controls",
            "#Filter" + translit(property.prop) + "Aside_1XXL"
        );
        var i = document.createElement("i");
        i.className = "icon-control fa fa-chevron-down";
        a.appendChild(i);

        var h6 = document.createElement("div");
        name_header = property.prop;
        name_header = "" + name_header.replace(/___/g, "'") + "";
        name_header = "" + name_header.replace(/dot/g, ".") + "";

        name_header = "" + name_header.replace(/o_parenthesis/g, "(") + "";
        name_header = "" + name_header.replace(/c_parenthesis/g, ")") + "";


        h6.innerText = "" + name_header.replace(/_/g, ' ') + "";

        a.appendChild(h6);
        header.appendChild(a);
        article.appendChild(header);

        var div1 = document.createElement("div");
        if (first) {
            div1.className = "collapse show";
        } else {
            div1.className = "collapse";
        }
        first = false;
        div1.id = "Filter" + translit(property.prop) + "Aside_1XXL";

        var div2 = document.createElement("div");
        div2.className = "d-flex flex-column mb-3";
        var new_arr = [];
        for (let prop_val of property.rows) {
            pr_arr = [];
            let v='1';
            let pr_val = prop_val['val'];
            let position = pr_val.indexOf('___d');
            console.log(position);
            if (position>0){
                //намагаємося відсортувати дюйми
                v = pr_val;
                v = v.replace('___d','d');
                v = v.replace('ForwardSlash','f');

                let position_full_part = v.indexOf('_');
                let position_float_part = v.indexOf('f');
                let full_part = 0;
                if (position_full_part>0){
                    full_part = v.substring(0,position_full_part);
                    full_part = Number(full_part);
                }else if(position_float_part>0){
                    full_part = 0;
                }else{
                    full_part = Number(v.replace('d',''));
                }

                let float_part = 0;
                let numerator = 0;
                let denominator = 0;
                if (position_float_part>0){
                    numerator = v.substring(position_full_part,position_float_part);
                    numerator = numerator.replace('_','');
                    numerator = Number(numerator);
                    denominator = v.substring(position_float_part+1,v.length);
                    denominator = denominator.replace('d','');
                    denominator = Number(denominator);
                }
                if (denominator>0){
                    float_part = numerator/denominator;
                }
                v = full_part+float_part;
            }else if(Number(pr_val)>0){
                let n_pr_val = Number(pr_val);
                v = n_pr_val;
            }
            pr_arr['val'] = prop_val['val'];
            pr_arr['qty'] = prop_val['qty'];
            pr_arr['sort'] = v;
            new_arr.push(pr_arr);
        }
        new_arr.sort(function(a, b) {
            return a.sort - b.sort
        });
        //console.log(new_arr);
        for (let prop_val of new_arr) {
            var div3 = document.createElement("div");
            div3.className = "card-body";
            div3.setAttribute("style", "padding-top: 0.25em !important;padding-bottom: 0.25em !important");
            var input = document.createElement("input");
            input.className = "form-check-input filter-check";
            input.setAttribute("type", "checkbox");
            input.setAttribute("style", "margin-right: 0.5em");
            input.setAttribute("data-prop", property.prop);
            input.setAttribute("data-val", property.prop + '-' + prop_val.val);
            input.id = "flexCheckDefault" + property.prop + '-' + prop_val.val;
            div3.appendChild(input);
            var label = document.createElement("label");
            label.className = "form-check-label";
            label.setAttribute("for", "flexCheckDefault" + property.prop + '-' + prop_val.val);
            var label_name = prop_val.val;
            label_name = label_name.replace(/ForwardSlash/g, "/");
            label_name = label_name.replace(/o_parenthesis/g, "(") + "";
            label_name = label_name.replace(/c_parenthesis/g, ")") + "";
            label_name = label_name.replace(/___d/g, "″");
            label_name = label_name.replace(/___/g, "'");
            label_name = label_name.replace(/_/g, ' ');
            label_name = label_name.replace(/exclamation/g, '!');
            label_name = label_name.replace(/plus/g, '+');
            label_name = label_name.replace(/comma/g, ',');
            label_name = label_name.replace(/dot/g, ".") + "";
            label.innerText = " " + label_name;
            div3.appendChild(label);
            var span = document.createElement("span");
            var b = document.createElement("b");
            b.className = "badge rounded-pill bg-gray-dark float-end filter-qty";
            b.setAttribute("data-prop", property.prop);
            b.setAttribute("data-val", property.prop + '-' + prop_val.val);
            b.setAttribute("style", 'display: none;');
            b.innerText = prop_val.qty;

            var b_load = document.createElement("b");
            b_load.className = "badge rounded-pill float-end filter-qty-load";
            b_load.setAttribute("data-prop", property.prop);
            b_load.setAttribute("data-val", property.prop + '-' + prop_val.val);
            b_load.setAttribute("style", "padding-top: 0px;padding-bottom: 0px;padding-left: 5px;padding-right: 5px;");
            b_load.innerHTML = '<img style="width: 20px;" src="../img/loading.gif" alt="loading">';



            span.appendChild(b);
            span.appendChild(b_load);
            div3.appendChild(span);
            div2.appendChild(div3);
        }
        div1.appendChild(div2);
        article.appendChild(div1);

        var article_lg = article.cloneNode(true);
        var article_m = article.cloneNode(true);

        $("#aside_filter")[0].appendChild(article_lg);
        //$("#card_mobile")[0].appendChild(article_m);
    }
    startFiltersMobile(arr_full_last);
    setFilters();

}

function startFiltersMobile(arr_full_last) {
    var first = true;
    for (let property of arr_full_last) {
        var article = document.createElement("article");
        article.className = "filter-group";
        var header = document.createElement("header");
        header.className = "card-header";
        var a = document.createElement("a");
        if (first) {
            a.className = "title";
        } else {
            a.className = "title collapsed";
        }
        a.setAttribute("data-bs-toggle", "collapse");
        a.setAttribute(
            "href",
            "#Filter" + translit(property.prop) + "Aside_1XXL"
        );
        a.setAttribute("role", "button");
        a.setAttribute("aria-expanded", "false");
        a.setAttribute(
            "aria-controls",
            "#Filter" + translit(property.prop) + "Aside_1XXL"
        );
        var i = document.createElement("i");
        i.className = "icon-control fa fa-chevron-down";
        a.appendChild(i);

        var h6 = document.createElement("div");
        name_header = property.prop;
        name_header = "" + name_header.replace(/___/g, "'") + "";
        name_header = "" + name_header.replace(/dot/g, ".") + "";

        name_header = "" + name_header.replace(/o_parenthesis/g, "(") + "";
        name_header = "" + name_header.replace(/c_parenthesis/g, ")") + "";
        h6.innerText = "" + name_header.replace(/_/g, ' ') + "";
        a.appendChild(h6);
        header.appendChild(a);
        article.appendChild(header);

        var div1 = document.createElement("div");
        if (first) {
            div1.className = "collapse show";
        } else {
            div1.className = "collapse";
        }
        first = false;
        div1.id = "Filter" + translit(property.prop) + "Aside_1XXL";

        var div2 = document.createElement("div");
        div2.className = "d-flex flex-column mb-3";
        for (let prop_val of property.rows) {
            var div3 = document.createElement("div");
            div3.className = "card-body";
            div3.setAttribute("style", "padding-top: 0.25em !important;padding-bottom: 0.25em !important");
            var input = document.createElement("input");
            input.className = "form-check-input filter-check-m";
            input.setAttribute("type", "checkbox");
            input.setAttribute("style", "margin-right: 0.5em");
            input.setAttribute("data-prop", property.prop);
            input.setAttribute("data-val", property.prop + '-' + prop_val.val);
            input.id = "m-flexCheckDefault" + property.prop + '-' + prop_val.val;
            div3.appendChild(input);
            var label = document.createElement("label");
            label.className = "form-check-label";
            label.setAttribute("for", "m-flexCheckDefault" + property.prop + '-' + prop_val.val);
            var label_name = prop_val.val;

            label_name = label_name.replace(/ForwardSlash/g, "/");
            label_name = label_name.replace(/___d/g, "″");
            label_name = label_name.replace(/___/g, "'");
            label_name = label_name.replace(/o_parenthesis/g, "(") + "";
            label_name = label_name.replace(/c_parenthesis/g, ")") + "";
            label_name = label_name.replace(/_/g, ' ');
            label_name = label_name.replace(/exclamation/g, '!');
            label_name = label_name.replace(/plus/g, '+');
            label_name = label_name.replace(/comma/g, ',');
            label_name = label_name.replace(/dot/g, ".") + "";
            label.innerText = " " + label_name;
            div3.appendChild(label);
            var span = document.createElement("span");
            var b = document.createElement("b");
            b.className = "badge rounded-pill bg-gray-dark float-end filter-qty";
            b.setAttribute("data-prop", property.prop);
            b.setAttribute("data-val", property.prop + '-' + prop_val.val);
            b.innerText = prop_val.qty;

            var b_load = document.createElement("b");
            b_load.className = "badge rounded-pill float-end filter-qty-load";
            b_load.setAttribute("data-prop", property.prop);
            b_load.setAttribute("data-val", property.prop + '-' + prop_val.val);
            b_load.setAttribute("style", "padding-top: 0px;padding-bottom: 0px;padding-left: 5px;padding-right: 5px;");
            b_load.innerHTML = '<img style="width: 20px;" src="../img/loading.gif" alt="loading">';

            span.appendChild(b);
            span.appendChild(b_load);
            div3.appendChild(span);
            div2.appendChild(div3);
        }
        div1.appendChild(div2);
        article.appendChild(div1);

        //var article_lg = article.cloneNode(true);
        var article_m = article.cloneNode(true);

        //$("#aside_filter")[0].appendChild(article_lg);
        $("#card_mobile")[0].appendChild(article_m);
    }
}

function setFilters() {
    var json_filter = findGetParameter('filter');
    if (json_filter != null) {
        var last_item_id;
        filter = JSON.parse(json_filter);
        $.each(filter, function(key, value) {
            $.each(value, function(prop_name, prop_val) {
                f = $('[data-prop="' + prop_name + '"]');
                $.each(f, function(index, item) {
                    if (item.dataset.val == prop_val) {
                        if (item.classList.contains('filter-check')) {
                            item.checked = true;
                            last_item_id = item.id;
                        } else if (item.classList.contains('filter-check-m')) {
                            item.checked = true;
                        }
                    }
                });
            });
        });
    } else {
        $('#product_list').show();
        $('#loading').hide();
        $(".filter-qty-load").each(function(index, value) {
            $(this).hide();
        });
        $(".filter-qty").each(function(index, value) {
            $(this).show();
        });
    }
    $(".filter-check").each(function() {
        if ($(this)[0].checked) {
            var block = $(this)[0].parentElement.parentElement.parentElement;
            if (!block.classList.contains('show')) {
                block.classList.add('show');
            }
        }
    });
    $(".filter-check-m").each(function() {
        if ($(this)[0].checked) {
            var block = $(this)[0].parentElement.parentElement.parentElement;
            if (!block.classList.contains('show')) {
                block.classList.add('show');
            }
        }
    });
    $('#' + last_item_id + '').prop('checked', false);
    $('#' + last_item_id + '').trigger('click', [true]);
    f = 2;
}

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function(item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

$(document).on("click", ".filter-check-m", function(e, not_clear_page) {
    $('#' + $(this)[0].id.replace('m-', '') + '').trigger('click', [true]);
});

$(document).on("click", ".filter-check", function(e, not_clear_page) {
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
    let cur_id = $(this)[0].id;
    let checked = $(this).is(':checked');

    if (!not_clear_page) {
        var new_url = UpdateQueryString('page', '1');
        history.pushState({}, null, new_url);
    }

    $('[id="' + cur_id + '"]').each(function() {
        $(this).prop('checked', checked);
    });
    du_it();
});

$(document).on("click", "#only-available", function(e) {
    const url = new URL(window.location); // == window.location.href
    url.searchParams.set('available', $('#only-available').is(':checked'));
    url.searchParams.set('page', 1);
    $('#only-available-m').prop('checked', $('#only-available').is(':checked'));
    history.pushState(null, null, url);

    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
    getDataFilter();
    du_it();
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
});

$(document).on("click", "#only-available-m", function(e) {
    const url = new URL(window.location); // == window.location.href
    url.searchParams.set('available', $('#only-available-m').is(':checked'));
    url.searchParams.set('page', 1);
    $('#only-available').prop('checked', $('#only-available-m').is(':checked'));
    history.pushState(null, null, url);
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
    getDataFilter();
    du_it();
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
});

function du_it() {
    $('#product_list').hide();
    $('#loading').show();
    var checked_prop = [];

    $(".filter-check:checked").each(function(index, value) {
        var prop = $(this).data("prop");
        var val = $(this).data("val");
        let item = [];
        item["prop"] = prop;
        item["val"] = val;
        checked_prop.push(item);
    });

    var checked_prop_full = [];
    var temp_arr = [];

    for (var i = 0; i < checked_prop.length; i++) {
        var pos = temp_arr.indexOf(checked_prop[i].prop);
        if (pos < 0) {
            temp_arr.push(checked_prop[i].prop);
            let item_arr = [];
            let val_arr = [];
            item_arr['prop'] = checked_prop[i].prop;
            item_arr['val'] = val_arr;
            checked_prop_full.push(item_arr);

        }
    };
    for (var i = 0; i < checked_prop_full.length; i++) {
        for (var j = 0; j < checked_prop.length; j++) {
            if (checked_prop_full[i].prop === checked_prop[j].prop) {
                checked_prop_full[i].val.push(checked_prop[j].val);
            }
        }
    }
    //Запам'ятаємо для локації
    var filters = $(".filter-check:checked").map(function() {
        var opt = {};
        opt[$(this).data("prop")] = $(this).data("val");
        return opt;
    }).get();


    var json_filters = JSON.stringify(filters);

    if (filters.length != 0) {
        var new_url = UpdateQueryString('filter', json_filters);
    } else {
        const url = new URL(document.location);
        const searchParams = url.searchParams;
        searchParams.delete("filter");
        var new_url = url.toString();
    }

    history.pushState({}, null, new_url);

    var result = jQuery.parseJSON(jQuery("#filter_json").val());
    let products_id = [];

    var result = jQuery.parseJSON(jQuery("#filter_json").val());
    let arr_prop = [];
    result.forEach((product) => {
        if (product.properties_filter != null) {
            var item = [];
            item["id"] = product.id;
            item["available"] = product.available;
            let prop = jQuery.parseJSON(product.properties_filter);

            for (const [key, value] of Object.entries(prop)) {
                item["" + value.name + ""] = value.value;
            }
            arr_prop.push(item);
        }
    });

    const needed_id = [];
    for (var i = 0; i < arr_prop.length; i++) { //перебираємо товари
        let product_needed = 1; //товар потрібен за промовчанням
        let needed_arr = [];
        for (var j = 0; j < checked_prop_full.length; j++) { //Перебираємо групи властивостей
            //заходимо в групу тут повинні застосувати умову "АБО"
            let current_prop_value = arr_prop[i][checked_prop_full[j].prop];

            let needed_in_group = 0; //товар потрібен в групі
            let inner_arr = checked_prop_full[j].val;
            for (l = 0; l < inner_arr.length; l++) { //Перебираємо властивості
                //let needed_in_group = 0;
                if (inner_arr[l].replace(inner_arr[l].split('-')[0] + '-', '') == current_prop_value) {
                    needed_in_group = 1;
                }
                //needed_arr.push(needed);
            }
            needed_arr.push(needed_in_group);
        }
        product_needed = 1;
        for (var k = 0; k < needed_arr.length; k++) {
            if (needed_arr[k] === 0) {
                product_needed = 0;
            }
        }
        if (product_needed === 1) {
            needed_id.push(arr_prop[i]);
        }
    }
    const request_id = [];
    for (let i = 0; i < needed_id.length; i++) {
        request_id.push(needed_id[i].id);
    }
    window.request_id = request_id;
    getDataFilter($(this).data("prop"), $(this)[0].checked);

};

function UpdateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
        hash;

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null) {
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        } else {
            hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                url += '#' + hash[1];
            }
            return url;
        }
    } else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                url += '#' + hash[1];
            }
            return url;
        } else {
            return url;
        }
    }
}

function calculateCountProductsInFilter(prop, checked) {
    console.log(prop);
    var result = jQuery.parseJSON(jQuery("#filtered_products_json").val());
    let arr_prop = [];
    result.forEach((product) => {
        if (product.properties_filter != null) {
            var item = [];
            item["id"] = product.id;
            let prop = jQuery.parseJSON(product.properties_filter);

            for (const [key, value] of Object.entries(prop)) {
                item["" + value.name + ""] = value.value;
            }
            arr_prop.push(item);
        }
    });
    arr_prop_filtered = arr_prop;
    $(".filter-qty").each(function(index, value) {
        var data_prop = $(this).data("prop");
        var val = $(this).data("val");

        if (prop != data_prop || !checked) {
            let count = 0;
            for (i = 0; i < arr_prop_filtered.length; i++) {
                if (arr_prop_filtered[i][data_prop] ==
                    val.replace(val.split('-')[0] + '-', '')) {
                    //val.split('-')[1]) {
                    count++;
                }
            }
            $(this)[0].innerText = count;
            $(this).show();
        }
    });
    $(".filter-qty-load").each(function(index, value) {

        $(this).hide();

    });

}

$(document).on("click", "#clean_filters", function(e) {
    $(".filter-check:checked").each(function(index, value) {
        $(this).prop('checked', false);
    });
    var new_url = UpdateQueryString('page', '1');
    history.pushState({}, null, new_url);
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
    du_it();
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
})

$(document).on("click", "#clean_filters-m", function(e) {
    $(".filter-check:checked").each(function(index, value) {
        $(this).prop('checked', false);
    });
    $(".filter-check-m:checked").each(function(index, value) {
        $(this).prop('checked', false);
    });
    var new_url = UpdateQueryString('page', '1');
    history.pushState({}, null, new_url);
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
    du_it();
    $(".filter-qty-load").each(function(index, value) {
        $(this).show();
    });
    $(".filter-qty").each(function(index, value) {
        $(this).hide();
    });
})

function getDataFilter(prop, checked) {
    if (window.request_id.length == 0) {
        window.request_id.push(999999999)
    }
    var q = findGetParameter('q');
    if (q === null) {
        var search = '';
    } else {
        var search = '&q=' + q;
    }

    var page = findGetParameter('page');
    if (page === null) {
        var page_q = '';
    } else {
        var page_q = '&page=' + page;
    }
    $.ajax({
            url: '?ids=' + window.request_id + search + '&available=' + $('#only-available').is(':checked') + page_q,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $("#products_wrap").empty().html(data);
            replaceSpace();
            calculateCountProductsInFilter(prop, checked);
            setFilterToPages();
            $('#product_list').show();
            $('#loading').hide();
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
}

function setFilterToPages() {


    $('.page-link').each(
        function(index) {
            var q = findGetParameter('q');
            var filter = findGetParameter('filter');
            if (filter === null) {
                var filter_q = '';
            } else {
                var filter_q = '&filter=' + filter;
            }
            if (q === null) {
                var q = '';
            } else {
                var q = '&q=' + q;
            }

            cur_href = $(this).attr("href");
            $(this).attr("href", cur_href + filter_q + '&available=' + $('#only-available').is(':checked')+q);
        }
    )
}

$(document).on('change', '#sort-select', function() {
    console.log($(this).val());
    var new_url = UpdateQueryString('sort', $(this).val());

    history.pushState({}, null, new_url);
    location.reload();
    // history.pushState({}, null, new_url);
    // get = new String(window.location);
    // x = get.indexOf('?');
    // l = get.length;
    // get = get.substr(x + 1, l - x);
    // f = 2;
    // $.ajax({
    //         url: '?' + get,
    //         type: "get",
    //         datatype: "html"
    //     })
    //     .done(function(data) {
    //         location.reload();
    //         // $("#products_wrap").empty().html(data);

    //         // setFilters();
    //         // setFilterToPages();

    //         //calculateCountProductsInFilter(prop, checked);
    //     })
    //     .fail(function(jqXHR, ajaxOptions, thrownError) {
    //         alert('No response from server');
    //     });
    // f = 4;
})

$(document).on('change', '#count-select', function(){
    var new_url = UpdateQueryString('count', $(this).val());
    history.pushState({}, null, new_url);
    location.reload();
})

$(document).on('change', '#m-count-select', function(){
    var new_url = UpdateQueryString('count', $(this).val());
    history.pushState({}, null, new_url);
    location.reload();
})

$(document).on('change', '#sort-select-m', function() {
    console.log($(this).val());
    var new_url = UpdateQueryString('sort', $(this).val());

    history.pushState({}, null, new_url);
    location.reload();
    // get = new String(window.location);
    // x = get.indexOf('?');
    // l = get.length;
    // get = get.substr(x + 1, l - x);
    // f = 2;
    // $.ajax({
    //         url: '?' + get,
    //         type: "get",
    //         datatype: "html"
    //     })
    //     .done(function(data) {
    //         location.reload();
    //         // $("#products_wrap").empty().html(data);
    //         // setFilters();
    //         // setFilterToPages();

    //         //calculateCountProductsInFilter(prop, checked);
    //     })
    //     .fail(function(jqXHR, ajaxOptions, thrownError) {
    //         alert('No response from server');
    //     });
})

$(document).on('click', '#hide-price', function() {
    if ($(this).hasClass('btn-primary')) {
        hide = 1;
    } else {
        hide = 0;
    }
    var new_url = UpdateQueryString('hide_price', hide);

    history.pushState({}, null, new_url);
    get = new String(window.location);
    x = get.indexOf('?');
    l = get.length;
    get = get.substr(x + 1, l - x);
    let btn = $(this);
    btn.toggleClass('btn-primary');
    btn.toggleClass('btn-outline-primary');
    $.ajax({
            url: '?' + get,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $("#products_wrap").empty().html(data);
            startFilters();
            setFilterToPages();
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
})

$(document).on('click', '#hide-price-m', function() {
    console.log($(this).val());
    if ($(this).hasClass('btn-primary')) {
        hide = 1;
    } else {
        hide = 0;
    }
    var new_url = UpdateQueryString('hide_price', hide);

    history.pushState({}, null, new_url);
    get = new String(window.location);
    x = get.indexOf('?');
    l = get.length;
    get = get.substr(x + 1, l - x);
    let btn = $(this);
    btn.toggleClass('btn-primary');
    btn.toggleClass('btn-outline-primary');
    $.ajax({
            url: '?' + get,
            type: "get",
            datatype: "html"
        })
        .done(function(data) {
            $("#products_wrap").empty().html(data);
            startFilters();
            setFilterToPages();
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
})

function translit(word) {
    var answer = "";
    var converter = {
        а: "a",
        б: "b",
        в: "v",
        г: "g",
        д: "d",
        е: "e",
        ё: "e",
        ж: "zh",
        з: "z",
        и: "i",
        й: "y",
        к: "k",
        л: "l",
        м: "m",
        н: "n",
        о: "o",
        п: "p",
        р: "r",
        с: "s",
        т: "t",
        у: "u",
        ф: "f",
        х: "h",
        ц: "c",
        ч: "ch",
        ш: "sh",
        щ: "sch",
        ь: "",
        ы: "y",
        ъ: "",
        э: "e",
        ю: "yu",
        я: "ya",

        А: "A",
        Б: "B",
        В: "V",
        Г: "G",
        Д: "D",
        Е: "E",
        Ё: "E",
        Ж: "Zh",
        З: "Z",
        И: "I",
        Й: "Y",
        К: "K",
        Л: "L",
        М: "M",
        Н: "N",
        О: "O",
        П: "P",
        Р: "R",
        С: "S",
        Т: "T",
        У: "U",
        Ф: "F",
        Х: "H",
        Ц: "C",
        Ч: "Ch",
        Ш: "Sh",
        Щ: "Sch",
        Ь: "",
        Ы: "Y",
        Ъ: "",
        Э: "E",
        Ю: "Yu",
        Я: "Ya",
        " ": "_",
        ".": "",
        "(": "",
        ")": "",
    };

    for (var i = 0; i < word.length; ++i) {
        if (converter[word[i]] == undefined) {
            answer += word[i];
        } else {
            answer += converter[word[i]];
        }
    }

    return answer;
}

jQuery(document).ready(function($) {

    //Use this inside your document ready jQuery
    // $(window).on('popstate', function() {
    //     location.reload(true);
    // });

});
