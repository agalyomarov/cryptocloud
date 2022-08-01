<?php
$site_address = 'https://2c26-83-149-21-47.eu.ngrok.io';
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTkzOSwiZXhwIjo4ODA1OTI2ODExNn0.awqM_kQ2HI9Htb6qUHBYJxcN1Kciui8ckFDwTSz3aAg';
$shop_id = 'TCgyCDNjhg9fzh7t';
$db_name = 'payment';
$db_user = 'agaly';
$db_pass = '1122';
$db_host = 'localhost';
$sum_1 = 2;
$sum_2 = 2;
$sum_3 = 3;
$sum_4 = 4;
$sum_5 = 5;
$sum_6 = 6;
$sum_7 = 7;
$sum_8 = 8;
$sum_9 = 9;
$sum_10 = 10;
$sum_11 = 11;
$sum_12 = 12;
$sum_13 = 13;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
   echo "Failed to connect to MySQL: " . $mysqli->connect_error;
   exit();
}
$mysqli->query('CREATE TABLE IF NOT EXISTS `payments`(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL,page VARCHAR(30) COLLATE utf8mb4_unicode_ci NOT NULL,invoice_id VARCHAR(30) COLLATE utf8mb4_unicode_ci NOT NULL,status VARCHAR(30) COLLATE utf8mb4_unicode_ci NOT NULL,pay_url VARCHAR(200) COLLATE utf8mb4_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
