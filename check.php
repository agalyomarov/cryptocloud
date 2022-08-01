<?php
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');

require_once "vendor/autoload.php";
require_once "config.php";

use GuzzleHttp\Client;


$client = new Client;
$query = "SELECT * FROM `payments` where status = 'created'";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
   $response = $client->get('https://cryptocloud.plus/api/v2/invoice/status?uuid=' . $row['invoice_id'], [
      "headers" => [
         "Authorization" => "Token " . $token
      ]
   ]);
   $response = json_decode($response->getBody()->getContents(), true);
   if ($response['status_invoice'] == 'paid') {
      $invoice_id = $row['invoice_id'];
      $mysqli->query("UPDATE `payments` SET `status`= 'paid' WHERE `invoice_id`='$invoice_id'");
   }
}
