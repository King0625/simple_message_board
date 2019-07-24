<?php 
    include_once "includes/header.inc.php"; 
    require "classes/file.class.php";
    require "helpers/img.php";
?>

<?php
    session_start();
    if(!isset($_SESSION['user']['name'])){
        header("Location: signin.php");
    }
    echo "<h2><a href='index.php'>Back to homepage</a></h2><br>";
    echo "This is " . $_SESSION['user']['name'] . "'s profile." . "<br><br>";
    
    $file = new File();
    if(isset($_POST['submit'])){
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_type = $_FILES['image']['type'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
        $allowed_ext = array("jpg", "jpeg", "png");
        if(empty($file_name)){
            $errors[] = "Please select an image"."<br>";
        }
        if(in_array($file_ext, $allowed_ext) === false){
            $errors[] = "Extensions not allowed. Please choose jpg or jpeg file"."<br>";
        }
        if($file_size > 1000000){
            $errors[] = "File size too big"."<br>";
        }
        if(empty($errors)){
            move_uploaded_file($file_tmp, "img/". $file_name);
            $file->updateImg($_SESSION['user']['id'], $file_name, $_SESSION['user']['fb']);
        }else{
            print_r($errors);
        }
    }
    if(isset($_POST['delete'])){
        $file->deleteImg($_SESSION['user']['id'], $_SESSION['user']['fb']);
    }


    if(isset($_SESSION['user']['img'])){
        // wrong: if($_SESSION['user']['img'] == "")
        if(empty($_SESSION['user']['img'])){
            echo "<img src='img/default.jpg' width='150' height='150'><br><br>";
        }else{
            imgUrl($_SESSION['user']['img']);
        }
    }
?>


<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="image"><br>
    <input type="submit" name="submit" value="Upload"><br>
    <input type="submit" name="delete" value="Delete">
</form>

<?php include_once "includes/footer.php"; ?>