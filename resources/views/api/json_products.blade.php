<?php
$host = "31.134.122.218";
        $db_name = 'New_B2b_v1';
        $db_database = 'new_b2b_v1';
        $db_pass = 'MdTF6nr863yhPWze';

        // $host = "127.0.0.1";
        // $db_name = 'rotting_mysql';
        // $db_database = 'rotting_lavka';
        // $db_pass = 'DatumSoft1C';

        $customer_code = Auth::user()->customer_code;
        $user_code = Auth::user()->user_code;
        $connection = new PDO("mysql:host=" . $host . ";dbname=" . $db_database . "", $db_name, $db_pass);
        $personal_price_id = App\Models\UserSettings::where(['customer_id' => Auth::user()->customer_code])->first()->price_type_id;
        $stock_group_id = Auth::user()->stock_group_id;
        $resultPDO = $connection->query('SELECT *,
        prod_table.id AS p_id,
        products.name_ua AS p_name,
        products.name_ru AS p_name_ru,
        IFNULL(`personal_prices`.price,0) AS personal_price,
        IFNULL(`retail_prices`.price,0) AS retail_price,
        `personal_prices`.currency_id AS personal_currency_id,
        IFNULL(`stocks_qty`.qty,0) AS qty,
        IFNULL(`main_stocks_qty`.qty,0) AS main_qty,
        products.brand AS brand,
        products.brand AS brand_ru,

        prod_table.properties AS properties_ua,
		prod_table.properties_ru AS properties_ru,

        prod_table.weight AS weight,
        prod_table.volume AS volume,

        `categories`.name AS category_name,
        `categories`.name_ru AS category_name_ru
        FROM product_for_pricelists AS products

        left join (SELECT product_id, qty FROM products_in_stock_groups where stock_group_id = ' . $stock_group_id . ') AS stocks_qty
        ON products.product_id = stocks_qty.product_id

        left join (SELECT id, properties, properties_ru, weight, volume FROM products) AS prod_table
        ON products.product_id = prod_table.id

        left join (SELECT product_id, qty FROM products_in_stock_groups where stock_group_id = 41) AS main_stocks_qty
        ON products.product_id = main_stocks_qty.product_id

        left join (SELECT id, name, name_ru FROM categories) AS categories
        ON products.category_id = categories.id
        LEFT JOIN (
            SELECT
                        product_id,
                        price,
                        currency_id
                    FROM
                    product_prices
                    WHERE price_type_id = ' . $personal_price_id . '
            ) AS personal_prices
            ON products.product_id = personal_prices.product_id
            LEFT JOIN (
            SELECT
                        product_id,
                        price,
                        currency_id
                    FROM
                    product_prices
                    WHERE price_type_id = 2
            ) AS retail_prices
            ON products.product_id = retail_prices.product_id;');

        $first = true;
        echo '[';
        while ($item = $resultPDO->fetch(PDO::FETCH_ASSOC)) :
        if (!$first){
            echo ',';
        };
        $first = false;
        $result_price = $connection->query('SELECT price, ipt.price_type_id, product_id, currency_id
            FROM product_prices
            LEFT JOIN (select individual_price_types.price_type_id as price_type_id from individual_price_types
            where product_id = '.$item['p_id'].'
            AND customer_id = '.$user_code.')
            AS ipt ON  product_prices.price_type_id =  ipt.price_type_id
            WHERE product_id = '.$item['p_id'].'
            AND ipt.price_type_id Is NOT null');
            $individual_price = $item['personal_price'];
            while ($price = $result_price->fetch(PDO::FETCH_ASSOC)) :
                $individual_price = $price['price'];
            endwhile;
echo '{';
        ?>


    "id": {{$item['p_id']}},
    @if ($lang=='ua')
    "name": "{{$item['p_name']}}",
    "brand": "{{$item['brand']}}",
    @else
    "name": "{{$item['p_name_ru']}}",
    "brand": "{{$item['brand_ru']}}",
    @endif
    "article": "{{$item['article']}}",
    "barcode": "{{$item['barcode']}}",
    "vendor_code": "{{$item['vendor_code']}}",
    @if ($lang=='ua')
    "country": "{{$item['country_ua']}}",
    "country_of_brand_registration": "{{$item['country_reg_ua']}}",

    "description": "{{$item['description_ua']}}",
    "category": { "id": {{$item['category_id']}}, "name": "{{$item['category_name']}}"},
    @else
    "country": "{{$item['country_ru']}}",
    "country_of_brand_registration": "{{$item['country_reg_ru']}}",

    "description": "{{$item['description_ru']}}",
    "category": { "id": {{$item['category_id']}}, "name": "{{$item['category_name_ru']}}"},
    @endif


    "price": {{number_format($item['retail_price'],2,'.','')}},
    "price_currency": "UAH",
    "personal_price": {{number_format($individual_price,2,'.','')}},
    @if ($item['personal_currency_id']==840)
    "personal_currency": "USD",
    @elseif ($item['personal_currency_id']==978)
    "personal_currency": "EUR",
    @else
    "personal_currency": "UAH",
    @endif

    "gallery":[{!!$item['pictures_json']!!}
        <?
        // $path = 'public/img/products/' . $item['p_id'] . '/';
        // $files = Storage::files($path);
        // $first_img = true;
        // foreach ($files as $img) {
        //     $img_str = str_replace('public','new.b2b-sklad.com/storage',$img);
        //     if (!$first_img){
        //         echo ',"'.$img_str.'"';
        //     }else{
        //         echo '"'.$img_str.'"';
        //     }
        //     $first_img = false;
        // }


        ?>

    ],
<?php
$qty = 0;
if ($item['qty']>$item['display_balances']){
    $qty = $item['display_balances'];
}else{
    $qty = $item['qty'];
};
$main_qty = 0;
if ($item['main_qty']>$item['display_balances']){
    $main_qty = $item['display_balances'];
}else{
    $main_qty = $item['main_qty'];
}
 ?>
    "local_stock": {{$qty}},
    "center_stock": {{$main_qty}},
    @if ($item['properties_ua']!=null)

    @if ($lang=='ua')
    "properties": {!!$item['properties_ua']!!},
    @else
        "properties": {!!$item['properties_ru']!!},
    @endif
    @endif
    "weight": {!!$item['weight']!!},
    "volume": {!!$item['volume']!!}

        <?
        echo '}';
        endwhile;
        echo ']';
        ?>
