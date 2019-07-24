<?php
require "db.class.php";

class User{
    public function userSignup($firstname, $lastname, $username, $password, $confirm_password){
        $dbconn = new DbConnection();
        $db = $dbconn->dbConn();
        $errMessage = "";
        try{
            // Check first name and last name
            if(empty(trim($firstname))){
                $errMessage = "Please enter your first name" . "<br>";
                echo $errMessage;
                return;
            }elseif(empty(trim($lastname))){
                $errMessage = "Please enter your last name" . "<br>";
                echo $errMessage;
                return;
            }

            // Check user name
            if(empty(trim($username))){
                $errMessage = "Please enter a username" . "<br>";
                echo $errMessage;
                return;                
            }else{
                $stmt = $db->prepare("SELECT id FROM users WHERE username=:username");
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = trim($username);
                
                if($stmt->execute()){
                    $row = $stmt->rowCount();
                    if($row == 1){
                        $errMessage = "The username is already taken!" . "<br>";
                        echo $errMessage;
                        return;
                    }else{
                        $username = trim($username);
                    }
                }else{
                    echo "Something goes wrong" . "<br>";
                }
                unset($stmt);
            }

            // Check password
            if(empty(trim($password))){
                $errMessage = "Please enter a password" . "<br>";
                echo $errMessage;
                return;
            }elseif(strlen(trim($password)) < 6){
                $errMessage = "The password must have at least 6 characters" . "<br>";
                echo $errMessage;
                return;
            }else{
                $password = trim($password);
            }

            // Check confirm password
            if(empty(trim($confirm_password))){
                $errMessage = "Please confirm password" . "<br>";
                echo $errMessage;
                return;
            }else{
                $confirm_password = trim($confirm_password);
                if(empty($errMessage) && ($password != $confirm_password)){
                    $errMessage = "Confirm password did not match" . "<br>";
                    echo $errMessage;
                    return;
                }
            }

            // Check if no error
            if(empty($errMessage)){
                $sql = "INSERT INTO users(firstname, lastname, username, password, img) VALUES(:firstname, :lastname, :username, :password, :img)";
                $stmt = $db->prepare($sql);
                // echo "success";
                $stmt->bindParam(":firstname", $param_firstname, PDO::PARAM_STR);
                $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                // echo $param_username;
                $stmt->bindParam(":password", $param_hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(":img", $param_img, PDO::PARAM_STR);

                $param_firstname = trim($firstname);
                $param_lastname = trim($lastname);
                $param_username = trim($username);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $param_hashed_password = trim($hashed_password);
                // echo $param_hashed_password;
                $param_img = trim($img);
                // echo $param_img;
                if($stmt->execute()){
                    header("Location: signin.php");
                    // echo $stmt->error;
                }else{
                    echo "Something went wrong!! Try again later" . "<br>";
                    return;
                }

                unset($stmt);
            }
            unset($db);
            
        }catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }



    public function userSignin($username, $password){
        $dbconn = new DbConnection();
        $db = $dbconn->dbConn();
        $errMessage = "";
        try{
            // Check username
            if(empty(trim($username))){
                $errMessage = "Please enter username";
                echo $errMessage;
                return;
            }else{
                $username = trim($username);
            }

            // Check password
            if(empty(trim($password))){
                $errMessage = "Please enter password";
                echo $errMessage;
                return;
                
            }else{
                $password = trim($password);
            }

            
            if(empty(trim($errMessage))){

                // Remember to select the column you wanna fetch
                $stmt = $db->prepare("SELECT * FROM users WHERE username=:username");
                $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
                $param_username = trim($username);

                if($stmt->execute()){
                    $count = $stmt->rowCount();
                    if($count == 1){
                        $row = $stmt->fetch();
                        $id = $row['id'];
                        $firstname = $row['firstname'];
                        $lastname = $row['lastname'];
                        $username = $row['username'];
                        // Handle hashed password
                        $hashed_password = $row['password'];
                        $img = $row['img'];

                        if(password_verify($password, $hashed_password)){

                            session_start();
                            $_SESSION['user'] = [
                                'id' => $id,
                                'fb' => false,
                                'name' => $firstname . " " . $lastname,
                                // 'username' => $username,
                                'img' => $img
                            ];
                            header("Location: index.php");
                        }
                        else{
                            $errMessage = "Incorrect password";
                            echo $errMessage;
                            return;
                        }
                    }else{
                        $errMessage = "No account found with that username";
                        echo $errMessage;
                        return;
                    }
                }else{
                    echo "Something error! Try again!!";
                }
                
                unset($stmt);
            }
            unset($db);
            
        }catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    public function fb_login($fb_id, $firstname, $lastname, $img){
        $dbconn = new DbConnection();
        $db = $dbconn->dbConn();
        try{
            $stmt = $db->prepare("SELECT * FROM fb_users WHERE fb_id=:fb_id");
            $stmt->bindParam(":fb_id", $param_fb_id, PDO::PARAM_STR);
            $param_fb_id = trim($fb_id);

            if($stmt->execute()){
                $count = $stmt->rowCount();
                // die(var_dump($count));
                if($count == 0){
                    $stmt = $db->prepare("INSERT INTO fb_users(fb_id, firstname, lastname, img) VALUES(:fb_id, :firstname, :lastname, :img)");

                    $stmt->bindParam(':fb_id', $param_fb_id, PDO::PARAM_STR);
                    $param_fb_id = trim($fb_id);

                    $stmt->bindParam(':firstname', $param_firstname, PDO::PARAM_STR);
                    $param_firstname = trim($firstname);

                    $stmt->bindParam(':lastname', $param_lastname, PDO::PARAM_STR);
                    $param_lastname = trim($lastname);

                    $stmt->bindParam(':img', $param_img, PDO::PARAM_STR);
                    $param_img = trim($img);

                    $stmt->execute();

                    $_SESSION['user'] = [
                        'id' => $fb_id,
                        'fb' => true,
                        'name' => $firstname . " " . $lastname,
                        'img' => $img
                    ];

                }else{
                    $row = $stmt->fetch();
                    $_SESSION['user'] = [ 
                        'id' => $row['fb_id'],
                        'fb' => true,
                        'name' => $row['firstname'] . " " . $row['lastname'],
                        'img' => $row['img']
                    ];
                }
            }
            unset($db);
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }
}