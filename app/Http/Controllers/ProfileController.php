<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail as Mail;
use App\Mail\RegisterMail;
use DateTime;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use Validator;


class ProfileController extends Controller
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

        try {
            //code...
            self::$soapClient = new \SoapClient($wsdl, $soapParameters);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        // }

        return self::$soapClient;
    }

    public function getAct(Request $request)
    {

        $client = self::getSoapClient();

        $user_code = Auth::user()->customer_code;
        $startDate = new DateTime($request->get('date_from'));
        $endDate = new DateTime($request->get('date_to'));
        $startDate = ($startDate->format('Y') . '-' . $startDate->format('m') . '-' . $startDate->format('d'));
        $endDate = ($endDate->format('Y') . '-' . $endDate->format('m') . '-' . $endDate->format('d'));
        $params = new \SoapVar([
            "StartDate" => $startDate,
            'EndDate' => $endDate,
            'AgreementSource' => $user_code
        ], SOAP_ENC_OBJECT);




        $data = base64_decode($client->GetReconciliationAct($params)->return, true);



        Storage::put('public/reports/act-' . $user_code . '.pdf', $data);
        //dd($user_code);

        return asset(Storage::url('public/reports/act-' . $user_code . '.pdf'));
    }

    public function balance()
    {
        $showFilter = false;
        return view('docs.balance', compact('showFilter'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trusted_users = User::where(['parent_id' => Auth::user()->id])->get();
        $showFilter = false;
        return view('catalog.profile', compact('trusted_users', 'showFilter'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $pass = $request['password'];
        $user->pass = $pass;
        $user->password = Hash::make($pass);
        $user->save();
        return true;
    }

    public function setHidePrice(Request $request)
    {

        $user = Auth::user();
        if ($user->hide_price == 1) {
            $user->hide_price = 0;
        } else {
            $user->hide_price = 1;
        }
        $user->save();


        return true;
    }

    public function deleteTrustedUser(Request $request)
    {
        $user_id = $request->get('user_id');
        $user = User::where(['id' => $user_id])->first();
        if ($user != null) {
            $user->delete();
        }
        $trusted_users = User::where(['parent_id' => Auth::user()->id])->get();
        $showFilter = false;
        return view('layouts.profile_body', compact('trusted_users', 'showFilter'));
    }

    public function saveTrustedUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {
            if ($request->get('id') == null) {
                $find_user = User::where(['email' => $request->get('email')])->first();
                if ($find_user != null) {
                    //return '2307';
                    return response()->json(['error' => __('l.email_already_exist')]);
                }
                $password = rand(111111, 999999);

                $newUser = User::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($password),
                    'api_token' => Str::random(60),
                ]);
                $newUser->pass = $password;
                $newUser->customer_code = Auth::user()->customer_code;
                $newUser->parent_id = Auth::user()->id;

                $newUser->api_token = Str::random(36);

                $newUser->user_code = Auth::user()->user_code;
            } else {
                $newUser = User::where(['id' => $request->get('id')])->first();
                $newUser->name = $request->get('name');
                $newUser->email = $request->get('email');
            }

            if ($request->get('purchase_prices') == "true") {
                $newUser->price_view = 1;
            } else {
                $newUser->price_view = 0;
            }
            if ($request->get('mutual_settlements') == "true") {
                $newUser->settlements_view = 1;
            } else {
                $newUser->settlements_view = 0;
            }
            $newUser->trusted = 1;
            $newUser->save();

            if ($request->get('id') == null) {
                $detalis = [
                    'title' => 'Register',
                    'body' => 'Your password ' . $password
                ];
                $detalis =
                    [
                        'title' => 'Запрошуємо вас приєднатися до нашого B2B порталу',
                        'email' => $newUser->email,
                        'password' => $password,
                        'user_code' => $newUser->user_code,
                        'id' => $newUser->id,
                        'url' => $_SERVER['SERVER_NAME'],
                    ];
                $email_admin = setting('email_admin');
                Mail::to($request->get('email'))->cc($email_admin)->send(new RegisterMail($detalis));
            }
            return response()->json(['success' => 'Added new records.']);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }

    public function addTrustedUser()
    {
        $showFilter = false;
        return view('catalog.add_user', compact('showFilter'));
    }

    public function editTrustedUser($id)
    {
        $showFilter = false;
        $user = User::where(['id' => $id])->first();
        return view('catalog.edit_user', compact('user', 'showFilter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
