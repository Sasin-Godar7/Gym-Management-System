<?php
session_start();
$user_id = 1; // demo user
?>

<!DOCTYPE html>
<html>
<head>
    <title>Khalti Payment</title>
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
</head>
<body>

<h2>Pay Gym Membership Fee</h2>

<button id="khaltiBtn">Pay with Khalti</button>

<script>
var config = {
    publicKey: "test_public_key_xxxxxxxx",
    productIdentity: "gym001",
    productName: "Sasin Elite Gym Membership",
    productUrl: "http://localhost/gym",
    eventHandler: {
        onSuccess (payload) {

            fetch("khalti_verify.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    token: payload.token,
                    amount: payload.amount,
                    user_id: <?php echo $user_id; ?>
                })
            })
            .then(res => res.text())
            .then(data => {
                alert(data);
            });
        },
        onError (error) {
            console.log(error);
        }
    }
};

var checkout = new KhaltiCheckout(config);

document.getElementById("khaltiBtn").onclick = function () {
    checkout.show({ amount: 1500 * 100 }); // Rs.1500
};
</script>

</body>
</html>
