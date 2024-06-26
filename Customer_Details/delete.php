<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once 'config.php';
include_once 'class/customers.php';

$database = new Database();
$db = $database->getConnection();
$cusObj = new Customers($db);

$customerTable = 'customer';

$requertMethod = $_SERVER['REQUEST_METHOD'];

if ($requertMethod == 'DELETE') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if(isset($_GET['token']) && !empty($token)){
            $token = $_GET['token'];
            $deleteCustomer = $cusObj->deleteId($customerTable, $id,$token);
        } else{
            $deleteCustomer =json_encode(["status"=>200,"message"=>"Please add token"]);
       }
       
        echo $deleteCustomer;
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requertMethod . ' Method now allowed',
    ];
    header("HTTP/1.0 405 Method now allowed");
    echo json_encode($data);
}
?>