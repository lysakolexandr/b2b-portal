<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Setting;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function callAction($method, $parameters)
    {
        //dd('23');
        $this->setMailSettings();

        return parent::callAction($method, $parameters);
    }


    public static function setMailSettings(){

        $smtp_for_sanding = setting('smtp_for_sanding');
        $email_for_sanding = setting('email_for_sanding');
        $pass_for_sanding = setting('pass_for_sanding');
        $name_for_sanding = setting('name_for_sanding');

        $mailers = ['smtp' => [
            'transport' => 'smtp',
            'host' => $smtp_for_sanding,
            'port' => 587,
            'username' => $email_for_sanding,
            'password' => $pass_for_sanding,
            'timeout' => null,
            'auth_mode' => null,
        ],


        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],];

        $from = [
            'address' => $email_for_sanding,
            'name' => $name_for_sanding,
        ];

        config(['mail.mailers' => $mailers]);
        config(['mail.from' => $from]);
    }

}
