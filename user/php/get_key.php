<?php
require_once 'r_config.php';
header('Content-Type: application/json');
// send only the Key ID to client (Key Secret must NOT be exposed)
echo json_encode(['key' => $keyId]);

?>