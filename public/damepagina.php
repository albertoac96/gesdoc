<?php

$PcURL = "http://calib.org/calib/calib.cgi";


$LoCURL = curl_init($PcURL);

$LcEncode = '';
foreach($_POST as $LcClave => $LcValor) {
  $LcEncode .= urlencode($LcClave).'='.urlencode($LcValor).'&';
}
$LcEncode = substr($LcEncode, 0, strlen($LcEncode)-1);

curl_setopt($LoCURL, CURLOPT_POSTFIELDS,  $LcEncode);
curl_setopt($LoCURL, CURLOPT_HEADER, 0);
curl_setopt($LoCURL, CURLOPT_POST, 1);
//curl_setopt($LoCURL, CURLOPT_RETURNTRANSFER, true);
curl_exec($LoCURL);
curl_close($LoCURL);

?>