<?php 
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    if(file_exists('../model/Reservation.php')){
        require_once '../model/Reservation.php';
    }else{
        require_once 'model/Reservation.php';
    }
    class Reservations {
        protected $ReservationModel;

        public function __construct()
        {
            $this->ReservationModel = new Reservation();
        }
        // === Add a reservation === //
        public function setReservation($customerReference, $reservationService, $reservationDate, $barberID,$reservatioDateDay) 
        {
            $setReservation = $this->ReservationModel->setReservation($customerReference, $reservationService, $reservationDate, $barberID, $reservatioDateDay);
            if($setReservation) {
                return true;
            }else{
                return false;
            }
        }
        // === Update a reservation === //
        public function updateReservation($reservationID, $reservationDate, $reservationDay) 
        {
            $deleteReservation = $this->ReservationModel->deleteReservation($reservationID, $reservationDate, $reservationDay);
            if($deleteReservation){
                return $deleteReservation;
            }else{
                return false;
            }
        }
        
        // === Delete(cancel) a reservation === //
        public function deleteReservation($reservationID, $reservationDate, $reservationDay) 
        {
            $deleteReservation = $this->ReservationModel->deleteReservation($reservationID, $reservationDate, $reservationDay);
            if($deleteReservation){
                return $deleteReservation;
            }else{
                return false;
            }
        }
        // === displayReservations === // 
        public function displayReservations() 
        {
            return $this->ReservationModel->displayReservations();
        }
        // === display Avialble hours === // 
        public function displayAvialbleHours($day)
        {
            return $this->ReservationModel->displayAvialbleHours($day);
        }
        // === Display User's reservations === // 
        public function displayUserReservations($userToken) {
            return $this->ReservationModel->displayUserReservations($userToken);
        }
       


    }

    $Reservation = new Reservations();

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        switch($_POST["type"]){

            case "setReservation":

                if(isset($_POST["reservationDate"]) && isset($_POST["reservationService"]) && isset($_POST["customerRefernce"]) && isset($_POST["barberID"]) && isset($_POST['reservatioDateDay'])){

                    $setReservation = $Reservation->setReservation($_POST["customerRefernce"],$_POST["reservationService"],$_POST["reservationDate"],$_POST["barberID"], $_POST['reservatioDateDay']);

                    if($setReservation){
                        http_response_code(201);
                        echo json_encode(array("status" => "reservation has been made seccessfully"));
                    }else {
                        http_response_code(500);
                        echo json_encode(array("status" => "reservation setting has faild"));
                    }
                }
                break;

            case "updateReservation":

                if(isset($_POST["reservationID"])){

                    $updateReservation = $Reservation->deleteReservation((int)$_POST["reservationID"], $_POST['reservationDate'], $_POST['reservationDateDay']);

                    if($updateReservation){
                        http_response_code(200);
                        echo json_encode(array("status" => "reservation has been deleted seccessfully"));
                    }else {
                        http_response_code(500);
                        echo json_encode(array("status" => "reservation setting has faild"));
                    }
                }
                break;

            case "deleteReservation":
                if(isset($_POST["reservationID"])){

                    $updateReservation = $Reservation->deleteReservation((int)$_POST["reservationID"], $_POST['reservationDate'], $_POST['reservationDateDay']);

                    if($updateReservation){
                        http_response_code(200);
                        echo json_encode(array("status" => "reservation has been deleted seccessfully"));
                    }else {
                        http_response_code(500);
                        echo json_encode(array("status" => "reservation setting has faild"));
                    }
                }
                break;
                case "displayUserReservations":
                    
                    if(isset($_POST['userToken'])){
                        $userReservations = $Reservation->displayUserReservations($_POST['userToken']);
                        if($userReservations){
                            http_response_code(200);
                            echo json_encode(array("reservations" => $userReservations));
                        }else {
                            http_response_code(400);
                            echo json_encode("There's no reservations !");
                        }
                    }else{
                        echo json_encode('The POST params are not avialble , please try another parameters');
                        http_response_code(404);
                    }
                    break;
            default :
                $Reservation->displayReservations();
                break;
                
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if(isset($_GET['reservationDay'])){
            $avialbleReservationHours = $Reservation->displayAvialbleHours($_GET['reservationDay']);
            if($avialbleReservationHours){
                http_response_code(200);
                echo json_encode(array("hours" => $avialbleReservationHours));
            }else {
                http_response_code(400);
                echo json_encode("There's no avialble hours !");
            }
        }else{
            echo json_encode('The GET params are not avialble , please try another parameters');
            http_response_code(404);
        }
    }