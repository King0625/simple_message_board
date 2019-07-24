<?php 
    include_once 'includes/header.inc.php'; 
    require 'classes/post.class.php';
    session_start();
    $comment = new Comment();

    // Store original cid and name (no need to override after publishing)
    $cid = $_SESSION['post']['cid'];
    var_dump($cid);
    $name = $_SESSION['post']['name'];
    var_dump($name);

    $date = $_SESSION['post']['date'];

    $topic = $_SESSION['post']['topic'];
    var_dump($topic);

    $content = $_SESSION['post']['content'];
    var_dump($content);

    if(isset($_POST['publish'])){
        // $cid = $_POST['cid'];
        // $name = $_POST['name'];
        // $date = $_POST['date'];
        // $topic = $_POST['topic'];
        // $content = $_POST['content'];
        // $cid = $_POST['cid'];
        // var_dump($cid);
        // $name = $_POST['name'];
        // var_dump($name);
        $_SESSION['post'] = '';
        // Store updated date, topic, and content
        $date = $_POST['date'];
        $topic = $_POST['topic'];
        $content = $_POST['content'];
        
        $comment->editComment($cid, $name, $date, $topic, $content);

    }elseif(isset($_POST['cancel'])){
        $_SESSION['post'] = '';
        header("Location: index.php");
    }
?>

<form action="" method="post">
    <input type='hidden' name='cid' value='<?php echo $cid ?>'>
    <input type="hidden" name="name" value='<?php echo $name; ?>'>
    <input type="hidden" name="date" value='<?php echo date('Y-m-d H:i:s');?>'>
    <textarea name="topic" id="" cols="30" rows="2" placeholder="Change the topic if you want?"><?php echo $topic ?></textarea><br>
    <!-- <input type="text" name="topic" value='' placeholder="Change the topic if you want?"><br> -->
    <textarea name="content" id="" cols="30" rows="5" placeholder="edit your message"><?php echo $content ?></textarea><br>
    <input type="submit" name="publish" value="publish">
    <input type="submit" name="cancel" value="cancel">
</form>

<?php include_once 'includes/footer.inc.php'; ?>