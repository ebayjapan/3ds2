<?php
//print_r($_POST);
$url = "https://ea2064596629378a-PaymentsMadeEasy-checkout-live.adyenpayments.com/checkout/v69/payments/details";

$payments_data = $_POST;

//echo $payments_data;

$additional_data = [
    //'reference' => 'KenjiW001',
];

$final_payment_data = array_merge($payments_data, $additional_data);

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
        CURLOPT_POSTFIELDS     => json_encode($final_payment_data),
        CURLOPT_HTTPHEADER     => $curl_http_header,
        CURLOPT_VERBOSE        => true
    ]
);

$payments_response = curl_exec($curl);
$file = 'paymentsDetailsCallResponse.txt';
$current = $payments_response;
file_put_contents($file, $current);

header('Content-Type: application/json');
echo $payments_response;

/*
if (array_key_exists("action", $payments_response)){
  $result['action'];
}
else{
  $result['resultCode'];
}*/

curl_close($curl);

//header("location:index.php");
