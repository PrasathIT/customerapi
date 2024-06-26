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

$customerTable = 'customers';
$requertMethod = $_SERVER['REQUEST_METHOD'];

if ($requertMethod == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            $getCustomerDetails = $cusObj->selectDataById($customerTable, $id,$token);
        }else{
            $getCustomerDetails =json_encode(["status"=>200,"message"=>"Please add token"]);
        }
    } else {
        if(isset($_GET['token'])){
                $token = $_GET['token'];
                $getCustomerDetails = $cusObj->fetchAllData($customerTable,$token);
        }
        else{
             $getCustomerDetails =json_encode(["status"=>200,"message"=>"Please add token"]);
        }
    }
    echo $getCustomerDetails;
}
// else{
//     $data = [
//         'status' => 405,
//         'message' => $requertMethod. ' Method now allowed',
//     ];
//     header("HTTP/1.0 405 Method now allowed");
//     echo json_encode($data); exit;
// }

//Insert function

if ($requertMethod == 'POST') {
    $formData = json_decode(file_get_contents("php://input"), true);
    if (empty($formData)) {
        // echo "if";
        $insertcustomerRecord = $cusObj->insertcustomerData($customerTable, $_POST);
    }else{
        // echo "else";
        $insertcustomerRecord = $cusObj->insertcustomerData($customerTable, $formData);
    }
    echo $insertcustomerRecord;
} 
// else {
//     $data = [
//         'status' => 405,
//         'message' => $requertMethod . ' Method now allowed',
//     ];
//     header("HTTP/1.0 405 Method now allowed");
//     echo json_encode($data);
// }
?>