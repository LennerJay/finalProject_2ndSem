<?php 
require "database.php";
    class Backend{
        public function fnGetUser(){
            try{
                $db = new Database;
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare('SELECT `userid`,`email`,`password`,`role`,`isactive` FROM `tbl_user` ');
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "Database Connection Error";
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function fnRegister($username,$firstname,$lastname,$street,$city,$state,$zipcode,$age,$gender,$email,$password,$files,$userid){
            try{
                $db= new Database;
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare('call sp_saveUser(?,?,?,?,?,?,?,?,?,?,?,?,?)');
                    $stmt->execute(array($username,$firstname,$lastname,$street,$city,$state,$zipcode,$age,$gender,$email,$password,$files,$userid));
                    $db->closeConnection();
                    return 1;
                }else{
                    return "Database Connection Error";      
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function updateQuantity($user_id,$product_id,$quantity){
            $db = new Database;
            if($db->getStatus()){
                $stmt = $db->getCon()->prepare("UPDATE `tbl_purchase` SET quantity = ?  WHERE (user_id = ? && product_id = ?)");
                $stmt->execute(array($quantity,$user_id,$product_id));
                $db->closeConnection();
                return 1;
            }else{
                return "Database Connection Error";      
            }
        }
        public function fnGetPurchasedProduct($user_id){
            try{
                $db = new Database;
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare("SELECT * FROM `tbl_purchase` WHERE user_id = ?");
                    $stmt->execute(array($user_id));
                    $result = $stmt->fetchAll();
                    $db->closeConnection();
                    return json_encode($result);
                }else{
                    return "Database Connection Error";      
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function fnPurchase($user_id,$product_id,$quantity,$status){
            try{
                $db = new Database;
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare("CALL sp_savePurchase(?,?,?,?)");
                    $stmt->execute(array($user_id,$product_id,$quantity,$status));
                    $db->closeConnection();
                    return 1;
                }else{
                    return "Database Connection Error";      
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function addToCart($user_id,$product_id,$status){
            try{
                $db = new Database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare("CALL sp_addToCart(?,?,?)");
                    $stmt->execute(array($user_id,$product_id,$status));
                    $db->closeConnection();
                    return 1;
                }else{
                    return "Database Connection Error";
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function getShoppingCart($user_id){
            try{
                $db = new Database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare('call sp_getShoppingCart(?)');
                    $stmt->execute(array($user_id));
                    $result = $stmt->fetchAll();
                    return json_encode($result);
                }else{
                    return "Database Connection Error";
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function getProduct($productId){
            try{
                $db = new Database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare('call sp_getProduct(?)');
                    $stmt->execute(array($productId));
                    $result = $stmt->fetchAll();
                    return json_encode($result);
                }else{
                    return "Database Connection Error";
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function addProduct($brand,$name,$description,$specification,$oldPrice,$newPrice,$stock,$sold,$img,$category){
            try{
                $db = new Database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare('call sp_addProduct(?,?,?,?,?,?,?,?,?,?)');
                    $stmt->execute(array($brand,$name,$description,$specification,$oldPrice,$newPrice,$stock,$sold,$img,$category));
                    $db->closeConnection();
                    return 1;
                }else{
                    return "Database Connection Error";
                }
            }catch(PDOException $e){
                return $e;
            }
        }
        public function login($email,$password){
            try{
                $db = new Database();
                if($db->getStatus()){
                    $tmp = md5($password);
                    $stmt = $db->getCon()->prepare('call sp_checkUser(?,?)');
                    $stmt->execute(array($email,$tmp));
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($result['ret'] === 1){
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $tmp;
                        $_SESSION['user_id'] = $result['userid'];
                        $_SESSION['role'] = $result['role'];
                        $db->closeConnection();
                    }
                    return json_encode($result);
                  
                }else{
                    return "Database Connection Error";
                }
            }catch(PDOException $e){
                return $e . " Database Connection Error";
            }
        }
        public function register($username,$password,$email){
            try{
                $db = new Database();
                if($db->getStatus()){
                    $stmt = $db->getCon()->prepare('Call sp_saveUser(?,?,?)');
                    $stmt->execute(array($username,md5($password),$email));
                    $db->closeConnection();
                    return 1;

                }else{
                    return "Database Connection Error";
                }

            }catch(PDOException $e){
                return $e . "Database Connection Error";
            }
        }
    }
?>