<script src='https://www.google.com/recaptcha/api.js'></script>

<h1>Change password</h1>

<?php 

    require "classes/password.class.php";

    $username = $_POST['username'];
    $old_pwd = $_POST['old_pwd'];
    $new_pwd = $_POST['new_pwd'];
    $confirm_new_pwd = $_POST['confirm_new_pwd'];

    if(isset($_POST['submit'])){
        // confirm recaptcha v2
        $captcha = $_POST['g-recaptcha-response'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $secretkey = "6LdU1a0UAAAAAMcwZtFpwlcihatWPHnp_YyM6TaC";					
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);

        if(intval($responseKeys["success"]) !== 1) {
            echo('<h3 style="color: red;">Wrong captcha!! Try again please!</h3>');
        } else {
            $pwd = new Pwd();
            $pwd->changePwd($username, $old_pwd, $new_pwd, $confirm_new_pwd);
        }
    }elseif(isset($_POST['cancel'])){
        header("Location: signin.php");
    }

?>


<form action="" method="POST">
    <label for="username">Username: </label>
    <input type="text" name="username"><br>
    <label for="old_pwd">Old password: </label>
    <input type="password" name="old_pwd"><br>
    <label for="new_pwd">New password: </label>
    <input type="password" name="new_pwd"><br>
    <label for="confirm_new_pwd">Confirm new password: </label>
    <input type="password" name="confirm_new_pwd"><br>
    <div class="g-recaptcha" data-sitekey="6LdU1a0UAAAAAGLh4uKa1z1u0ZAgZ_dfI3w-SHjT"></div>
    <input type="submit" name="submit" value="Change password">
    <input type="submit" name="cancel" value="Cancel">
</form>