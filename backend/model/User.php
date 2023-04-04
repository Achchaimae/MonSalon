<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    if(file_exists('../core/Database.php')){
        require_once '../core/Database.php';
    }else{
        require_once 'core/Database.php';
    }


    class User extends Database{
        

        // === Login === //

        public function login($userRefernce) {

            $query = $this->Dbh->prepare("SELECT * FROM users WHERE user_refernce = :userrefernce");

            $query->bindValue(':userrefernce', $userRefernce, PDO::PARAM_STR);
     
            $query->execute();
            if(!$query->rowCount() > 0){
                error_log("Error in banding the params : " . $query->errorInfo());
                return false;
            }else {
            
                return true;
                    
            }            
            
        }
        
        
        
        public function register($userName, $userEmail, $userPwd,$userPhoneNmuber){
            
            
            $sql = "INSERT INTO users(user_name,user_email,user_pwd,user_refernce,user_role,user_phone_number) VALUES (:username, :useremail, :userpwd, :userrefernce,:user_role,:user_phone_number)";

            $query = $this->Dbh->prepare($sql);

            $query->bindValue(":username", $userName, PDO::PARAM_STR);
            $query->bindValue(":useremail", $userEmail, PDO::PARAM_STR);
            $query->bindValue(":userpwd", $userPwd, PDO::PARAM_STR);
            $query->bindValue(":user_phone_number", (int)$userPhoneNmuber, PDO::PARAM_INT);
            $query->bindValue(":userrefernce" , $this->generateKey(), PDO::PARAM_STR);
            $query->bindValue(":user_role", 'client', PDO::PARAM_STR);

            $userRegisterd = $query->execute();
            
            if(!$userRegisterd){
                return false;
            }else{
                return $userRegisterd;

            }
            
        }

        public function userReferneceKey($userEmail) {
            $sql = "SELECT * FROM users WHERE user_email = :user_email";
            $query = $this->Dbh->prepare($sql);

            $query->bindValue(':user_email', $userEmail, PDO::PARAM_STR);
            if($query->execute()){
                echo json_encode($query->fetch(PDO::FETCH_OBJ));
            }else{
                echo json_encode("something went wrong , please try later");
            }
        }
        
        // === Generate the login reference key === // 

        public function generateKey() {
            $str = "78LmNOPAQRsUvwXYZ";
            $strLength = strlen($str);

            for($i = 0; $i < mt_rand(10,14); $i++){
                $baseRandomString []= $str[mt_rand(rand(0,10) , $strLength - 1)];
            }

            return implode($baseRandomString);

        }
        

        
    }

    