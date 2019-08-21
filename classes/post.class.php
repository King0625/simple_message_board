<?php 
    require 'db.class.php';
    require 'reply.class.php';

    class Comment{
        public $pages = 0;

        public function addComment($name, $topic, $content, $date){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            try{
                if(empty(trim($topic))){
                    $errMessage = "Please enter the topic!!";
                    echo $errMessage;
                    return;
                }elseif(empty(trim($content))){
                    $errMessage = "Please enter the message you wanna say!";
                    echo $errMessage;
                    return;
                }else{
                    $stmt = $db->prepare("INSERT INTO posts(name, topic, content, date) VALUES(:name, :topic, :content, :date);");
                    $stmt->bindParam(':name', $param_name, PDO::PARAM_STR);
                    $param_name = trim($name);
                    // var_dump($param_name);

                    $stmt->bindParam(':topic', $param_topic, PDO::PARAM_STR);
                    $param_topic = trim($topic);
                    // var_dump($param_topic);

                    $stmt->bindParam(':content', $param_content, PDO::PARAM_STR);
                    $param_content = trim($content);
                    // var_dump($param_content);

                    // Date is still a string in mysql
                    $stmt->bindParam(':date', $param_date, PDO::PARAM_STR);
                    $param_date = trim($date);
                    // var_dump($param_date);

                    if(!$stmt->execute()){
                        echo "Something went wrong!! Try again later" . "<br>";
                        return;
                    }
                }
            }catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
        }

        /************* */
        public function getComment($name, $search, $reply_cid, $get_page_no, $total_items_per_page){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            try{
                $stmt = $db->prepare("SELECT * FROM posts");
                $stmt->execute();
                
                // post counts
                $count = $stmt->rowCount();
                
                $this->pages = ceil($count / $total_items_per_page);
                

                if(isset($get_page_no) && $get_page_no != ""){
                    $page_no = $get_page_no;
                }else{
                    $page_no = 1;
                }
    
                $offset = ($page_no - 1) * $total_items_per_page;

                if(isset($search) && !empty($search)){
                    // no need single quotes in interger type 
                    $stmt = $db->prepare("SELECT * FROM posts WHERE topic LIKE '%$search%' ORDER BY cid DESC 
                    LIMIT $offset, $total_items_per_page");
                   
                }else{
                    $stmt = $db->prepare("SELECT * FROM posts ORDER BY cid DESC LIMIT $offset,  $total_items_per_page");

                }

                $stmt->execute();


                while($row = $stmt->fetch()){
                    echo "<hr><div class='message'>";
                    echo "<h2>". $row['name']."<span>  at " .$row['date'] . "</span></h2>";
                    // echo $row['date'] . "<br>";
                    echo "<h3>Topic: " . $row['topic'] . "</h3>";
                    echo $row['content'] . "<br>";
                    // var_dump($row['cid']);

                    // Not if($name == $row['name']){
                    if($row['name'] == $name){
                        echo "<form class='post-btns' action='' method='POST'>
                        <input type='hidden' name='cid' value='".$row['cid']."'>
                        <input type='submit' name='delete' value='delete'>
                        </form>";

                        // set action to send these values to editpost.php
                        echo "<form class='post-btns' action='' method='POST'>
                        <input type='hidden' name='cid' value='".htmlspecialchars($row['cid'], ENT_QUOTES)."'>
                        <input type='hidden' name='name' value='".htmlspecialchars($row['name'], ENT_QUOTES)."'>
                        <input type='hidden' name='date' value='".htmlspecialchars($row['date'], ENT_QUOTES)."'>
                        <input type='hidden' name='topic' value='".htmlspecialchars($row['topic'], ENT_QUOTES)."'>
                        <input type='hidden' name='content' value='".htmlspecialchars($row['content'], ENT_QUOTES)."'>
                        <input type='submit' name='edit' value='edit'>
                        </form>";
                        
                        if(isset($_POST['edit'])){
                            $_SESSION['post'] = [
                                'cid' => $_POST['cid'],
                                'name' => $_POST['name'],
                                'date' => $_POST['date'],
                                'topic' => $_POST['topic'],
                                'content' => $_POST['content']
                            ];    
                            // print_r($_SESSION['post']);
                            header("Location: editpost.php");
                        }
                    }

                    echo "<br>";
                    $reply = new Reply();
                    $reply->getReply($name, $row['cid']);
                    
                    echo "<hr><form class='reply' method='POST'>
                    <input type='hidden' name='reply_cid' value='".$row['cid']."'>
                    <input type='hidden' name='reply_name' value='".$name."'>
                    <input type='hidden' name='reply_date' value='".date('Y-m-d H:i:s')."'>
                    <input type='text' name='reply_content' placeholder='What do you think?'>
                    <input type='submit' name='reply' value='Reply'>
                    </form>";
                    echo "</div>";
                }
                unset($stmt);
                unset($db);
            }catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
        }


        public function editComment($cid, $name, $date, $topic, $content){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            try{
                $stmt = $db->prepare("UPDATE posts SET topic=:topic, content=:content, date=:date WHERE cid='$cid'");
                $stmt->bindParam(':topic', $param_topic, PDO::PARAM_STR);
                $param_topic = trim($topic);
                $stmt->bindParam(':content', $param_content, PDO::PARAM_STR);
                $param_content = trim($content);
                $stmt->bindParam(':date', $param_date, PDO::PARAM_STR);
                $param_date = trim($date);

                if($stmt->execute()){
                    header("Location: index.php");
                }else{
                    echo "Error";
                }
            
            }catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
        }


        public function deleteComment($cid){
            $dbconn = new DbConnection();
            $db = $dbconn->dbConn();
            try{
                if(isset($_POST['delete'])){
                    $stmt_post = $db->prepare("DELETE FROM posts WHERE cid='$cid'");
                    $stmt_post->execute();
                    // $stmt_reply = $db->prepare("DELETE FROM replies WHERE cid='$cid'");
                    // $stmt_reply->execute();
                    unset($stmt);
                    unset($db);
                }
            }catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            }
        }
    }

    