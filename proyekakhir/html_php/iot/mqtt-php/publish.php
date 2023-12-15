<?php
require("phpMQTT.php");
require("config.php");
$topic="iot/G.211.21.0058";

$message = @$_GET['message'];

if(empty($message)) { $message = "TEST"; }

//MQTT client id to use for the device. "" will generate a client id automatically
$mqtt = new bluerhinos\phpMQTT($host, $port, "ClientID".rand());

if ($mqtt->connect(true,NULL,$username,$password)) {
  $mqtt->publish($topic,$message, 0);
  $mqtt->close();
}else{
  echo "Fail or time out
";
}

echo "[$topic] mengirim messages: $message ";



?>

<script>

window.history.back();
</script>
