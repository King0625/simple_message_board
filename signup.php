<?php 
    include_once "includes/header.inc.php"; 
?>

<script src='https://www.google.com/recaptcha/api.js'></script>

<h1>Sign up</h1>

<?php
    require "classes/user.class.php";
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $img = "";
    if(isset($_POST['submit'])){

        // confirm recaptcha v2
        $captcha = $_POST['g-recaptcha-response'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $secretkey = "6LdU1a0UAAAAAMcwZtFpwlcihatWPHnp_YyM6TaC";					
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);	     

        if(intval($responseKeys["success"]) !== 1) {
            echo('<h3 style="color: red;">Wrong captcha try again please!</h3>');
        } else {
            $user = new User();
            $user->userSignup($firstname, $lastname, $username, $password, $confirm_password, $img);
        }	  

    }

    
?>

<form action="signup.php" method="POST">
    <label for="firstname">First name: </label>
    <input type="text" name="firstname"><br>
    <label for="lastname">Last name: </label>
    <input type="text" name="lastname"><br>
    <label for="username">Username: </label>
    <input type="text" name="username"><br>
    <label for="password">Password: </label>
    <input type="password" name="password"><br>
    <label for="confirm-password">Confirm Password: </label>
    <input type="password" name="confirm-password"><br>
    <div class="g-recaptcha" data-sitekey="6LdU1a0UAAAAAGLh4uKa1z1u0ZAgZ_dfI3w-SHjT"></div>
    <input type="submit" name="submit" value="Submit">
</form>

<p>Already have an account? <span><a href="signin.php">Sign in here</a></span>.</p>


<?php 
    include_once "includes/footer.inc.php"; 
?>