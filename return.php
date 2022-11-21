<?php

$url = "https://ea2064596629378a-PaymentsMadeEasy-checkout-live.adyenpayments.com/checkout/v69/payments/details";

$payments_data = $_POST;

echo $payments_data;

$file = 'paymentsCallResponse.txt';
$current = file_get_contents($file);

$payments_call_resp = json_decode($current, True);

$paymentData = $payments_call_resp['action']['paymentData'];
$MD = $_POST['MD'];
$PaRes = $_POST['PaRes'];


$params = [
    "paymentData" => $paymentData,
    "details" => [
        "MD" => $MD,
        "PaRes" => $PaRes
    ]
];


$curl_http_header = array(
    "X-API-Key: AQEyhmfxJovIYhRKw0m/n3Q5qf3VeIpUAJZETHZ7x3yuu2dYh5cnvT60e4XlLQ+yKZra3zQQwV1bDb7kfNy1WIxIIkxgBw==-S39eP06SsENH4R51dJB5yBqeyOmH4j6ELiJp/Ga97nw=-d(^TtKg6z&m(5DHK",
    "Content-Type: application/json"
);

$curl = curl_init();

curl_setopt_array(
    $curl,
    [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => json_encode($params),
        CURLOPT_HTTPHEADER     => $curl_http_header,
        CURLOPT_VERBOSE        => true
    ]
);

$payment_details_response = curl_exec($curl);

var_dump($payment_details_response);
