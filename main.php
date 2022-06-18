<?php

echo "[+] Stumble Guys Bot - By:Zedds45\n";
echo "[+] Input Cookie: ";
$cok = trim(fgets(STDIN));

$headers = array();
$headers[] = 'authorization: ' . $cok;
$headers[] = 'use_response_compression: true';

echo "\n";
while (1) {
    $gas = curl('http://kitkabackend.eastus.cloudapp.azure.com:5010/round/finishv2/3', null, $headers);
    if (strpos($gas[1], '"User":{"Id"')) {
        $res = json_decode($gas[1]);
        $name = $res->User->Username;
        $trofi = $res->User->SkillRating;
        $mahkota = $res->User->Crowns;
        echo "[" . date("H:i:s") . "] Nick: $name | Trofi: $trofi | Mahkota: $mahkota\n";
    }
}

function curl($url, $post, $headers, $follow = false, $method = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
    $cookies = array();
    foreach ($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    return array(
        $header,
        $body,
        $cookies
    );
}
