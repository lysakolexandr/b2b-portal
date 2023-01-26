<?php

namespace App\Actions\Fortify;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\RegisterMail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Mail as Mail;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $find_user = User::where(['email' => $input['email']])->first();
        if ($find_user!=null) {
            return '2307';
        }

        $newUser = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'api_token' => Str::random(36),
        ]);

        $newUser->customer_code = $input['customer_code'];

        $newUser->api_token = Str::random(36);

        $newUser->pass = $input['pass'];
        $newUser->user_code = $input['user_code'];
        $newUser->save();

        $detalis =
        [
            'title'=>'Запрошуємо вас приєднатися до нашого сервісу B2B порталу',
            'email'=>$newUser->email,
            'password'=>$input['pass'],
            'user_code'=>$newUser->user_code,
            'id'=>$newUser->id,
            'url'=>$_SERVER['SERVER_NAME'],
        ];
        // $detalis = [
        //     'title' => 'Register',
        //     'body' => 'Your login '.$newUser->email.'<br>Your password '.$input['password'].'<br> Your ID '.$newUser->user_code.''
        // ];

        $email_admin = setting('email_admin');
        Controller::setMailSettings();
        Mail::to($input['email'])->cc($email_admin)->send(new RegisterMail($detalis));

        die();
    }
}
