<?php

// 1. prepare api request to adyen library
// 2. get all payment methods for this shopper
// Change Currency depends on your inquiry

$url = "https://checkout-test.adyen.com/v69/paymentMethods";

$payload = array(
  "merchantAccount" => "PME_ECOM_JP",
  "countryCode" => "JP",
  "channel" => "web",
  "amount" => [
    "value" => 1000,
    "currency" => "JPY",
    ],
    "shopperReference" => "Shopper_002" //enable it when need to show tokanization
);

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
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => $curl_http_header,
        CURLOPT_VERBOSE        => true
    ]
);

$paymentmethodsrequestresponse = json_encode(curl_exec($curl));

curl_close($curl);

//var_dump($paymentmethodsrequestresponse);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet"
     href="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/5.27.0/adyen.css"
     integrity="sha384-2MpA/pwUY9GwUN1/eXoQL3SDsNMBV47TIywN1r5tb8JB4Shi7y5dyRZ7AwDsCnP8"
     crossorigin="anonymous">

     <script src="https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/5.27.0/adyen.js"
     integrity="sha384-YGWSKjvKe65KQJXrOTMIv0OwvG+gpahBNej9I3iVl4eMXhdUZDUwnaQdsNV5OCWp"
     crossorigin="anonymous"></script>

     <script src="https://code.jquery.com/jquery-3.6.0.min.js" charset="utf-8"></script>
  </head>
  <style>
  h1 {
  position: relative;
  padding: 0.25em 0;
}
h1:after {
  content: "";
  display: block;
  height: 4px;
  background: -webkit-linear-gradient(to right, rgb(190, 50, 30), transparent);
  background: linear-gradient(to right, rgb(130, 30, 90), transparent);
}

p {
  position: relative;/*相対位置*/
  padding-left: 1.5em;/*アイコン分のスペース*/
  line-height: 1.4;/*行高*/
  color: black;/*文字色*/
}

p:before {
  font-family: "Font Awesome 5 Free";/*忘れずに*/
  content: "\f111";/*アイコンのユニコード*/
  font-weight: 900;
  position: absolute;/*絶対位置*/
  font-size: 1.4em;/*サイズ*/
  left: 0;/*アイコンの位置*/
  top: -0.2em;/*アイコンの位置*/
}
.example1:before {
  color: #ffa7a1;
}
.example2:before {
  color: #ffdfa1;
}
.example3:before {
  color: #a1ceff;
}
.example4:before {
  color: #b1eab8;
}
  </style>
  <body onload="initialLoad()">
    <h1>Adyen Checkout Integration (/Payments API v69)</h1>

    <div id="kenjis-dropin"></div>

    <script type="text/javascript">

      var availablePaymentMethods = JSON.parse( <?php echo $paymentmethodsrequestresponse; ?> );

      function makePayment(state) {
          const prom_data = state;
          return new Promise(
              function (resolve,reject) {
                  $.ajax(
                      {
                          type: "POST",
                          url: "/processpayment.php",
                          data: prom_data,
                          success: function (response) {
                              resolve(response);
                          }
                      }
                  );
              }
          );

      }

      function showFinalResult(data){
          //console.log(JSON.parse(data.resultCode));
          //var responseData = JSON.parse(data);
          var responseData = data;

          if(responseData.resultCode == "Authorised"){
              alert('PAYMENT SUCCESSFUL!');
              //window.location.href = 'http://127.0.0.1:8080/return.php';
              window.location.href = 'http://127.0.0.1:8080/showResults.php';
          }
      }

      function show3DSResult(data){
        if(data.resultCode == "Authorised"){

            //alert(data.resultCode);

            var response_list = data;
            var response_list_all;

            for (var i=0; i<response_list.length;i++){
              response_list_all += '<li>' + response_list[i] + '</li>';
            }
            //console.log(typeof(data));

            document.write("resultCode: ");
            document.write(data.resultCode);
            document.write("<br>");
            document.write("psp#: ");
            document.write(data.pspReference);
            document.write("<br>");

            document.write("<p>");


            /*
            document.write(data.fraudResult.accountScore);
            document.write("<br>");

            console.log(Object.values(data));
            document.write(Object.values(data));*/

            Object.keys(data).forEach(function(key){
                //キーと値をコンソールに表示する
                //document.write(key + "⇒" + data[key]);
                //document.write("<p>");
                traverseObj(data);

      });

        }else
        {
          //alert('PAYMENT UNSUCCESSFUL!');
          //window.location.href = 'http://127.0.0.1:8080/return.php';
          window.location.href = 'http://127.0.0.1:8080/showResults.php';

        }
        /*
        console.log("makeAdditionalDetails_2(data)");*/
      }

      function makeAdditionalDetails(state){
        //alert('makeAdditionalDetials');

        const detail_data = state;
        return new Promise(
          function (resolve,reject){
            $.ajax(
              {
                type: "POST",
                url: "additionaldetails.php",
                data: detail_data,
                success: function (response) {
                  resolve(response);
                  console.log(response);
                }
              }
            );
            }
            )
          }

          function traverseObj(obj){
            const keys = Object.keys(obj)
            for(let i=0; i<keys.length; ++i){
              const key = keys[i]
              const value = obj[key]
              if(value.constructor === Object && !value.length){
                traverseObj(value)
              }else{
                console.log(`${key} : ${value}`);
                document.write(`${key} : ${value}`);
              }
            }
          }


      var configuration = {
        paymentMethodsResponse : availablePaymentMethods,
        clientKey: "live_JTIYT2ZCPVE6BILTC6STWVZJSEXW6YLP",
        locale: "ja-JP",
        showPayButton: true,
        environment: "live",
        hasHolderName: false,//added on Aug30
        holderNameRequired: false,//added on Aug30
        enableStoreDetails: false,//added on Aug30
        billingAddressRequired: false,//added on Aug30
        threeDS2: {
          challengeWindowSize: '04'
          // '02': ['390px', '400px'] -  The default window size
              // '01': ['250px', '400px']
              // '03': ['500px', '600px']
              // '04': ['600px', '400px']
              //'05': ['100%', '100%']
        },
        onSubmit: (state,dropin)=>{
            makePayment(state.data)
                .then(response => {
                    var responseData = response.action;
                    console.log(response);
                    if(response.action) {
                        dropin.handleAction(response.action);
                    }
                    else{
                        showFinalResult(response);
                    }
                })
                .catch(error => {
                    console.log(error);
                    throw Error(error);
                });
        },
        onAdditionalDetails: (state,dropin)=>{
          //alert('onAdditionalDetails called.');
          $a_params = state.data;
          makeAdditionalDetails(state.data)
            .then(response => {
              var responseDetail = response.action;
              console.log(response);
              if(response.action) {
                //alert('action received.');
                dropin.handleAction(response.action);
                //show3DSResult(response);
              }
              else{
                show3DSResult(response);
              }
            })
            .catch(error => {
              console.log(error);
              throw Error(error);
            });
        },
        paymentMethodsConfiguration: {
            card:{
                hasHolderName: true,
                holderNameRequired: true,
                enableStoreDetails: true,
                name: 'Credit or debit card',
                billingAddressRequired: false,
                //to show installment options
                installmentOptions: {
                  card: {
                    values: [1,2,3,5],
                    plans: ['regular','revolving']
                  },
                  showInstallmentAmounts: true
                }

            },
            threeDS2: {
              challengeWindowSize: '05'
            },
        }
      }

      async function initialLoad(){
        const checkout = await AdyenCheckout(configuration);
        const dropin = checkout.create('dropin').mount('#kenjis-dropin');
        console.log("Be carefull when using onload event to trigger the async function.");
      }

    </script>
  </body>
</html>
