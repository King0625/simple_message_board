<?php 
	include_once "includes/header.inc.php"; 
	require "classes/post.class.php";
	require "helpers/img.php";

	session_start();
	if(!isset($_SESSION['user'])){
		header("Location: signin.php");
	}else{
		$name = $_SESSION['user']['name'];
		// $username = $_SESSION['user']['username'];
		echo "<h1>". $name . "</h1>";

		// check image setting
		if(isset($_SESSION['user']['img'])){
			// wrong: if($_SESSION['img'] == "")
			if(empty($_SESSION['user']['img'])){
				echo "<img src='img/default.jpg' width='150' height='150'><br><br>";
			}
			else{
				imgUrl($_SESSION['user']['img']);
			}
		}
		echo "<a href='profile.php'>Profile</a>";
		echo "<form action='signout.php'>
		<input type='submit' name='submit' value='Sign out'>
		</form>";
	}
	
?>

<form id="search" action="" method="GET">
	<input type="text" name="search">
	<input type="submit" name="submit" value="search">
</form>

<form action="" method="POST">
	<input type="hidden" name="name" value="<?php echo $name ?>">
	<input type="hidden" name="date" value="<?php echo date('Y-m-d H:i:s') ?>">
	<input type="text" name="topic" placeholder="Topic?"><br>
	<textarea name="content" id="" cols="30" rows="5" placeholder="How do you do today?"></textarea><br>
	<input type="submit" name="publish" value="publish">
</form>

<?php 
	// initial comment class
	$comment = new Comment();
	if(isset($_POST['publish'])){
		$comment->addComment($_POST['name'], $_POST['topic'], $_POST['content'], $_POST['date']);
		// var_dump($_POST['date']);
	}
	
	// After delete, show comments
	$comment->deleteComment($_POST['cid']);
	$search = $_GET['search'];

	$reply = new Reply();
	if(isset($_POST['reply'])){
		$reply->addReply($_POST['reply_cid'], $_POST['reply_name'], $_POST['reply_content'], $_POST['reply_date']);
	}
	if(isset($_POST['delete_reply'])){
		$reply->deleteReply($_POST['rid']);
	}



	// get comment and reply at the same time --> add getReply in getComment function
	echo "<div class='container'>";
	$comment->getComment($name, $search, $_POST['reply_cid'], $_GET['page_no'], 3);
	echo "</div>"; 
	

?>


<form class='pagination' action="" method="GET">
	<?php 
		for($i = 1; $i <= $comment->pages; $i++){
			echo "<input type='submit' name='page_no' value='" . $i . "'>";
		} 
	?>
</form>

<?php include_once "includes/footer.inc.php"; ?>