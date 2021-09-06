
<?php
if (isset($_POST['paymentInit'])) {
    paymentInit();
}
function paymentInit()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/');
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            "X-Api-Key:e4f2b4dc4a9538131479fd94c84eb10e",
            "X-Auth-Token:c317f9c3980dfde368345eace142711d"
        )
    );
    $payload = array(
        'purpose' => 'FIFA 16',
        'amount' => '2500',
        'currency' => 'INR',
        'phone' => '+919810256713',
        'buyer_name' => 'John Doe',
        'redirect_url' => 'http://www.spacece.co/',
        'send_email' => true,
        'webhook' => 'http://educationfoundation.space/ConsultUs/instamojo_payment/webhook2.php',
        'send_sms' => true,
        'email' => 'contactus@spacece.co',
        'allow_repeated_payments' => false
    );
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    $response = curl_exec($ch);
    curl_close($ch);
    // $response = "result:". $response;
    // $response = json_encode($response);
    // print_r($response);

    echo $response;

    // header('location:'. $response->payment_request->longurl);
    exit();
}


?>