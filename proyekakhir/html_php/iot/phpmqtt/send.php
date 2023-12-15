<?php
require("phpMQTT.php");

$server = "riset.revolusi-it.com"; // Ganti dengan alamat broker MQTT Anda
$port = 1883; // Ganti dengan port broker MQTT Anda
$username = "usm"; // Ganti dengan username broker MQTT Anda (jika ada)
$password = "rahasia234"; // Ganti dengan password broker MQTT Anda (jika ada)
$client_id = "phpMQTT-0058"; // Ganti dengan ID klien yang unik

$mqtt = new phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
    $topic = "iot/G.211.21.0058"; // Ganti dengan topik MQTT yang Anda inginkan
    $message = "Hello, MQTT!"; // Pesan yang akan dikirim

    $mqtt->publish($topic, $message, 0);
    $mqtt->close();
} else {
    echo "Gagal terhubung ke broker MQTT!";
}
?>

