<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Mail as Mail;
use Illuminate\Http\Request;
use App\Mail\RepairPassword;

class CustomController extends Controller
{
    public function sendPassword(Request $request){
        $input = $request->collect();
        //dd($input);

        $find_user = User::where(['email' => $input['email']])->first();
        if ($find_user!=null) {
            $detalis = [
                'title' => 'Repair password',
                'body' => 'Your password '.$find_user['pass']
            ];
            $detalis =
        [
            'title'=>'Запрошуємо вас приєднатися до нашого сервісу B2B порталу',
            'email'=>$input['email'],
            'password'=>$find_user['pass'],
            'user_code'=>$find_user['user_code'],
            'id'=>$find_user['id'],
            'url'=>$_SERVER['SERVER_NAME'],
        ];
            Mail::to($input['email'])->cc(setting('email_admin'))->send(new RepairPassword($detalis));

            //dd($result);

            return redirect('login')->with('status', __('l.your_password_was_send_to_your_email'));
        }else{
            return redirect('login');
        }
    }
}
