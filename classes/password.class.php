<?php
require "db.class.php";

class Pwd{
    public function changePwd($username, $old_pwd, $new_pwd, $confirm_new_pwd){
        $dbconn = new DbConnection();
        $db = $dbconn->dbConn();
        $errMessage = "";
        try{
            // check username
            if(empty(trim($username))){
                $errMessage = "Please enter username" . "<br>";
                echo $errMessage;
                return;
            }else{
                $username = trim($username);
            }

            // check old password
            if(empty(trim($old_pwd))){
                $errMessage = "Please enter old password" . "<br>";
                echo $errMessage;
                return;
            }else{
                $old_pwd = trim($old_pwd);
            }

            // check new password
            if(empty(trim($new_pwd))){
                $errMessage = "Please enter new password" . "<br>";
                echo $errMessage;
                return;
            }elseif(strlen(trim($new_pwd)) < 6){
                $errMessage = "Password must be at least 6 characters" . "<br>";
                echo $errMessage;
                return;
            }else{
                $new_pwd = trim($new_pwd);
            }

            // check confirm new password
            if(empty(trim($confirm_new_pwd))){
                $errMessage = "Please confirm new password" . "<br>";
                echo $errMessage;
                return;
            }else{
                $confirm_new_pwd = trim($confirm_new_pwd);
                if(empty(trim($errMessage)) && ($new_pwd != $confirm_new_pwd)){
                    $errMessage = "Confirm password did not match";
                    echo $errMessage;
                    return;
                }
            }

            
            if(empty(trim($errMessage))){
                $stmt_check = $db->prepare("SELECT * FROM users WHERE username=:username");
                $stmt_check->bindParam(":username", $username, PDO::PARAM_STR);

                if($stmt_check->execute()){
                    $count = $stmt_check->rowCount();
                    if($count == 1){
                        $row = $stmt_check->fetch();
                        $username = $row['username'];
                        $hashed_password = $row['password'];
                        if(password_verify($old_pwd, $hashed_password)){
                            $stmt_change = $db->prepare("UPDATE users SET password=:password WHERE username=:username");
                            $stmt_change->bindParam(":password", $param_new_hashed_pwd, PDO::PARAM_STR);
                            $hashed_new_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
                            $param_new_hashed_pwd = trim($hashed_new_pwd);
                            $stmt_change->bindParam(":username", $param_username, PDO::PARAM_STR);
                            $param_username = trim($username);
                            if($stmt_change->execute()){
                                header("Location: signin.php");
                            }else{
                                die("Something went wrong");
                            }
                            
                        }else{
                            $errMessage = "Incorrect origin password";
                            echo $errMessage;
                            return;
                        }
                    }else{
                        $errMessage = "No account with that username";
                        echo $errMessage;
                        return;
                    }
                }
            }
        }catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

}