<?php

// if(isset($_POST['refundInit']))
// {
//     refundInit();
// }

// function refundInit(){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/refunds/');
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
                array("X-Api-Key:e4f2b4dc4a9538131479fd94c84eb10e",
                    "X-Auth-Token:c317f9c3980dfde368345eace142711d"));
    $payload = Array(
        'transaction_id'=> 'partial_refund_1',
        'payment_id' => 'MOJO5a06005J21512197',
        'type' => 'QFL',
        'body' => "Customer isn't satisfied with the quality"
    );
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    $response = curl_exec($ch);
    curl_close($ch); 

    echo $response;
    $response =  json_decode($response);
    // echo $response;
    
    header('location:'. $response->payment_request);

// }


// Result Format
//  201 Created· 400 Bad Request· 401 Unauthorized
// {
//    "refund": {
//         "id": "C5c0751269",
//         "payment_id": "MOJO5a06005J21512197",
//         "status": "Refunded",
//         "type": "QFL",
//         "body": "Customer isn't satisfied with the quality",
//         "refund_amount": "2500.00",
//         "total_amount": "2500.00",
//         "created_at": "2015-12-07T11:01:37.640Z"
//     },
//     "success": true
// }

?>