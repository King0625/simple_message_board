<?php include_once "includes/header.inc.php"; ?>

<h1>Sign in</h1>

<?php
    session_destroy();
    require "classes/user.class.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    if(isset($_POST['submit'])){
        $user = new User();
        $user->userSignin($username, $password);
    }

?>

<form action="signin.php" method="POST">
    <label for="username">Username: </label>
    <input type="text" name="username"><br>
    <label for="password">Password: </label>
    <input type="password" name="password"><br>
    <input type="submit" name="submit" value="Submit"><br>
    <a href="change_pwd.php">Change Password</a>
    
    
</form>


<p>Don't have an account? <span><a href="signup.php">Sign up here</a></span>.</p>


<!-- facebook sign in comes here -->
<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/config.php';
    session_start();
    $fb = new Facebook\Facebook([
      'app_id' => APP_ID, // Replace {app-id} with your app id
      'app_secret' => APP_SECRET,
      'default_graph_version' => 'v3.3',
    ]);
    
    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ['email']; // Optional permissions
    // print_r($permissions);
    $loginUrl = $helper->getLoginUrl('http://localhost/cms/fb-callback.php', $permissions);
    echo "<h3>Or sign in with <span><a href='". htmlspecialchars($loginUrl) ."'>Facebook</a></span></h3>";

?>


<?php include_once "includes/footer.inc.php"; ?>