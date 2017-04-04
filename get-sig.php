<?php
header('Access-Control-Allow-Origin: *'); //I have also tried the * wildcard and get the same response
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

$data = $_POST;

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

$url = ["url" => "https://api.facebook.com/restserver.php?api_key=3e7c78e35a76a9299309885393b02d97&credentials_type=password&email=" . $_POST['email'] . "&format=JSON&generate_machine_id=1&generate_session_cookies=1&locale=en_US&method=auth.login&password=" . $_POST['password'] . "&return_ssl_resources=0&v=1.0&sig=" . signCreator($data)];

header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');
echo json_encode($url);
