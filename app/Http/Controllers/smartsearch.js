$(document).ready(function () {
    $('#searchbox').on('input', function () {

        var query = this.value;
        var url = search_url;
        if (query.length>3){
            $.ajax({
                url: url,
                type: "get",
                datatype: "html",
                data: {
                    q: query,
                }
            }).done(function(res){
                console.log(res.data);
                $('.autocomplete').empty();
                if (res.data.length>0){
                    res.data.forEach(element => {
                        console.log(element);
                        let item_html = '<div class="item type-'+element.class+'" data-text="Lenovo">'+
                        '<img src="'+element.picture+'">'+
                        '<a href="'+element.url+'">'+

                        '('+element.code+') '+element.name+'<span class="type"></span></a></div>';
                        $('.autocomplete').append(
                            $(item_html)
                          );
                    });
                    $('autocomplite').append('<div class="all-categories"><a onclick="$(this).parents(&quot;form:first&quot;).submit();" href="javascript:void(0);">Всі результати</a></div>');
                    $('.autocomplete').show();
                    $('.padding-y-sm').addClass('hover');
                }else{
                    $('.autocomplete').hide();
                    $('.padding-y-sm').removeClass('hover');
                    $('.autocomplete').empty();
                }
            });
        }else{

                $('.autocomplete').hide();
                $('.padding-y-sm').removeClass('hover');
                $('.autocomplete').empty();
        }
    });
});
