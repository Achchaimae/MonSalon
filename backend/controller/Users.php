
<?php 
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    if(file_exists('../model/User.php')){
        require_once '../model/User.php';
    }else{
        require_once 'model/User.php';
    }

    class Users {
        protected $UserModel;

        public function __construct() 
        {
            $this->UserModel = new User();
        }

        public function login($userReference) {
            $loggedIn = $this->UserModel->login($userReference);
            if($loggedIn){
                return true;
            }else{
                return false;
            }
        }

        public function register($email, $username , $password, $phoneNumber){
            $registerd = $this->UserModel->register($username,$email,$password,$phoneNumber);
            if($registerd) {
                return true;
            }else{
                return false;
            }
        }
        
        

    }


    $User = new User();

    // Login Endpoint   

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        switch ($_POST['type']){
            case 'login':      
                if(isset($_POST['userrefernce'])){
                    // Fetch data 
                    $user = $User->login($_POST['userrefernce']);
                    if($user){
                        echo json_encode(array('status' => 'success', 'user' => $user));
                    }else {
                        http_response_code(400);
                        echo json_encode(array('status' => 'User does not exsit !'));
                        
                    }
                }else{
                    http_response_code(500);
                    echo json_encode("please fill out all inputs");
                }
                break;
            case 'register':
                if(isset($_POST['Email']) || !empty($_POST['username']) || !empty($_POST['password'] || !empty($_POST['phoneNumber']))){
                    $user = $User->register($_POST['username'] , $_POST['Email'] , $_POST['password'], $_POST['phoneNumber']);

                    if($user){
                       $userReference = $User->userReferneceKey($_POST['Email']);
                       return $userReference;
                    }else{
                        http_response_code(404);
                        echo json_encode(array('status' => 'something went wrong!'));
                    }
                }else{
                    echo json_encode("please fill out all inputs");
                }
                break;

            default: 
                echo json_encode("the request param isn't avialble");
                break;
        }
    }else {
        echo json_encode("The request method isn't valid!");
    }
