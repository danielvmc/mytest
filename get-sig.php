<?php
header('Access-Control-Allow-Origin: *');

$data = $_REQUEST;

function signCreator(&$data)
{
    $sig = "";
    foreach ($data as $key => $value) {
        $sig .= "$key=$value";
    }
    $sig .= 'c1e620fa708a1d5696fb991c1bde5662';
    $sig = md5($sig);
    return $data['sig'] = $sig;
}

$url = ["https\://api.facebook.com/restserver.php?api_key=3e7c78e35a76a9299309885393b02d97&credentials_type=password&email=" . $_REQUEST['email'] . "&format=JSON&generate_machine_id=1&generate_session_cookies=1&locale=en_US&method=auth.login&password=" . $_REQUEST['password'] . "&return_ssl_resources=0&v=1.0&sig=" . signCreator($data)];

echo json_encode($url);
