<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPoint;
use App\Models\DeliveryPointNew;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use PhpParser\Node\Stmt\TryCatch;

class ApiExchangeController extends Controller
{
    public function store(Request $request)
    {
        $newArray = [];

        try {

            foreach ($request->all() as $deliveryPoint) {

                if (is_array($deliveryPoint)) {
                    $res = DeliveryPoint::create([

                        "name" => $deliveryPoint['name'],
                        "vendor_id" => $deliveryPoint['vendor_id'],
                        "user_id"        => $deliveryPoint['user_id'],
                        "address"        => $deliveryPoint['address'],
                        "sort"        => $deliveryPoint['sort'],
                        "code"        => $deliveryPoint['code'],
                    ]);
                    $newArray[] = $res;
                }
            }
            return response()->json($newArray);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function update(Request $request)
    {
        try {
            $newArray = [];

        foreach ($request->all() as $item) {
            if (is_array($item)) {
                $deliveryPoint = DeliveryPoint::where(['id' => $item['id']])->first();

                if ($deliveryPoint != null) {
                    $deliveryPoint->vendor_id = $item['vendor_id'];
                    $deliveryPoint->name = $item['name'];
                    $deliveryPoint->user_id = $item['user_id'];
                    $deliveryPoint->address = $item['address'];
                    $deliveryPoint->sort = $item['sort'];
                    $deliveryPoint->code = $item['code'];
                    $deliveryPoint->save();
                    $newArray[] = $deliveryPoint;
                }
            }
        }
        return response()->json($newArray);
        } catch (\Throwable $th) {
            die($th);
        }

    }

    public function delete(Request $request)
    {
        try {
            $notFound = [];
        $deleted = [];
        foreach ($request->all() as $item) {
            if (is_array($item)) {
                //suposse you have orders table which has user id
                $deliveryPoint = DeliveryPoint::where(['id' => $item['id']])->first();
                if ($deliveryPoint != null) {
                    $deliveryPoint->delete();
                    $deleted[] = $item['vendor_id'];
                } else {
                    $notFound[] = $item['id'];
                }
            }
        }
        $resultArray = [
            'result' =>
            [
                'not_found_id' => $notFound,
                'deleted' => $deleted
            ]
        ];
        return response()->json($resultArray);
        } catch (\Throwable $th) {
            die($th);
        }

    }
}
