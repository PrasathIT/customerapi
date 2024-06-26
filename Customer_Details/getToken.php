<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once 'config.php';
include_once 'class/customers.php';


$database = new Database();
$db = $database->getConnection();
$cusObj = new Customers($db);

$requertMethod = $_SERVER['REQUEST_METHOD'];

if ($requertMethod == 'GET') {
    $getCustomerDetails = $cusObj->jwtTokenEncode();
    echo $getCustomerDetails;
}

?>

?>