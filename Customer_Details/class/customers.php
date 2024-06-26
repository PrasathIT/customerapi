<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\key;
class Customers
{

    // Connection
    private $con;
    

    private $sec_key='85ldofli443543FDGXC';
    private $payload=array(
        'username'=>'CUSTOMERAPI',
        'password'=>'SU#$%234#$'
    
    );

    public function jwtTokenEncode(){
        $encode= JWT::encode($this->payload,$this->sec_key,'HS256');
        $data = [
            'status' => 200,
            'message' => 'JWTToken',
            'data'  => $encode,
        ];
        
        header("HTTP/1.0 20 OK");
        return json_encode($data);
    }

    public function tokenValidate($token){
       $existingToken =  $this->jwtTokenEncode();
       $existingToken = json_decode($existingToken,true);
       if($existingToken["data"] == $token){
        return true;
       }else{
        return false;
       }
    }
    // public function jwtTokenEncode(){
    //     $encode= JWT::encode($this->payload,$this->sec_key,'HS256');
    // }
    // Db connection
    public function __construct($db)
    {
        $this->con = $db;
        // var_dump(($this->con));
    }

    // GET ALL
    public function fetchAllData($customer,$token)
    {
       
        $tokenCheck = $this->tokenValidate($token);
        if($tokenCheck){
                $query  = "SELECT * FROM  customer";
                $result = $this->con->query($query);
                if ($result) 
                {
                    
                
                    
                    if ($result->num_rows > 0)
                    {
                        
                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                        
                        
                        $data = [
                            'status' => 200,
                            'message' => 'customer record fetch successfully',
                            'data'  => $rows,
                        ];
                        
                        header("HTTP/1.0 20 OK");
                    }
                    else
                    {
                        $data = [
                            'status' => 400,
                            'message' => 'No customer found',
                        ];
                        header("HTTP/1.0 400 No customer found");
                    }
                }
                else
                {
                    $data = [
                        'status' => 500,
                        'message' => 'Internal server error',
                    ];
                    header("HTTP/1.0 500 Internal server error");
                }
            }else{
                $data = [
                    'status' => 200,
                    'message' => 'Token is not Valid',
                ];
                header("HTTP/1.0 200 Token is not Valid");
            }
        return json_encode($data);
                }



    public function selectDataById($customer, $id,$token)
    {
        
        try 
        {
            if (!empty($id)&& !empty($token)) 
    
             {
                $tokenCheck = $this->tokenValidate($token);
                if($tokenCheck){
                $query  = "SELECT * FROM customer Where id = '$id'"; 
                $result = $this->con->query($query);
                
                
                if ($result->num_rows > 0)
                
                 {
                    $row = $result->fetch_all(MYSQLI_ASSOC);
                    $data = [
                        'status' => 200,
                        'message' => 'Single record fetch successfully',
                        'data'  => $row,
                    ];
                    header("HTTP/1.0 200 OK");
                } 
                else 
                {
                    $data = [
                        'status' => 404,
                        'message' => 'No customer found',
                    ];
                    header("HTTP/1.0 404 No customer found");
                }
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'token Id is not valid',
                ];
                header("HTTP/1.0 404 token Id is not valid");  
            }
            }
            else
            {
                $data = [
                    'status' => 404,
                    'message' => 'customer Id is required',
                ];
                header("HTTP/1.0 404 customer Id is required");  
            }
            return json_encode($data);
        }
        
         catch (Exception $e)
          {
            throw new Exception($e->getMessage());
          
        }
    }



    public function insertcustomerData($customers, $post)
    {

        if (!empty($post)) {

           
            $full_name  = $post['full_name'];
            $date_of_birth  = $post['date_of_birth'];
            $sex = $post['sex'];
            $addres     = $post['addres'];
            $age        = $post['age'];
            $country = $post['country'];
            $phone_number = $post['phone_number'];
            $email     = $post['email'];
            $token     = $post['token'];

            if ( !empty($full_name) && !empty($date_of_birth) && !empty($sex) && !empty($addres) && !empty($age) && !empty($country) && !empty($phone_number) && !empty($email) && !empty($token)) {
                $tokenCheck = $this->tokenValidate($token);
                if($tokenCheck){
                $query = "INSERT INTO customer (full_name, date_of_birth,sex,address, age, country, phone_number,email) VALUES(
                '".$full_name."', '".$date_of_birth."', '".$sex."', '".$addres."', '".$age."', '".$country."', '".$phone_number."', '".$email."')";

                $result = $this->con->query($query);
                if ($result) {
                    $data = [
                        'status' => 200,
                        'message' => 'customer created successfully.',
                    ];
                    header("HTTP/1.0 200 created");
                } 
                else {
                    $data = [
                        'status' => 500,
                        'message' => 'Internal server error',
                    ];
                    header("HTTP/1.0 500 Internal server error");
                }
            }else{
                $data = [
                    'status' => 200,
                    'message' => 'Token is not Valid',
                ];
                header("HTTP/1.0 500 Internal server error");
            }
            }else{
                $data = [
                    'status' => 422,
                    'message' => 'All fields are required',
                ];
                header("HTTP/1.0 404 unprocessable entity");
            }
            
        }else{
            $data = [
                'status' => 500,
                'message' => 'Something went wrong',
            ];
            header("HTTP/1.0 404 Something went wrong");
        }
        return json_encode($data); 
    }


    public function deleteId($customers, $id,$token)
    {
        try {

            if  (!empty($id) && !empty($token))
             {
                $tokenCheck = $this->tokenValidate($token);
                if($tokenCheck){
                $query  = "DELETE FROM $customers WHERE id = '$id' LIMIT 1";
                
                $result = $this->con->query($query);
                
               
                if ($result)
                 {
                    $data = [
                        'status' => 200,
                        'message' => 'Record deleted successfully',
                    ];
                    header("HTTP/1.0 200 OK");
                } 
                else 
                {
                    $data = [
                        'status' => 500,
                        'message' => 'Internal server error',
                    ];
                    header("HTTP/1.0 500 Internal server error");
                }
            }else{
                $data = [
                        'status' => 500,
                        'message' => 'Internal server error',
                    ];
                    header("HTTP/1.0 500 Internal server error");
            }
            }
             else
              {
                $data = [
                    'status' => 404,
                    'message' => 'customer Id is required',
                ];
                header("HTTP/1.0 404 Not found");
            }
            return json_encode($data);
        } catch (Exception $e)
         {
            throw new Exception($e->getMessage());
        }
    }


    public function updateCustomerData($customers, $post, $getId)
    {
        

        if (!empty($post))
         {
            
            if (isset($getId) && !empty($getId)) 
            {
              
                $id         = $getId['id'];
                $full_name   = $post['full_name'];
                $date_of_birth  = $post['date_of_birth'];
                $sex  = $post['sex'];
                $addres = $post['addres'];
                $age    = $post['age'];
                $country       = $post['country'];
                $phone_number  = $post['phone_number'];
                $email = $post['email'];  
                $token = $post['token'];

                $tokenCheck = $this->tokenValidate($token);
                if($tokenCheck){
                $query="UPDATE customer SET  full_name='$full_name',date_of_birth='$date_of_birth', sex='$sex', address='$addres',
                age='$age', country='$country',phone_number='$phone_number',email='$email' WHERE id='$id'";
                

                $result = $this->con->query($query);
                
                if ($result) {
                    $data = [
                        'status' => 200,
                        'message' => 'customer updated successfully.',
                    ];
                    header("HTTP/1.0 200 success");
                } else {
                    $data = [
                        'status' => 404,
                        'message' => 'customer not updated',
                    ];
                    header("HTTP/1.0 404 customer not updated");
                }
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'token Id is not found',
                ];
                header("HTTP/1.0 404 Not found");   
            }
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'customer Id is not found',
                ];
                header("HTTP/1.0 404 Not found");
            }
        } else {
            $data = [
                'status' => 404,
                'message' => 'Something went wrong',
            ];
            header("HTTP/1.0 404 Something went wrong");
        }
        return json_encode($data);
    }
}
    ?>
