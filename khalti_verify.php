<?php
require "config.php";

$data = json_decode(file_get_contents("php://input"), true);

$token = $data['token'];
$amount = $data['amount'];
$user_id = $data['user_id'];

$args = http_build_query([
    'token' => $token,
    'amount' => $amount
]);

$url = "https://khalti.com/api/v2/payment/verify/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Key test_secret_key_xxxxxxxx"
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($status == 200){
    $conn->query("
        INSERT INTO payments(user_id, amount, payment_method, payment_status, transaction_id)
        VALUES('$user_id','$amount','Khalti','Success','$token')
    ");

    echo "Payment Successful (Demo)";
}else{
    echo "Payment Failed";
}
?>
