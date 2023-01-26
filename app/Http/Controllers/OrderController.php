<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \avadim\FastExcelReader\Excel;

class OrderController extends Controller
{

    const SOURCE_TYPE_1C = '1C';

    static $soapClient;
    static $cache = [];

    static function WSDL()
    {
        $params = [];
        $params['wsdl'] = 'http://saw.in.ua:1080/Live/ws/b2b?wsdl';
        return $params['wsdl'];
    }

    static function &getSoapClient()
    {

        ini_set("soap.wsdl_cache_enabled", "0");
        ini_set("soap.wsdl_cache_ttl", "86400");

        $wsdl = self::WSDL();

        $login = 'b2bprog';
        $pwd = '10022020';

        $soapParameters = array(
            'exceptions' => true,
            'login' => $login,
            'password' => $pwd
        );

        self::$soapClient = new \SoapClient($wsdl, $soapParameters);
        // }

        return self::$soapClient;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $number_search = $request->get('number_search');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $source = $request->get('source_order');

        $orders = Order::where(['user_id' => Auth::user()->id])->orderBy('created_at', 'DESC');
        if ($number_search != null) {
            $orders = $orders->where(function ($query) use ($number_search) {
                $query->where('code', 'like', '%' . $number_search . '%')
                    ->orWhere('invoice_number', 'like', '%' . $number_search . '%');
            });
        };
        if ($date_from != null) {
            $date_from = new DateTime($date_from);
            $orders = $orders->where('created_at', '>', $date_from);
        }
        if ($date_to != null) {
            $date_to = (new DateTime($date_to))->modify('+1 day')->format('Y-m-d');
            $orders = $orders->where('created_at', '<', $date_to);
        }
        if ($source != null && $source != '4' && $source != 'undefined') {
            $orders = $orders->where(['source' => $source]);
        }
        $orders = $orders->paginate(20);
        if ($request->ajax()) {
            return view('docs.order_list_body', compact('orders'));
        }
        $showFilter = false;
        return view('docs.orders', compact('orders', 'showFilter'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function orderDetail($id)
    {
        $showFilter = false;
        $order = Order::where(['id' => $id])->first();
        return view('docs.order_detail', compact('order', 'showFilter'));
    }

    public function printOrder($id)
    {

        $client = self::getSoapClient();


        $params = new \SoapVar([
            "Source" => 'order_' . $id
        ], SOAP_ENC_OBJECT);


        $data = base64_decode($client->GetPdf($params)->return, true);

        $order = Order::where(['id' => $id])->first();
        if ($order != null) {
            $id = $order->code . '_' . substr($order->created_at, 0, 10) . '';
        }
        Storage::put('public/orders/order_' . $id . '.pdf', $data);

        $path = 'public/orders/';
        $name = 'order_' . $id . '.pdf';
        $path = Storage::disk('local')->path('public/orders/');
        return response()->download(public_path('storage/orders/order_' . $id . '.pdf'));
        //return response()->download($path);

    }

    public function printRetailOrder($id)
    {

        $client = self::getSoapClient();


        $params = new \SoapVar([
            "Source" => 'retail_order_' . $id
        ], SOAP_ENC_OBJECT);


        $data = base64_decode($client->GetPdf($params)->return, true);

        $order = Order::where(['id' => $id])->first();
        if ($order != null) {
            $id = $order->code . '_' . substr($order->created_at, 0, 10) . '';
        }
        Storage::put('public/retail_orders/order_' . $id . '.pdf', $data);

        $path = 'public/orders/';
        $name = 'order_' . $id . '.pdf';
        $path = Storage::disk('local')->path('public/orders/');
        return response()->download(public_path('storage/retail_orders/order_' . $id . '.pdf'));
        //return response()->download($path);

    }

    public function printInvoice($id)
    {

        $client = self::getSoapClient();


        $params = new \SoapVar([
            "Source" => 'invoice_' . $id
        ], SOAP_ENC_OBJECT);


        $data = base64_decode($client->GetPdf($params)->return, true);

        $order = Order::where(['id' => $id])->first();
        if ($order != null) {
            $id = $order->invoice_number . '_' . substr($order->created_at, 0, 10) . '';
        }
        Storage::put('public/invoices/invoice_' . $id . '.pdf', $data);

        // $path = 'public/orders/';
        // $name = 'order_' . $id . '.pdf';
        //$path = Storage::disk('local')->path('public/orders/');
        return response()->download(public_path('storage/invoices/invoice_' . $id . '.pdf'));
        //return response()->download($path);

    }

    public function orderRepeat($id)
    {
        Cart::where(['user_id' => Auth::user()->id])->delete();
        $orderItems = OrderItem::where(['order_id' => $id])->get();
        foreach ($orderItems as $key => $item) {
            $newCart = new Cart();
            $newCart->product_id = $item->product_id;
            $newCart->qty = $item->qty;
            $newCart->user_id = Auth::user()->id;
            $newCart->price = Product::where(['id' => $item->product_id])->first()->personalPrice;
            $newCart->save();
        }
        return redirect()->route('cart');
    }

    public function uploadOrder(Request $request)
    {
        try {
            $image = $request->file('file');
            $fileInfo = $image->getClientOriginalName();
            $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);

            $file_name = 'order-' . time() . '.' . $extension;
            $image->move(public_path('uploads/orders'), $file_name);

            $path = public_path('uploads/orders/') . $file_name;

            // Open XLSX-file
            $excel = Excel::open($path);

            // Read all rows
            $result = $excel->readRows();

            Cart::where(['user_id' => Auth::user()->id])->delete();
            foreach ($result as $key => $value) {

                if ($key > 1 && $value['A'] != '') {

                    $product_id = ltrim($value['A'], "0");
                    $product = Product::where(['id' => $product_id])->first();
                    if ($product == null) {
                        echo 'Problem with ID:' . $product_id;;
                    } else {
                        $qty = ltrim($value['C'], "0");
                        $newCart = Cart::where(['product_id' => $product_id, 'user_id' => Auth::user()->id])->first();
                        if ($newCart == null) {
                            $newCart = new Cart();
                            $newCart->product_id = $product_id;
                            $newCart->qty = $qty;
                            $newCart->user_id = Auth::user()->id;
                            $newCart->price = Product::where(['id' => $product_id])->first()->personalPrice;
                        } else {
                            $newCart->qty = $newCart->qty + $qty;
                        }

                        $newCart->save();
                    }
                }
            }
            return true;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function makeOrderFromExcel()
    {
        $showFilter = false;
        return view('docs.make_from_excel', compact('showFilter'));
    }
}
