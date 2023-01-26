<aside class="col-lg-3">
    <div class="card">
        <div class="card-body">
            <input type="text" class="form-control" id="price_name" value="{{ $price->name }}">
            <div class="form-text b text-center">{{ __('l.input_name_of_price') }}</div>
            <div class="d-grid gap-2">
                <a class="btn btn-outline-primary mt-2" id="save-price-xls">{{ __('l.save_price') }}</a>
            </div>
        </div>
    </div>
</aside>
<main class="col-lg-9">
    <article class="card mb-3">
        <div class="card-body">
            <div id="treeview-{{ $price->id }}" class=""></div>
        </div>
    </article>
</main>

<script type="text/javascript">
    $('#price_name').on('change', function() {
        $.ajax({
            url: '/prices/save-name?price_id=' + {{ $price->id }} + '&name=' + this.value,
            type: "get",
            datatype: "html"
        });
    });

    $('#save-price-xls').on('click', function() {
        $.ajax({
            url: '/prices/save?price_id=' + {{ $price->id }},
            type: "get",
            datatype: "html"
        }).done(function() {window.location.href = './prices-xls';});
    });

    $(function() {

        $.ajax({
                url: '/tree-category?price_id=' + {{ $price->id }},
                type: "get",
                datatype: "html"
            })
            .done(function(data) {
                $('#treeview-{{ $price->id }}').treeview({
                    data: data,
                    levels: 1,
                    expandIcon: 'fas fa-folder-plus',
                    collapseIcon: 'fas fa-folder-minus',
                    checkedIcon: 'fas fa-check-square',
                    uncheckedIcon: 'fas fa-square',
                    showCheckbox: true,
                    multiSelect: true,
                    onNodeChecked: function(event, dat) {

                        var nodes_to_ajax = [];
                        nodes_to_ajax.push(dat['id']);
                        var parent = $('#treeview-{{ $price->id }}').treeview('getParent', dat);
                        var parent_parent = $('#treeview-{{ $price->id }}').treeview('getParent', parent);
                        if (parent['id']!=undefined){
                            nodes_to_ajax.push(parent['id']);
                            $('#treeview-{{ $price->id }}').treeview('checkNode',
                                    [
                                        parent, {
                                            silent: true
                                        }
                                    ]);
                        }
                        if (parent_parent['id']!=undefined){
                            nodes_to_ajax.push(parent_parent['id']);
                            $('#treeview-{{ $price->id }}').treeview('checkNode',
                                    [
                                        parent_parent, {
                                            silent: true
                                        }
                                    ]);
                        }
                        if (dat.nodes != undefined) {
                            dat.nodes.forEach(element => {
                                var node = [];
                                nodes_to_ajax.push(element['id']);
                                $('#treeview-{{ $price->id }}').treeview('checkNode',
                                    [
                                        element['nodeId'], {
                                            silent: true
                                        }
                                    ]);
                                if (element.nodes != undefined) {
                                    element.nodes.forEach(brand => {
                                        $('#treeview-{{ $price->id }}')
                                            .treeview('checkNode',
                                                [
                                                    brand['nodeId'], {
                                                        silent: true
                                                    }
                                                ]);
                                        nodes_to_ajax.push(brand['id']);
                                    });
                                };
                            });
                        };

                        $.ajax({
                            url: '/price/check-category?price_id=' +
                                {{ $price->id }} + '&category_id=' +
                                encodeURIComponent(JSON.stringify(nodes_to_ajax)) +
                                '&check=1',
                            type: 'get',
                            datatype: 'html'
                        });

                    },
                    onNodeUnchecked: function(event, dat) {
                        var nodes_to_ajax = [];
                        nodes_to_ajax.push(dat['id']);
                        if (dat.nodes != undefined) {
                            dat.nodes.forEach(element => {
                                nodes_to_ajax.push(element['id']);
                                $('#treeview-{{ $price->id }}').treeview(
                                    'uncheckNode',
                                    [
                                        element['nodeId'], {
                                            silent: true
                                        }
                                    ]);
                                if (element.nodes != undefined) {
                                    element.nodes.forEach(brand => {
                                        $('#treeview-{{ $price->id }}')
                                            .treeview('uncheckNode',
                                                [
                                                    brand['nodeId'], {
                                                        silent: true
                                                    }
                                                ]);
                                        nodes_to_ajax.push(brand['id']);
                                    });
                                };
                            });

                        };

                        $.ajax({
                            url: '/price/check-category?price_id=' +
                                {{ $price->id }} + '&category_id=' +
                                encodeURIComponent(JSON.stringify(
                                    nodes_to_ajax)) +
                                '&check=0',
                            type: 'get',
                            datatype: 'html'
                        });

                    },

                });
            });
    });
</script>
