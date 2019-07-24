<?php 
    if(isset($_POST['submit'])){
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_type = $_FILES['image']['type'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
        $allowed_ext = array("jpg", "jpeg");

        if(in_array($file_ext, $allowed_ext) === false){
            $errors[] = "Extensions not allowed. Please choose jpg or jpeg file";
        }
        if($file_size > 1000000){
            $errors[] = "File size too big";
        }
        if(empty($errors)){
            move_uploaded_file($file_tmp, "img/". $file_name);
            echo "Success";
        }else{
            print_r($errors);
        }

    }
?>

<!-- must add enctype="multipart/form-data" to handle file  -->
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="image"><br>
    <input type="submit" name="submit" value="Submit">
</form>

<ul>
    <li>Sent file: <?php echo $file_name  ?>
    <li>File size: <?php echo $file_size  ?>
    <li>File type: <?php echo $file_type  ?>
    <li>File tmp: <?php echo $file_tmp  ?>
    <li>File extension: <?php echo $file_ext  ?>
</ul>