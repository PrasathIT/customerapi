<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config.php';
include_once 'class/customers.php';

$database = new Database();
$db = $database->getConnection();
$empObj = new Customers($db);

$customerTable = 'customers';

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'PUT') {
    
    $formData = json_decode(file_get_contents("php://input"), true);
      
    $updateCustomerData = $empObj->updateCustomerData($customerTable, $formData, $_GET);
    echo $updateCustomerData;
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method not allowed',
    ];
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode($data);
}
?>