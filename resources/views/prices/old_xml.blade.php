<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">

<?php
$i = 0;
?>
<?php
$host = "31.134.122.218";
$db_name = 'New_B2b_v1';
$db_database = 'new_b2b_v1';
$db_pass = 'MdTF6nr863yhPWze';
$connection = new PDO("mysql:host=".$host.";dbname=".$db_database."", $db_name, $db_pass);
$personal_price_id = App\Models\UserSettings::where(['customer_id' => Auth::user()->customer_code])->first()->price_type_id;

$result = $connection->query('SELECT *,
product_in_pricelists.product_id AS p_id,
`personal_prices`.price AS personal_price,
`retail_prices`.price AS retail_price,
`personal_prices`.currency_id AS personal_currency_id
FROM product_in_pricelists
left join (SELECT product_id, qty FROM products_in_stock_groups where stock_group_id = '.$stock_group_id.') AS stocks_qty
ON product_in_pricelists.product_id = stocks_qty.product_id
LEFT JOIN (
	SELECT
                product_id,
                price,
                currency_id
            FROM
            product_prices
            WHERE price_type_id = '.$personal_price_id.'
	) AS personal_prices
	ON product_in_pricelists.product_id = personal_prices.product_id
    LEFT JOIN (
	SELECT
                product_id,
                price,
                currency_id
            FROM
            product_prices
            WHERE price_type_id = 2
	) AS retail_prices
	ON product_in_pricelists.product_id = retail_prices.product_id
where price_id='.$id.';');
?>
<yml_catalog date="{{date("Y-m-d h:i")}}">
    <shop>
        <currencies>
            <currency id="UAH" rate="1"/>
            </currencies>
        <categories>
            @foreach ($categories_xml as $category)
            <category id="{{$category['_attributes']['id']}}" parentId="{{$category['_attributes']['parentId']}}" portal_id="{{$category['_attributes']['id']}}">{!!$category['_value']!!}</category>
            @endforeach
        </categories>

        <offers>
            <?php while ($item = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <?php

            if ($item['display_balances']  < $item['qty'] )
            {
                $qty = '' . number_format($item['display_balances'], 0, '.', '');
            }else{
                $qty = '' . number_format($item['qty'], 0, '.', '');
            }
            if ($qty>0) {
                $available = 'true';
            } else {
                if ($prom==1){
                    $available = '';
                }else{
                    $available = 'false';
                }
            }

            ?>
                <offer id="{{ $item['p_id'] }}" available="{{$available}}">

                    <currencyId>UAH</currencyId>
                    <article>{{ $item['article'] }}</article>
                    <barcode>{{ $item['barcode'] }}</barcode>

                    @if ($item['vendor_code']!='' &&$item['vendor_code']!=null)
                    <vendorCode>{{$item['vendor_code']}}</vendorCode>
                    @endif

                    <categoryId>{{ $item['category_id'] }}</categoryId>
                    <price>{{ $item['retail_price'] }}</price>
                    <vendor>{{ $item['brand'] }}</vendor>
                    <personal_price>{{ $item['personal_price'] }}</personal_price>
                    <personal_currency>{!!$item['personal_currency_id']  !!}</personal_currency>
                    <stock_quantity>{!! $qty!!}</stock_quantity>
                    @if ($lang == 'ua')
                    <?php
                    $name = str_replace("&","&amp;",$item['name_ua']);
                    $name = str_replace("`","&apos;",$name);

                    $description = str_replace("&","&amp;",$item['description_ua']);
                    $description = str_replace("`","&apos;",$description);
                    ?>
                        <name>{!! $name !!}</name>
                        <description>
                            <![CDATA[{!! $description !!}]]>
                        </description>
                        {!! str_replace("`","&apos;",$item['param_ua']) !!}
                    @else
                    <?php
                    $name = str_replace("&","&amp;",$item['name_ru']);
                    $name = str_replace("`","&apos;",$name);

                    $description = str_replace("&","&amp;",$item['description_ru']);
                    $description = str_replace("`","&apos;",$description);
                    ?>

<name>{!! $name !!}</name>
<description>
    <![CDATA[{!! $description !!}]]>
</description>
                        {!! str_replace("`","&apos;",$item['param_ru']) !!}
                    @endif
                    {!! $item['pictures'] !!}
                </offer>
            <?php endwhile ?>
        </offers>
    </shop>
</yml_catalog>
