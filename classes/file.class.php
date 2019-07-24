<?php 

require "db.class.php" ;

class File{
    public function updateImg($id, $img, $fb){
        $dbconn = new Dbconnection();
        $db = $dbconn->dbconn();
        try{
            if($fb){
                $stmt = $db->prepare("UPDATE fb_users SET img='$img' WHERE fb_id='$id'");
            }else{
                $stmt = $db->prepare("UPDATE users SET img='$img' WHERE id='$id'");
            }
            
            $stmt->execute();
            $_SESSION['user']['img'] = $img;
            $stmt->bindParam(':img', $param_img, PDO::PARAM_STR);
            $param_img = $img;
            unset($stmt);
            unset($db);
        }catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

    public function deleteImg($id, $fb){
        $dbconn = new Dbconnection();
        $db = $dbconn->dbconn();
        try{
            if($fb){
                $stmt = $db->prepare("UPDATE fb_users SET img='$img' WHERE fb_id='$id'");
            }else{
                $stmt = $db->prepare("UPDATE users SET img='$img' WHERE id='$id'");
            }
            $stmt->execute();
            $_SESSION['user']['img'] = '';
            unset($stmt);
            unset($db);
        }catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
}

?>
    