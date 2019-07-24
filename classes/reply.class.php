<?php
    // require "db.class.php";

    class Reply{
        public function addReply($cid, $name, $content, $date){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            $parent_rid = 0;
            $errMessage = "";
            try{
                if(empty(trim($content))){
                    $errMessage = "Please fill in the reply";
                    echo $errMessage;
                    return;
                }else{
                    $stmt = $db->prepare("INSERT INTO replies(parent_rid, cid, name, content, date) VALUES(:parent_rid, :cid, :name, :content, :date);");
                    
                    $stmt->bindParam(':parent_rid', $param_parent_rid, PDO::PARAM_INT);
                    $param_parent_rid = trim($parent_rid);
                    $stmt->bindParam(':cid', $param_cid, PDO::PARAM_INT);
                    $param_cid = trim($cid);
                    $stmt->bindParam(':name', $param_name, PDO::PARAM_STR);
                    $param_name = trim($name);
                    $stmt->bindParam(':content', $param_content, PDO::PARAM_STR);
                    $param_content = trim($content);
                    $stmt->bindParam(':date', $param_date, PDO::PARAM_STR);
                    $param_date = trim($date);
                    if(!$stmt->execute()){
                        $errMessage = "Something went wrong! Try again.";
                        echo $errMessage;
                        return;
                    }
                    $parent_rid = 1;
                }
                

            }catch(PDOException $e){
                // echo '{"error":{"text":'. $e->getMessage() .'}}';
                echo "Something went wrong." . $e->getMessage();
            }
        }

        public function getReply($name, $cid){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            $parent_rid = 0;
            $margin_left = 15;
            try{
                $stmt = $db->prepare("SELECT * FROM replies ORDER BY rid");
                $stmt->execute();
                while($row = $stmt->fetch()){
                    if($row['cid'] == $cid){
                        if(!$parent_rid == 0){
                            $margin_left = 20;
                        }
                        echo "<hr><div style='margin-left:$margin_left" ."px; color: grey;'><h3>". $row['name'] . "<span> at " . $row['date'] ." </span></h3>";
                        // echo $row['date']. "<br><br>";
                        echo $row['content']. "<br><br>";
                        if($row['name'] == $name){
                            echo "<form class='reply-btns' action='' method='POST'>
                            <input type='hidden' name='rid' value='".$row['rid']."'>
                            <input type='submit' name='delete_reply' value='delete'>
                            </form>";
    
                        }
                        echo "</div>";
                        
                        // var_dump($row['cid']);
    
                    }
                    
                    // if($row['name'] == $name){
                    //     echo "<form class='reply-btns' action='' method='POST'>
                    //     <input type='hidden' name='rid' value='".$row['rid']."'>
                    //     <input type='submit' name='delete' value='delete'>
                    //     </form>";

                    //     // set action to send these values to editpost.php
                    //     echo "<form class='reply-btns' action='' method='POST'>
                    //     <input type='hidden' name='rid' value='".$row['rid']."'>
                    //     <input type='hidden' name='name' value='".$row['name']."'>
                    //     <input type='hidden' name='date' value='".$row['date']."'>
                    //     <input type='hidden' name='content' value='".$row['content']."'>
                    //     <input type='submit' name='edit' value='edit'>
                    //     </form>";
                    // }
                }

                unset($stmt);
                unset($db);
            }catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
        }

        // public function editReply(){
        //     $dbconn = new DbConnection();
        //     $db = $dbconn->dbConn();
        //     try{

        //     }catch(PDOException $e){
        //         echo '{"error":{"text":'. $e->getMessage() .'}}';
        //     }
        // }

        public function deleteReply($rid){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            try{
                $stmt = $db->prepare("DELETE FROM replies WHERE rid='$rid'");
                $stmt->execute();
                unset($stmt);
                unset($db);
            }catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
        }


    }