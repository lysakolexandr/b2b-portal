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
// $host = "127.0.0.1";
// $db_name = 'rotting_mysql';
// $db_database = 'rotting_lavka';
// $db_pass = 'DatumSoft1C';

$connection = new PDO("mysql:host=".$host.";dbname=".$db_database."", $db_name, $db_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$customer_code = Auth::user()->customer_code;
$user_code = Auth::user()->user_code;

$personal_price_id = App\Models\UserSettings::where(['customer_id' => Auth::user()->customer_code])->first()->price_type_id;

$result = $connection->query('SELECT DISTINCT

pfp.product_id AS product_id,
pfp.brand AS brand,
pfp.name_ua AS name_ua,
pfp.name_ru AS name_ru,
pfp.description_ua AS description_ua,

pfp.article AS article,
pfp.barcode AS barcode,
pfp.vendor_barcode AS vendor_barcode,
pfp.vendor_code AS vendor_code,
pfp.category_id AS category_id,
pfp.description_ru AS description_ru,
products_price.price AS price,
pfp.country_ua AS country_ua,
pfp.country_ru AS country_ru,
pfp.country_reg_ua AS country_reg_ua,
pfp.country_reg_ru AS country_reg_ru,
pfp.pictures AS pictures,
pfp.pictures_json AS pictures_json,
pfp.param_ua AS param_ua,
pfp.param_ru AS param_ru,
pfp.display_balances AS display_balances,
stocks_qty.qty AS qty,

product_in_pricelists.product_id AS p_id,
`personal_prices`.price AS personal_price,
`retail_prices`.price AS retail_price,
`personal_prices`.currency_id AS personal_currency_id
FROM product_in_pricelists

left join (select * from product_for_pricelists) AS pfp ON product_in_pricelists.product_id = pfp.product_id

left join (select price, id  from products) AS products_price ON product_in_pricelists.product_id = products_price.id

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
$currencies = $connection->query('SELECT * FROM currencies');
?>
<yml_catalog date="{{date("Y-m-d h:i")}}">
    <shop>
        <currencies>
            <currency id="UAH" rate="1"/>
            <?php while ($curr = $currencies->fetch(PDO::FETCH_ASSOC)): ?>
                @if ($curr['code']!='грн')
                <currency id="{{$curr['code']}}" rate="{{$curr['rate']}}"/>
                @endif
            <?php endwhile ?>
        </currencies>
        <categories>
            @foreach ($categories_xml as $category)
            <category id="{{$category['_attributes']['id']}}" parentId="{{$category['_attributes']['parentId']}}" portal_id="{{$category['_attributes']['id']}}">{!!$category['_value']!!}</category>
            @endforeach
        </categories>

        <offers>
            <?php while ($item = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <?php
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
            $personal_currency = '';
            if ($item['personal_currency_id']=='980') {
                $personal_currency = 'UAH';
            }else if ($item['personal_currency_id']=='840'){
                $personal_currency = 'USD';
            }else if ($item['personal_currency_id']=='978'){
                $personal_currency = 'EUR';
            };

            ?>
                <offer id="{{ $item['p_id'] }}" available="{{$available}}">

                    <currencyId>UAH</currencyId>
                    <article>{{ $item['article'] }}</article>
                    <barcode>{{ $item['barcode'] }}</barcode>

                    @if ($item['vendor_code']!='' &&$item['vendor_code']!=null)
                    <vendorCode>{{$item['vendor_code']}}</vendorCode>
                    @endif

                    <categoryId>{{ $item['category_id'] }}</categoryId>
                    <price>{{ number_format($item['retail_price'],2,'.','') }}</price>
                    <vendor>{{ $item['brand'] }}</vendor>
                    @if ((Auth::user()->price_view != 0 && Auth::user()->trusted==1) || Auth::user()->trusted==0)
                    <personal_price>{{ number_format($individual_price,2,'.','') }}</personal_price>
                    <personal_currency>{!!$personal_currency  !!}</personal_currency>
                    @endif
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
