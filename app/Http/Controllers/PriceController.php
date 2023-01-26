<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;

//use Maatwebsite\Excel\Facades\Excel;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\Currency;
use App\Models\IndividualPriceType;
use App\Models\Price;
use App\Models\PriceCategories;
use App\Models\Product;
use App\Models\ProductForPricelist;
use App\Models\ProductInPricelist;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use Spatie\ArrayToXml\ArrayToXml;
use XMLWriter;



class PriceController extends Controller
{
    public static function downloadXlsTest($id, $lang, $all)
    {



        $price_model = Price::where(['id' => $id])->first();
        //if ($price_model->all == 1) {
        //    Auth::loginUsingId(2);
        //} else {
        Auth::loginUsingId($price_model->user_id);
        //}

        $price_type_id = UserSettings::where(['customer_id' => Auth::user()->customer_code])->first()->price_type_id;


        $host = "31.134.122.218";
        $db_name = 'New_B2b_v1';
        $db_database = 'new_b2b_v1';
        $db_pass = 'MdTF6nr863yhPWze';
        // $host = "127.0.0.1";
        // $db_name = 'rotting_mysql';
        // $db_database = 'rotting_lavka';
        // $db_pass = 'DatumSoft1C';
        $connection = new PDO("mysql:host=" . $host . ";dbname=" . $db_database . "", $db_name, $db_pass);

        $stock_group_id = Auth::user()->stock_group_id;
        $result = $connection->query('SELECT
            pfp.name_ua,
            pfp.name_ru,
            pfp.article,
            pfp.brand,
            pfp.barcode,
            `product_in_pricelists`.product_id AS p_id,
            pfp.category_id,
            pfp.price,
            `prices`.price AS personal_price,
            `prices`.currency_id AS currency_id,
            `cat`.name_ua AS category_ua,
	        `cat`.name_ru AS category_ru,
            `stocks_qty`.qty,
            `prod`.p_code AS p_code,
            `prod`.display_balances AS display_balances,
            `stocks_qty_main`.qty AS main_qty
        FROM product_in_pricelists

        LEFT JOIN (SELECT * FROM product_for_pricelists) AS pfp ON product_in_pricelists.product_id = pfp.product_id

        left JOIN
            (SELECT
                product_id, qty
            FROM products_in_stock_groups
            where stock_group_id = ' . $stock_group_id . ') AS stocks_qty
            ON product_in_pricelists.product_id = stocks_qty.product_id
        left JOIN
            (SELECT
                product_id, qty
            FROM products_in_stock_groups
            where stock_group_id = 41) AS stocks_qty_main
            ON product_in_pricelists.product_id = stocks_qty_main.product_id

        LEFT JOIN
            (SELECT
                product_id,
                price,
                currency_id
            FROM
            product_prices
            WHERE price_type_id = ' . $price_type_id . ') AS prices
            ON product_in_pricelists.product_id = prices.product_id

            LEFT JOIN (SELECT id, `categories`.`name` AS name_ua, `categories`.name_ru AS name_ru  FROM categories) AS cat ON pfp.category_id=cat.id

            LEFT JOIN (SELECT id, `products`.`code` AS p_code, `products`.display_balances AS display_balances  FROM products) AS prod ON pfp.product_id=prod.id

        where price_id=' . $id . ' and `product_in_pricelists`.product_id = 1154');
        $offers = [];
        $user = Auth::user();

        $usd = Currency::where(['id' => '840'])->first();
        if ($usd != null) {
            $usd = $usd->rate;
        } else {
            $usd = 0;
        }
        $eur = Currency::where(['id' => '978'])->first();
        if ($eur != null) {
            $eur = $eur->rate;
        } else {
            $eur = 0;
        }
        $currency_arr = ['', '', '', 'USD: ' . $usd . ' EUR: ' . $eur];

        while ($product = $result->fetch(PDO::FETCH_ASSOC)) :
            //dd($product);
            if ($lang == 'ua') {
                $name = $product['name_ua'];
                $category = $product['category_ua'];
            } else {
                $name = $product['name_ru'];
                $category = $product['category_ru'];
            }
            if ($product['qty'] == 0) {
                $available = '-';
            } else {
                $available = '+';
            }
            $currency = '';
            if ($product['currency_id'] == 840) {
                $currency = 'USD';
            } elseif ($product['currency_id'] == 978) {
                $currency = 'EUR';
            } elseif ($product['currency_id'] == 980) {
                $currency = 'UAH';
            };
            $qty = 0;
            $main_qty = 0;
            if ($product['display_balances'] < $product['qty']) {
                $qty = '>' . $product['display_balances'];
            } else {
                $qty = $product['qty'];
            }
            if ($product['display_balances'] < $product['main_qty']) {
                $main_qty = '>' . $product['display_balances'];
            } else {
                $main_qty = $product['main_qty'];
            }
            if ($user->show_price) {
                $offers[] = [
                    'id' => $product['p_id'],
                    'code' => $product['p_code'],
                    'article' => $product['article'],
                    'vendor' => $product['brand'],
                    'name' => $name,
                    'category' => $category,
                    'price' => $product['price'],
                    'currency' => 'UAH',
                    'personalPrice' => $product['personal_price'],
                    'personalCurrency' => $currency,
                    'available' => $available,
                    'local_storage' => $qty,
                    'central_storage' => $main_qty,

                    'barcode' => $product['barcode'],

                ];
            } else {
                $offers[] = [
                    'id' => $product['p_id'],
                    'code' => $product['p_code'],
                    'article' => $product['article'],
                    'vendor' => $product['brand'],
                    'name' => $name,
                    'category' => $category,
                    'price' => $product['price'],
                    'currency' => 'UAH',
                    'available' => $available,
                    'local_storage' => $qty,
                    'central_storage' => $main_qty,
                    'barcode' => $product['barcode'],
                ];
            }
        endwhile;
        if ($user->show_price) {
            if ($lang == 'ua') {
                $array_headers = ['ID', 'Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Персональна ціна', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок',  'Штрихкод'];
            } else {
                $array_headers = ['ID', 'Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта',  'Персональная цена', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток',  'Штрихкод'];
            }
        } else {
            if ($lang == 'ua') {
                $array_headers = ['ID', 'Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок', 'Штрихкод'];
            } else {
                $array_headers = ['ID', 'Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток',  'Штрихкод'];
            }
        }


        $head = ['Date', 'Name', 'Amount'];
        $data = [
            ['2003-12-31', 'James', '220'],
            ['2003-8-23', 'Mike', '153.5'],
            ['2003-06-01', 'John', '34.12'],
        ];
        $headStyle = [
            'font' => [
                'style' => 'bold'
            ],
            'text-align' => 'center',
            'vertical-align' => 'center',
            'border' => 'thin',
            'height' => 22,
        ];

        $excel_class = new \avadim\FastExcelWriter\Excel();
        $excel = $excel_class::create(['Sheet1']);
        $sheet = $excel->getSheet();

        $sheet->writeRow($currency_arr);
        $sheet->writeRow($array_headers, $headStyle);

        $sheet
            ->setColFormats(['0', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string',])
            ->setColWidths([10, 16, 25, 16, 60, 30, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16]);

        $rowNum = 1;
        foreach ($offers as $rowData) {
            $rowOptions = [
                'height' => 20,
            ];
            if ($rowNum % 2) {
                $rowOptions['fill'] = '#eee';
            }
            $sheet->writeRow($rowData);
        }

        return $excel->output('price_' . $lang . '.xlsx');
    }

    public static function downloadXls($id, $lang, $all)
    {



        $price_model = Price::where(['id' => $id])->first();
        // if ($price_model->all == 1) {
        //     Auth::loginUsingId(2);
        // } else {
        Auth::loginUsingId($price_model->user_id);
        // }
        $customer_code = Auth::user()->customer_code;
        $price_type_id = UserSettings::where(['customer_id' => $customer_code])->first()->price_type_id;
        $host = "31.134.122.218";
        $db_name = 'New_B2b_v1';
        $db_database = 'new_b2b_v1';
        $db_pass = 'MdTF6nr863yhPWze';
        // $host = "127.0.0.1";
        // $db_name = 'rotting_mysql';
        // $db_database = 'rotting_lavka';
        // $db_pass = 'DatumSoft1C';
        $connection = new PDO("mysql:host=" . $host . ";dbname=" . $db_database . "", $db_name, $db_pass);

        $rate_EUR = Currency::where(['id'=>978])->first()->rate;
        $rate_USD = Currency::where(['id'=>840])->first()->rate;
        $rate_UAH = 1;
        $stock_group_id = Auth::user()->stock_group_id;
        $result = $connection->query('SELECT DISTINCT
            pfp.name_ua,
            pfp.name_ru,
            pfp.article,
            pfp.brand,
            pfp.barcode,
            `product_in_pricelists`.product_id AS p_id,
            pfp.category_id,
            retail_price.price,
            `prices`.price AS personal_price,
            `prices`.currency_id AS currency_id,
            `cat`.name_ua AS category_ua,
	        `cat`.name_ru AS category_ru,
            `stocks_qty`.qty,
            `prod`.p_code AS p_code,
            `prod`.display_balances AS display_balances,
            `stocks_qty_main`.qty AS main_qty
        FROM product_in_pricelists

        LEFT JOIN (SELECT * FROM product_for_pricelists) AS pfp ON product_in_pricelists.product_id = pfp.product_id

        left join (select price, product_id  from product_prices where price_type_id = 2) AS retail_price ON product_in_pricelists.product_id = retail_price.product_id

        left JOIN
            (SELECT
                product_id, qty
            FROM products_in_stock_groups
            where stock_group_id = ' . $stock_group_id . ') AS stocks_qty
            ON product_in_pricelists.product_id = stocks_qty.product_id
        left JOIN
            (SELECT
                product_id, qty
            FROM products_in_stock_groups
            where stock_group_id = 41) AS stocks_qty_main
            ON product_in_pricelists.product_id = stocks_qty_main.product_id

        LEFT JOIN
            (SELECT
                product_id,
                price,
                currency_id
            FROM
            product_prices
            WHERE price_type_id = ' . $price_type_id . ') AS prices
            ON product_in_pricelists.product_id = prices.product_id

            LEFT JOIN (SELECT id, `categories`.`name` AS name_ua, `categories`.name_ru AS name_ru  FROM categories) AS cat ON pfp.category_id=cat.id

            LEFT JOIN (SELECT id, `products`.`code` AS p_code, `products`.display_balances AS display_balances  FROM products) AS prod ON pfp.product_id=prod.id

        where price_id=' . $id . '');
        $offers = [];
        $user = Auth::user();

        $usd = Currency::where(['id' => '840'])->first();
        if ($usd != null) {
            $usd = $usd->rate;
        } else {
            $usd = 0;
        }
        $eur = Currency::where(['id' => '978'])->first();
        if ($eur != null) {
            $eur = $eur->rate;
        } else {
            $eur = 0;
        }
        $currency_arr = ['', '', '', 'USD: ' . $usd . ' EUR: ' . $eur];
        $user_code = Auth::user()->user_code;
        while ($product = $result->fetch(PDO::FETCH_ASSOC)) :

            $result_price = $connection->query('SELECT price, ipt.price_type_id, product_id, currency_id
            FROM product_prices
            LEFT JOIN (select individual_price_types.price_type_id as price_type_id from individual_price_types
            where product_id = '.$product['p_id'].'
            AND customer_id = '.$user_code.')
            AS ipt ON  product_prices.price_type_id =  ipt.price_type_id
            WHERE product_id = '.$product['p_id'].'
            AND ipt.price_type_id Is NOT null');
            $individual_price = $product['personal_price'];
            while ($price = $result_price->fetch(PDO::FETCH_ASSOC)) :
                $individual_price = $price['price'];
            endwhile;
            if ($lang == 'ua') {
                $name = $product['name_ua'];
                $category = $product['category_ua'];
            } else {
                $name = $product['name_ru'];
                $category = $product['category_ru'];
            }
            if ($product['qty'] == 0) {
                $available = '-';
            } else {
                $available = '+';
            }
            $currency = '';
            if ($product['currency_id'] == 840) {
                $currency = 'USD';
            } elseif ($product['currency_id'] == 978) {
                $currency = 'EUR';
            } elseif ($product['currency_id'] == 980) {
                $currency = 'UAH';
            };
            $qty = 0;
            $main_qty = 0;
            if ($product['display_balances'] < $product['qty']) {
                $qty = '>' . $product['display_balances'];
            } else {
                $qty = $product['qty'];
            }
            if ($product['display_balances'] < $product['main_qty']) {
                $main_qty = '>' . $product['display_balances'];
            } else {
                $main_qty = $product['main_qty'];
            }



            if ($user->show_price) {
                $personal_price_UAH = 0;
                if ($product['currency_id']==978){
                    $personal_price_UAH = $individual_price*$rate_EUR;
                }elseif ($product['currency_id']==840){
                    $personal_price_UAH = $individual_price*$rate_USD;
                }else{
                    $personal_price_UAH = $individual_price*$rate_UAH;
                }
                $offers[] = [
                    'id' => $product['p_id'],
                    'code' => $product['p_code'],
                    'article' => $product['article'],
                    'vendor' => $product['brand'],
                    'name' => $name,
                    'category' => $category,
                    'price' => number_format($product['price'],2,'.',''),
                    'currency' => 'UAH',
                    'personalPrice' => number_format($individual_price,2,'.',''),//$product['personal_price'],
                    'personalCurrency' => $currency,
                    'personalPriceUAH'=> number_format($personal_price_UAH,2,'.',''),
                    'available' => $available,
                    'local_storage' => $qty,
                    'central_storage' => $main_qty,

                    'barcode' => $product['barcode'],

                ];
            } else {
                $offers[] = [
                    'id' => $product['p_id'],
                    'code' => $product['p_code'],
                    'article' => $product['article'],
                    'vendor' => $product['brand'],
                    'name' => $name,
                    'category' => $category,
                    'price' => number_format($product['price'],2,'.',''),
                    'currency' => 'UAH',
                    'available' => $available,
                    'local_storage' => $qty,
                    'central_storage' => $main_qty,
                    'barcode' => $product['barcode'],
                ];
            }
        endwhile;
        if ($user->show_price) {
            if ($lang == 'ua') {
                $array_headers = ['ID', 'Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Персональна ціна', 'Валюта','Персональна ціна ГРН', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок',  'Штрихкод'];
            } else {
                $array_headers = ['ID', 'Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта',  'Персональная цена', 'Валюта','Персональная цена ГРН', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток',  'Штрихкод'];
            }
        } else {
            if ($lang == 'ua') {
                $array_headers = ['ID', 'Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок', 'Штрихкод'];
            } else {
                $array_headers = ['ID', 'Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток',  'Штрихкод'];
            }
        }


        $head = ['Date', 'Name', 'Amount'];
        $data = [
            ['2003-12-31', 'James', '220'],
            ['2003-8-23', 'Mike', '153.5'],
            ['2003-06-01', 'John', '34.12'],
        ];
        $headStyle = [
            'font' => [
                'style' => 'bold'
            ],
            'text-align' => 'center',
            'vertical-align' => 'center',
            'border' => 'thin',
            'height' => 22,
        ];

        $excel_class = new \avadim\FastExcelWriter\Excel();
        $excel = $excel_class::create(['Sheet1']);
        $sheet = $excel->getSheet();

        $sheet->writeRow($currency_arr);
        $sheet->writeRow($array_headers, $headStyle);

        $sheet
            ->setColFormats(['0', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string', 'string',])
            ->setColWidths([10, 16, 25, 16, 60, 30, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16]);

        $rowNum = 1;
        foreach ($offers as $rowData) {
            $rowOptions = [
                'height' => 20,
            ];
            if ($rowNum % 2) {
                $rowOptions['fill'] = '#eee';
            }
            $sheet->writeRow($rowData);
        }

        return $excel->output('price_' . $lang . '.xlsx');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = Category::orderBy('id', 'desc')->where(['parent_id' => 0])->get();
        $prices = Price::where(['user_id' => Auth::user()->id])->get();
        if ($prices->count() == 0) {
            $price = new Price();
            $price->user_id = Auth::user()->id;
            $price->name = 'My price';
            $price->save();
            $prices = Price::where(['user_id' => Auth::user()->id])->get();
        }
        $showFilter = false;
        $temp_xml = Price::where(['user_id' => Auth::user()->id, 'temp' => 1, 'type' => 'xml'])->firstOrCreate();
        return view('catalog.xml', compact('products', 'prices', 'showFilter'));
    }

    public function delete(Request $request)
    {
        $price_id = $request->get('price_id');
        $price_type = $request->get('price_type');
        $price = Price::where(['id' => $price_id])->first();
        if ($price != null) {
            $price->delete();
        };
        $price_categories = PriceCategories::where(['price_id' => $price_id])->get();
        foreach ($price_categories as $key => $item) {
            $item->delete();
        }
        $prices = Price::where(['user_id' => Auth::user()->id, 'type' => $price_type, 'temp' => 0])->get();
        if ($price_type == 'xml') {
            return view('layouts.xml_body', compact('prices'));
        } elseif ($price_type == 'xls') {
            return view('layouts.xls_body', compact('prices'));
        }
    }

    public function xmlEdit(Request $request)
    {
        $id = $request->get('id');
        $price = Price::where(['id' => $id])->first();
        $showFilter = false;
        return view('catalog.xml_edit', ['price' => $price, 'showFilter' => $showFilter]);
    }

    public function xlsEdit(Request $request)
    {
        $id = $request->get('id');
        $price = Price::where(['id' => $id])->first();
        $showFilter = false;
        return view('catalog.xls_edit', ['price' => $price, 'showFilter' => $showFilter]);
    }

    public function xml()
    {
        $prices = Price::where(['user_id' => Auth::user()->id, 'temp' => '0', 'type' => 'xml', 'all' => 0])->get();
        $temp_xml = Price::where(['user_id' => Auth::user()->id, 'temp' => '1', 'type' => 'xml'])->firstOrCreate();
        $temp_xml->user_id = Auth::user()->id;
        $temp_xml->temp = '1';
        $temp_xml->type = 'xml';
        $temp_xml->save();
        $showFilter = false;
        return view('catalog.xml', compact('prices', 'temp_xml', 'showFilter'));
    }

    public function xls()
    {
        $prices = Price::where(['user_id' => Auth::user()->id, 'temp' => '0', 'type' => 'xls'])->get();
        $temp_xls = Price::where(['user_id' => Auth::user()->id, 'temp' => '1', 'type' => 'xls'])->firstOrCreate();
        $temp_xls->user_id = Auth::user()->id;
        $temp_xls->temp = '1';
        $temp_xls->type = 'xls';
        $temp_xls->save();
        $showFilter = false;
        return view('catalog.xls', compact('prices', 'temp_xls', 'showFilter'));
    }

    public function checkCategory(Request $request)
    {
        $ids = $request->get('category_id');
        $ids = str_replace('[', '', $ids);
        $ids = str_replace(']', '', $ids);
        $ids = str_replace('"', '', $ids);
        $ids = explode(",", $ids);
        //sdd($ids);
        foreach ($ids as $item) {
            $pos = strpos($item, 'b-');
            $brand = null;
            $category_id = null;
            $brand_category_id = null;
            if ($pos === false) {
                $brand = 0;
                $category_id = $item;
            } else {
                $brand = 1;
                $category_id = str_replace('b-', '', $item);

                $arr = explode("-c-", $category_id);
                $category_id = $arr[0];
                $brand_category_id = $arr[1];
            };
            $record = PriceCategories::where(['price_id' => $request->get('price_id'), 'category_id' => $category_id, 'brand' => $brand, 'brand_category_id' => $brand_category_id])->firstOrCreate();
            $record->price_id = $request->get('price_id');
            $record->category_id = $category_id;
            $record->brand = $brand;
            $record->check = $request->get('check');
            $record->brand_category_id = $brand_category_id;
            $record->save();
        }
        return true;
    }

    public static function arrayOfCategories($price_id, $lang, $all)
    {
        $neededCategories = [];
        $rootCategories = Category::where(['parent_id' => 0])->get();
        foreach ($rootCategories as $key => $value) {
            if ($all == 1) {
                $need = [];
                $need['check'] = 1;
            } else {
                $need = PriceCategories::where(['price_id' => $price_id, 'category_id' => $value['id'], 'brand' => 0])->first();
            }
            if ($need != null) {
                //dd($value->name);
                if ($lang == 'ua') {
                    $name = $value->nameua;
                } elseif ($lang == 'ru') {
                    $name = $value->toArray()['name_ru'];
                } else {
                    $name = '';
                }

                if ($need['check'] == 1) {
                    $neededCategories[] =
                        [
                            '_attributes' => [
                                'id' => $value['id'],
                                'parentId' => '',
                                'portalId' => $value['id']
                            ],
                            '_value' => $name,
                        ];
                    $childCategories = Category::where(['parent_id' => $value['id']])->get();
                    foreach ($childCategories as $child_value) {
                        if ($all == 1) {
                            $need = [];
                            $need['check'] = 1;
                        } else {
                            $need = PriceCategories::where(['price_id' => $price_id, 'category_id' => $child_value['id'], 'brand' => 0])->first();
                        }
                        if ($need != null) {

                            if ($need['check'] == 1) {
                                if ($lang == 'ua') {
                                    $child_name = $child_value->nameua;
                                } elseif ($lang == 'ru') {
                                    $child_name = $child_value->toArray()['name_ru'];
                                } else {
                                    $child_name = '';
                                }
                                $neededCategories[] =
                                    [
                                        '_attributes' => [
                                            'id' => $child_value['id'],
                                            'parentId' => $child_value['parent_id'],
                                            'portalId' => $child_value['id']
                                        ],
                                        '_value' => $child_name,
                                    ];
                            }
                        }
                    }
                }
            }
        }
        // usort($neededCategories, function ($a, $b) use ($key) {
        //     return $a['_attributes']['parentId'] - $b['_attributes']['parentId'];
        // });
        //dd($neededCategories);
        return $neededCategories;
    }

    public function arrayPictures($product)
    {
        $picture = [];
        foreach ($product['pictures'] as $value) {
            $picture[] = '' . 'https://'  . setting('domain')  . '/' . str_replace('public', 'storage', $value);
        }
        return $picture;
    }

    public function arrayOffers($price_id, $lang, $prom, $all)
    {
        $offers = [];
        $Categories = Category::whereNotNull('parent_id')->get();
        foreach ($Categories as $key => $value) {
            if ($all == 1) {
                $need = [];
                $need['check'] = 1;
            } else {
                $need = PriceCategories::where(['price_id' => $price_id, 'category_id' => $value['id'], 'brand' => 0])->first();
            }
            if ($need != null) {
                if ($need['check'] == 1) {
                    $need_brand_records = PriceCategories::where([
                        'price_id' => $price_id,
                        'brand_category_id' => $value['id'],
                        'brand' => 1,
                        'check' => 1
                    ])->get();
                    $brands = [];
                    if ($all == 1) {
                        $brand_models = Brand::get();
                        foreach ($brand_models as $val) {
                            $brands[] = $val->id;
                        }
                    } else {
                        foreach ($need_brand_records as $item) {
                            $brands[] = $item->category_id;
                        }
                    }
                    $products = Product::where(['category_id' => $value['id']])->whereIn('brand_id', $brands)->get();
                    $i = 0;
                    foreach ($products as $product) {
                        $i++;
                        if ($i % 50 == 0) {
                            //   sleep(1);
                        }
                        if ($lang == 'ua') {
                            $name = $product['name'];
                            $description = $product['description'];
                            $country_obj = Country::find($product->country_of_consignment_id);
                            $country = '';
                            if ($country_obj != null) {
                                $country = $country_obj->name;
                            }
                            $country_obj = Country::find($product->country_of_brand_registration_id);
                            $country_reg = '';
                            if ($country_obj != null) {
                                $country_reg = $country_obj->name;
                            }
                            $params = json_decode($product->properties);
                        } else {
                            $name = $product['name_ru'];
                            $description = $product['description_ru'];
                            $country_obj = Country::find($product->country_of_consignment_id);
                            $country = '';
                            if ($country_obj != null) {
                                $country = $country_obj->name_ru;
                            }
                            $country_obj = Country::find($product->country_of_brand_registration_id);
                            $country_reg = '';
                            if ($country_obj != null) {
                                $country_reg = $country_obj->name;
                            }
                            $params = json_decode($product->properties_ru);
                        }
                        if ($prom == 1) {
                            if ($product->myAmount == 0) {
                                $available = '';
                            } else {
                                $available = 'true';
                            }
                        } else {
                            if ($product->myAmount == 0) {
                                $available = 'false';
                            } else {
                                $available = 'true';
                            }
                        }
                        $params_arr = [];


                        $params_arr[] = [
                            '_attributes' => ['name' => __('l.country_of_consignment', [], $lang)],
                            '_value' => $product->countryOfConsignmentName
                        ];
                        $params_arr[] = [
                            '_attributes' => ['name' => __('l.country_of_brand_registration', [], $lang)],
                            '_value' => $product->countryOfBrandRegistrationName
                        ];

                        if ($params != null) {
                            usort($params, function ($a, $b) use ($key) {
                                try {
                                    return $a->sort - $b->sort;
                                } catch (\Throwable $th) {
                                    return 1;
                                }
                            });
                            foreach ($params as $param) {
                                $params_arr[] = [
                                    '_attributes' => ['name' => $param->name],
                                    '_value' => $param->value
                                ];
                            }
                        }


                        $params_arr[] = [
                            '_attributes' => ['name' => __('l.weight', [], $lang)],
                            '_value' => $product->weight
                        ];
                        $params_arr[] = [
                            '_attributes' => ['name' => __('l.volume', [], $lang)],
                            '_value' => $product->volume
                        ];
                        $offers[] = [
                            '_attributes' => [
                                'id' => $product['id'],
                                'available' => $available,
                            ],
                            'currencyId' => 'UAH',
                            'article' => $product['article'],
                            'barcode' => $product['barcode'],
                            'vendorBarcode' => $product['supplier_barcode'],
                            'vendorCode' => $product['supplier_code'],
                            'country' => $country,
                            'country_brand_registration' => $country_reg,
                            'categoryId' => $product['category_id'],
                            'price' => $product['retailPriceUah'],
                            'vendorCode' => $product['code'],
                            'vendor' => $product['brandName'],
                            'available' => $product['myAmount'],
                            'name' => $name,
                            'description' => [
                                '_cdata' => $description,
                            ],
                            'picture' => $this->arrayPictures($product),
                            'param' => $params_arr,
                        ];
                    }
                }
            }
        }
        dd($offers);
        return $offers;
    }

    public static function generatePrice()
    {
        $start = microtime(true);

        //ProductInPricelist::truncate();
        $prices = Price::where(['temp' => 0])->get();
        foreach ($prices as $price) {
            $price->created = 0;
            $price->save();
        }
        // $price_all_xml = Price::find(1);
        // if ($price_all_xml != null) {
        //     $price_all_xml->created = 0;
        //     $price_all_xml->save();
        // }

        // $price_all_xls = Price::find(2);
        // if ($price_all_xls != null) {
        //     $price_all_xls->created = 0;
        //    $price_all_xls->delete();
        // }

        // $price_all_xml = new Price();
        // $price_all_xml->type = 'xml';
        // $price_all_xml->temp = 0;
        // $price_all_xml->user_id = 0;
        // $price_all_xml->all = 1;
        // $price_all_xml->name = 'all_xml';
        // $price_all_xml->id = 1;
        // $price_all_xml->created = 1;
        // $price_all_xml->save();

        // $price_all_xls = new Price();
        // $price_all_xls->type = 'xls';
        // $price_all_xls->temp = 0;
        // $price_all_xls->user_id = 0;
        // $price_all_xls->all = 1;
        // $price_all_xls->name = 'all_xls';
        // $price_all_xls->id = 2;
        // $price_all_xls->created = 1;
        // $price_all_xls->save();


        // $prices = Price::where(['temp' => 0])->get();
        PriceController::createProductForPriceList();
        // $i = 0;
        // foreach ($prices as $item) {
        //     $j = PriceController::createProductInPriceList($item->id, $item->all);
        //     $item->created = 1;
        //     $item->save();
        //     $i = $i + $j;
        // }


        //return '';
        return 'Prices are NULLED, time: ' . round(microtime(true) - $start, 4) . ' sec.';

    }

    public static function createProductForPriceList()
    {
        $products = Product::where('active', 1)->get();
        $j = 0;
        ProductForPricelist::truncate();
        foreach ($products as $product) {
            $productForPricelist = new ProductForPricelist();
            $productForPricelist->product_id = $product->id;

            $productForPricelist->brand = $product->brandName;
            $productForPricelist->article = $product->article;
            $productForPricelist->barcode = $product->barcode;
            $productForPricelist->vendor_barcode = $product->supplier_barcode;
            $productForPricelist->vendor_code = $product->code;

            $productForPricelist->name_ua = $product->name;
            $productForPricelist->description_ua = $product->description;

            $productForPricelist->name_ru = $product->name_ru;
            $productForPricelist->description_ru = $product->description_ru;
            $productForPricelist->category_id = $product->category_id;
            $productForPricelist->price = $product->price;
            $productForPricelist->display_balances = $product->display_balances;

            $country_obj = Country::find($product->country_of_consignment_id);
            $country_ua = '';
            $country_ru = '';
            if ($country_obj != null) {
                $country_ua = $country_obj->name;
                $country_ru = $country_obj->name_ru;
            }
            $country_obj = Country::find($product->country_of_brand_registration_id);
            $country_reg_ua = '';
            $country_reg_ru = '';
            if ($country_obj != null) {
                $country_reg_ua = $country_obj->name;
                $country_reg_ru = $country_obj->name_ru;
            }
            $productForPricelist->country_ua = $country_ua;
            $productForPricelist->country_ru = $country_ru;

            $productForPricelist->country_reg_ua = $country_reg_ua;
            $productForPricelist->country_reg_ru = $country_reg_ru;

            $pictures = '';
            $pictures_json = '';
            $first_pic = true;
            foreach ($product['pictures'] as $value) {
                //$pictures = $pictures . '<picture>' . $_SERVER['SERVER_NAME'] . '/' . str_replace('public', 'storage', $value) . '</picture>';
                $pictures = $pictures . '<picture>' . 'https://' . setting('domain') . '/' . str_replace('public', 'storage', $value) . '</picture>';
                if (!$first_pic) {
                    $pictures_json = $pictures_json . ',';
                }
                $first_pic = false;
                $pictures_json = $pictures_json   . '"' . 'https://' . setting('domain') . '/' . str_replace('public', 'storage', $value) . '"';
            }
            $productForPricelist->pictures = $pictures;
            $productForPricelist->pictures_json = $pictures_json;
            $params_ua = json_decode($product->properties);
            $params_ru = json_decode($product->properties_ru);
            $parametrs_ua = '';
            $parametrs_ru = '';
            $country_obj = Country::find($product->country_of_consignment_id);
            $country = '';
            if ($country_obj != null) {
                $country = $country_obj->name;
                $country_ru = $country_obj->name_ru;
            }
            $parametrs_ua = $parametrs_ua . '<param name="Країна походження">' . $country . '</param>';
            $parametrs_ru = $parametrs_ru . '<param name="Страна происхождения">' . $country_ru . '</param>';

            $country_obj = Country::find($product->country_of_brand_registration_id);
            $country = '';
            if ($country_obj != null) {
                $country = $country_obj->name;
                $country_ru = $country_obj->name_ru;
            }
            $parametrs_ua = $parametrs_ua . '<param name="Країна реєстрації бренду">' . $country . '</param>';
            $parametrs_ru = $parametrs_ru . '<param name="Страна регистрации бренда">' . $country_ru . '</param>';
            if ($params_ua != null) {
                usort($params_ua, function ($a, $b) {
                    try {
                        return $a->sort - $b->sort;
                    } catch (\Throwable $th) {
                        return 1;
                    }
                });
                foreach ($params_ua as $param) {
                    $parametrs_ua = $parametrs_ua . '<param name="' . $param->name . '">' . $param->value . '</param>';
                }
            }
            if ($params_ru != null) {
                usort($params_ru, function ($a, $b) {
                    try {
                        return $a->sort - $b->sort;
                    } catch (\Throwable $th) {
                        return 1;
                    }
                });
                foreach ($params_ru as $param) {
                    $parametrs_ru = $parametrs_ru . '<param name="' . $param->name . '">' . $param->value . '</param>';
                }
            }
            $parametrs_ru = $parametrs_ru . '<param name="Вес брутто (кг.)">' . $product->weight . '</param>';
            $parametrs_ru = $parametrs_ru . '<param name="Объём (м³)">' . $product->volume . '</param>';

            $parametrs_ua = $parametrs_ua . '<param name="Вага брутто (кг.)">' . $product->weight . '</param>';
            $parametrs_ua = $parametrs_ua . '<param name="Об`єм (м³)">' . $product->volume . '</param>';

            $productForPricelist->param_ua = $parametrs_ua;
            $productForPricelist->param_ru = $parametrs_ru;
            $productForPricelist->save();
            $j++;
        }
    }



    public static function createProductInPriceList($id, $all, $type = null)
    {

        $j = 0;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        $price_model = Price::where(['id' => $id])->first();
        if ($all == 1) {
            Auth::loginUsingId(2);
        } else {
            Auth::loginUsingId($price_model->user_id);
        }
        $productsInPricelists = ProductInPricelist::where(['price_id' => $id])->get();
        foreach ($productsInPricelists as $item_product) {
            $item_product->delete();
        }
        if ($type == 'xml' || $type == 'xls') {
        } else {
            $type = $price_model->type;
        }
        //if ($type == 'xml') {
        $Categories = Category::whereNotNull('parent_id')->get();

        $i = 0;
        $start = microtime(true);
        foreach ($Categories as $key => $value) {
            if ($all == 1) {
                $need = [];
                $need['check'] = 1;
            } else {
                $need = PriceCategories::where(['price_id' => $id, 'category_id' => $value['id'], 'brand' => 0])->first();
            }
            if ($need != null) {
                if ($need['check'] == 1) {
                    $need_brand_records = PriceCategories::where([
                        'price_id' => $id,
                        'brand_category_id' => $value['id'],
                        'brand' => 1,
                        'check' => 1
                    ])->get();
                    $brands = [];
                    if ($all == 1) {
                        $brand_models = Brand::get();
                        foreach ($brand_models as $val) {
                            $brands[] = $val->id;
                        }
                    } else {
                        foreach ($need_brand_records as $item) {
                            $brands[] = $item->category_id;
                        }
                    }
                    $products = Product::where(['category_id' => $value['id']])->whereIn('brand_id', $brands)->where('active', 1)->get();

                    foreach ($products as $product) {
                        $productInPricelist = ProductInPricelist::where(['product_id' => $product->id])->where(['price_id' => $id])->first();
                        if ($productInPricelist != null) {
                        }else{
                            $productInPricelist = new ProductInPricelist();
                            $productInPricelist->product_id = $product->id;
                            $productInPricelist->price_id = $id;
                            $productInPricelist->save();
                        }
                        // $productInPricelist = ProductInPricelist::firstOrNew(['product_id'=>$product->id,'price_id'=>$id]);
                        // //$productInPricelist = new ProductInPricelist();
                        // $productInPricelist->product_id = $product->id;
                        // $productInPricelist->price_id = $id;

                        // $productInPricelist->save();
                        // $j++;
                    }
                }
            }
        }

        return $j;
    }

    public function download(Request $request, $id, $lang, $prom, $all, $type)
    {
        set_time_limit(0);
        ini_set('memory_limit', '100M');


        $price_model = Price::where(['id' => $id])->first();

        if ($all == 1) {

            Auth::loginUsingId($id);
        } else {

            Auth::loginUsingId($price_model->user_id);
        }

        // $type = $request->get('type');
        if ($type == 'xml' || $type == 'xls') {
        } else {
            $type = $price_model->type;
        }
        if ($type == 'xml') {
            $xmlWriter = new XMLWriter();
            $xmlWriter->openMemory();
            $xmlWriter->startDocument('1.0', 'UTF-8');
            $xmlWriter->startElement('yml_catalog');
            $xmlWriter->writeAttribute('data', date("Y-m-d"));
            $xmlWriter->startElement('shop');
            $fp = tempnam(sys_get_temp_dir(), 'TMP_');
            //dd($fp);

            $arr_categories = $this->arrayOfCategories($id, $lang, $all);
            $xmlWriter->startElement('categories');
            foreach ($arr_categories as $item) {
                $xmlWriter->startElement('category');
                $xmlWriter->writeAttribute('id', $item['_attributes']['id']);
                $xmlWriter->writeAttribute('parentId', $item['_attributes']['parentId']);
                $xmlWriter->writeAttribute('portalId', $item['_attributes']['portalId']);
                $xmlWriter->writeRaw(htmlspecialchars($item['_value'], ENT_QUOTES));

                $xmlWriter->endElement();
            }
            $xmlWriter->endElement();

            $xmlWriter->startElement('offers');
            $Categories = Category::whereNotNull('parent_id')->get();

            $i = 0;
            // $start = microtime(true);
            foreach ($Categories as $key => $value) {
                if ($all == 1) {
                    $need = [];
                    $need['check'] = 1;
                } else {
                    $need = PriceCategories::where(['price_id' => $id, 'category_id' => $value['id'], 'brand' => 0])->first();
                }
                if ($need != null) {
                    if ($need['check'] == 1) {
                        $need_brand_records = PriceCategories::where([
                            'price_id' => $id,
                            'brand_category_id' => $value['id'],
                            'brand' => 1,
                            'check' => 1
                        ])->get();
                        $brands = [];
                        if ($all == 1) {
                            $brand_models = Brand::get();
                            foreach ($brand_models as $val) {
                                $brands[] = $val->id;
                            }
                        } else {
                            foreach ($need_brand_records as $item) {
                                $brands[] = $item->category_id;
                            }
                        }
                        // session_write_close();
                        // session_start(['read_and_close'=>true]);

                        $products = Product::where(['category_id' => $value['id']])->whereIn('brand_id', $brands)->get();


                        foreach ($products as $product) {

                            if ($lang == 'ua') {
                                $name = $product['name'];
                                $description = $product['description'];
                                $country_obj = Country::find($product->country_of_consignment_id);
                                $country = '';
                                if ($country_obj != null) {
                                    $country = $country_obj->name;
                                }
                                $country_obj = Country::find($product->country_of_brand_registration_id);
                                $country_reg = '';
                                if ($country_obj != null) {
                                    $country_reg = $country_obj->name;
                                }
                                $params = json_decode($product->properties);
                            } else {
                                $name = $product['name_ru'];
                                $description = $product['description_ru'];
                                $country_obj = Country::find($product->country_of_consignment_id);
                                $country = '';
                                if ($country_obj != null) {
                                    $country = $country_obj->name_ru;
                                }
                                $country_obj = Country::find($product->country_of_brand_registration_id);
                                $country_reg = '';
                                if ($country_obj != null) {
                                    $country_reg = $country_obj->name;
                                }
                                $params = json_decode($product->properties_ru);
                            }
                            $qty = $product->myAmount;
                            if ($prom == 1) {
                                if ($qty == 0) {
                                    $available = '';
                                } else {
                                    $available = 'true';
                                }
                            } else {
                                if ($qty == 0) {
                                    $available = 'false';
                                } else {
                                    $available = 'true';
                                }
                            }

                            $xmlWriter->startElement('offer');
                            $xmlWriter->writeAttribute('id', $product['id']);
                            $xmlWriter->writeAttribute('available', $available);
                            $xmlWriter->writeElement('currencyId', 'UAH');
                            $xmlWriter->writeElement('article', $product['article']);
                            $xmlWriter->writeElement('barcode', $product['barcode']);
                            $xmlWriter->writeElement('vendorBarcode', $product['supplier_barcode']);
                            $xmlWriter->writeElement('vendorCode', $product['supplier_code']);
                            $xmlWriter->writeElement('categoryId', $product['category_id']);
                            $xmlWriter->writeElement('price', $product['price']);
                            $xmlWriter->writeElement('vendorCode', $product['code']);
                            $xmlWriter->writeElement('vendor', $product['brandName']);
                            $xmlWriter->writeElement('stock_quantity', $qty);
                            $xmlWriter->writeElement('name', htmlspecialchars($name, ENT_QUOTES));
                            $xmlWriter->startElement('description');
                            $xmlWriter->writeCdata($description);
                            $xmlWriter->endElement();

                            $xmlWriter->startElement('param');
                            $xmlWriter->writeAttribute('name', __('l.country_of_consignment', [], $lang));
                            $xmlWriter->writeRaw($country);
                            $xmlWriter->endElement();

                            $xmlWriter->startElement('param');
                            $xmlWriter->writeAttribute('name', __('l.country_of_brand_registration', [], $lang));
                            $xmlWriter->writeRaw($country_reg);
                            $xmlWriter->endElement();

                            if ($params != null) {
                                usort($params, function ($a, $b) use ($key) {
                                    try {
                                        return $a->sort - $b->sort;
                                    } catch (\Throwable $th) {
                                        return 1;
                                    }
                                });
                                foreach ($params as $param) {
                                    $xmlWriter->startElement('param');
                                    $xmlWriter->writeAttribute('name', $param->name);
                                    $xmlWriter->writeRaw($param->value);
                                    $xmlWriter->endElement();
                                }
                            }
                            $xmlWriter->startElement('param');
                            $xmlWriter->writeAttribute('name', __('l.weight', [], $lang));
                            $xmlWriter->writeRaw($product->weight);
                            $xmlWriter->endElement();

                            $xmlWriter->startElement('param');
                            $xmlWriter->writeAttribute('name', __('l.volume', [], $lang));
                            $xmlWriter->writeRaw($product->volume);
                            $xmlWriter->endElement();

                            foreach ($product['pictures'] as $value) {
                                $xmlWriter->writeElement('picture', '' . 'https://'  . setting('domain')  . '/' . str_replace('public', 'storage', $value));
                            }
                            $xmlWriter->endElement();
                            if (0 == $i % 10) {
                                file_put_contents($fp, $xmlWriter->flush(true), FILE_APPEND);
                            }
                            $i++;
                        }
                    }
                }
            }
            //echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';
            //echo $i;


            $xmlWriter->endElement();
            $xmlWriter->endElement();
            $xmlWriter->endElement();
            $xmlWriter->endDocument();
            file_put_contents($fp, $xmlWriter->flush(true), FILE_APPEND);

            $xml = file_get_contents($fp);

            return response($xml, 200)
                ->header('content-Type', 'text/xml')
                ->header('cache-control', 'public')
                ->header('content-disposition', 'inline; filename="b2b-sklad.xml"');
            $array = [
                'shop' => [
                    'currencies' => [
                        'currency' =>
                        [
                            '_attributes' => [
                                'id' => 'UAH',
                                'rate' => '1',
                            ]
                        ],
                    ],
                    'categories' => [
                        'category' => $this->arrayOfCategories($id, $lang, $all),
                    ],
                    'offers' => [
                        'offer' => $this->arrayOffers($id, $lang, $prom, $all),
                    ]
                ]
            ];
            $root = [
                'rootElementName' => 'yml_catalog',
                '_attributes' => [
                    'data' => date("Y-m-d"),
                ],
            ];
            $arrayToXml = new ArrayToXml($array, $root, true, 'UTF-8', '1.0', [], true);
            $arrayToXml->setDomProperties(['formatOutput' => true]);
            $xml = $arrayToXml->toXml();
            return response($xml, 200)->header('Content-Type', 'application/xml');
        } else {
            $products = $this->arrayOffersXls($id, $lang, $prom, $all);
            $user = Auth::user();
            $usd = Currency::where(['id' => '840'])->first();
            if ($usd != null) {
                $usd = $usd->rate;
            } else {
                $usd = 0;
            }
            $eur = Currency::where(['id' => '978'])->first();
            if ($eur != null) {
                $eur = $eur->rate;
            } else {
                $eur = 0;
            }
            $currency = ['', '', '', 'USD: ' . $usd . ' EUR: ' . $eur];
            if ($user->show_price) {
                if ($lang == 'ua') {
                    $array_headers = ['Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок', 'Персональна ціна', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
                } else {
                    $array_headers = ['Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток',  'Персональная цена', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
                }
            } else {
                if ($lang == 'ua') {
                    $array_headers = ['Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
                } else {
                    $array_headers = ['Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
                }
            }
            return Excel::download(new ProductsExport([
                $currency,
                $array_headers,
                $products
            ]), 'price_' . $lang . '.xlsx');
        }
        Auth::logout();
    }

    // public function arrayOffersXls($price_id, $lang, $prom, $all)
    // {
    //     $host = env("DB_HOST", "");
    //     $db_name = env("DB_USERNAME", '');
    //     $db_database = env("DB_DATABASE", '');
    //     $db_pass = env("DB_PASSWORD");
    //     $connection = new PDO("mysql:host=" . $host . ";dbname=" . $db_database . "", $db_name, $db_pass);

    //     $result = $connection->query('SELECT * FROM product_in_pricelists left join (SELECT product_id, qty FROM products_in_stock_groups where stock_group_id = 61) AS stocks_qty ON product_in_pricelists.product_id = stocks_qty.product_id where price_id=' . $id . ';');

    //     $offers = [];
    //     $user = Auth::user();
    //     while ($product = $result->fetch(PDO::FETCH_ASSOC)) :
    //         if ($lang == 'ua') {
    //             $name = $product['name_ua'];
    //         } else {
    //             $name = $product['name_ru'];
    //         }
    //         if ($product['qty'] == 0) {
    //             $available = '-';
    //         } else {
    //             $available = '+';
    //         }
    //         if ($user->show_price) {
    //             $offers[] = [
    //                 'id' => $product['code'],
    //                 'article' => $product['article'],
    //                 'vendor' => $product['brand'],
    //                 'name' => $name,
    //                 'category' => $product['category_id'],
    //                 'price' => $product['price'],
    //                 'currency' => 'UAH',
    //                 'available' => $available,
    //                 // 'personalPrice' => $product['personalPrice'],
    //                 // 'personalCurrency' => $product['personalPriceCurrency'],
    //             ];
    //         } else {
    //             $offers[] = [
    //                 'id' => $product['code'],
    //                 'article' => $product['article'],
    //                 'vendor' => $product['brand'],
    //                 'name' => $name,
    //                 'category' => $product['category_id'],
    //                 'price' => $product['price'],
    //                 'currency' => 'UAH',
    //                 'available' => $available,
    //             ];
    //         }
    //     endwhile;
    //     $usd = Currency::where(['id' => '840'])->first();
    //     if ($usd != null) {
    //         $usd = $usd->rate;
    //     } else {
    //         $usd = 0;
    //     }
    //     $eur = Currency::where(['id' => '978'])->first();
    //     if ($eur != null) {
    //         $eur = $eur->rate;
    //     } else {
    //         $eur = 0;
    //     }
    //     $currency = ['', '', '', 'USD: ' . $usd . ' EUR: ' . $eur];
    //     if ($user->show_price) {
    //         if ($lang == 'ua') {
    //             $array_headers = ['Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок', 'Персональна ціна', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
    //         } else {
    //             $array_headers = ['Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток',  'Персональная цена', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
    //         }
    //     } else {
    //         if ($lang == 'ua') {
    //             $array_headers = ['Код товару', 'Артикул', 'Бренд', 'Номенклатура', 'Категорія', 'РРЦІ', 'Валюта', 'Наявність', 'Локальний склад залишок', 'Центральний склад залишок', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
    //         } else {
    //             $array_headers = ['Код товара', 'Артикул', 'Бренд', 'Номенклатура', 'Категория', 'РРЦИ', 'Валюта', 'Наличие', 'Локальный склад остаток', 'Центральный склад остаток', 'Валюта', 'Штрихкод', 'Номенклатура основного постачальника', 'Номенклатура основного постачальника'];
    //         }
    //     }

    //     return Excel::download(new ProductsExport([
    //         $currency,
    //         $array_headers,
    //         $offers
    //     ]), 'price_' . $lang . '.xlsx');
    //     return $offers;
    // }

    public function saveName(Request $request)
    {
        $id = $request->get('price_id');
        $name = $request->get('name');
        $price = Price::where(['id' => $id])->first();
        if ($price != null) {
            $price->name = $name;
            $price->save();
        }
        return true;
    }

    public function save(Request $request)
    {
        $id = $request->get('price_id');
        $price = Price::where(['id' => $id])->first();
        if ($price != null) {
            $price->temp = 0;
            $price->created = 0;
            $price->save();
        }
        return true;
    }

    public function treeCategory(Request $request)
    {
        $categories = Category::orderBy('sort', 'asc')->where(['parent_id' => 0])->get();
        $cats = array();
        $i = 0;
        foreach ($categories as $cat) {

            $check_category = PriceCategories::where([
                'category_id' => $cat->id,
                'price_id' => $request->get('price_id'),
                'brand' => 0
            ])->first();
            if ($check_category != null) {
                if ($check_category->check != 0) {
                    $check = $check_category['check'];
                } else {
                    $check = 0;
                }
            } else {
                $check = 0;
            }
            //dd(compact('check_category','check'));
            $cat_detail['text'] = $cat['name'];
            $cat_detail['id'] = trim($cat['id']);
            $state = [];
            $state['checked'] = $check;
            $cat_detail['state'] = $state;
            $cats[$i] = $cat_detail;

            $i++;
        }

        $tree = $this->build_tree($cats, $request->get('price_id'));


        return $tree;
    }

    public function build_tree(&$cats, $price_id)
    {
        foreach ($cats as &$cat) {
            $categories = Category::orderBy('sort', 'asc')->where(['parent_id' => $cat['id']])->get();
            $i = 0;
            $dats = array();
            foreach ($categories as $dat) {
                $check_category = PriceCategories::where(['category_id' => $dat->id, 'price_id' => $price_id, 'brand' => 0])->first();
                $check = 0;
                if ($check_category != null) {
                    if ($check_category->check != 0) {
                        $check = $check_category['check'];
                    }
                };
                $cat_detail['text'] = $dat['name'];
                $cat_detail['id'] = trim($dat['id']);
                $state = [];
                $state['checked'] = $check;
                $cat_detail['state'] = $state;
                $dats[$i] = $cat_detail;
                $i++;
            }

            $this->build_tree($dats, $price_id);
            if (count($dats) > 0) {
                $cat['nodes'] = $dats;
            } else {
                $brands = $this->addBrands($price_id, $cat['id']);
                $cat['nodes'] = $brands;
            }
        }
        return $cats;
    }

    public function addBrands($price_id, $category_id)
    {
        $brands_arr = [];

        $i = 0;
        $products = Product::where(['category_id' => $category_id])->get();
        $brand_id = [];
        foreach ($products as $item) {
            $brand_id[] = $item->brand_id;
        }
        $brands = Brand::whereIn('id', $brand_id)->get();
        foreach ($brands as $brand) {
            $check_brand = PriceCategories::where([
                'category_id' => $brand->id,
                'brand' => 1,
                'price_id' => $price_id,
                'brand_category_id' => $category_id
            ])->first();
            $check = 0;
            if ($check_brand != null) {
                $check = $check_brand['check'];
            };
            $cat_detail['text'] = $brand['name'];
            $cat_detail['id'] = 'b-' . $brand->id . '-c-' . $category_id;
            $state = [];
            $state['checked'] = $check;
            $cat_detail['state'] = $state;
            $brands_arr[$i] = $cat_detail;
            $i++;
        }
        return $brands_arr;
    }
}
