<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{$_SERVER['SERVER_NAME']}}</title>
    <style>
    .logo-icon {
        float: left;
        display: block;
        margin-left: -20px;
        font-size: 20px;
        font-weight: 200;
        padding: 10px;
    }
    .logo-icon img{
        width: 75%;
    }
</style>
</head>
<body>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-family: Geneva, Arial, Helvetica, sans-serif;">
    <tr bgcolor="#e5f3ff">
        <td  align="center">
            <table width="650px" cellspacing="0" cellpadding="3">
                <tr>
                    <td class="logo-icon" align="center">
                        <a href="" style="">
                            <img src="{{ $details['url'] }}/img/logo.png" alt="">                        
                        </a>
                    </td>

                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#fff" align="center">
            <table width="650px" cellspacing="0" cellpadding="3" class="main container">
                <tr>
                    <td>
                        <div class="password-reset" style="font-family: Geneva, Arial, Helvetica, sans-serif;font-size: 16px; color: #0a0a0a">
                            
                            <p>Сайт: <a href="{{ $details['url'] }}" target="_blank">{{ $details['url'] }}</a></p>
							<p>Ваш ID: {{ $details['user_code'] }}</p>
							<p class="disabled">Логін: {{ $details['email'] }}</p>
							<p>Пароль: {{ $details['password'] }}</p>
                        
                            <p>Вдалих продажів!</p>
                        
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
