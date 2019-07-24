<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/user.class.php';
require_once __DIR__ . '/config.php';

session_start();

if(!isset($_SESSION['fb_access_token'])){
    header("Location: signin.php");
}

$fb = new Facebook\Facebook([
    'app_id' => APP_ID,
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v3.3',
]);


try {
// Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,first_name,last_name,picture.width(300).height(300)', $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

// $graphNode = $response->getGraphNode();
$fb_user = $response->getGraphUser();
// print_r($fb_user);

// echo "firstname: " . $fb_user['first_name'];
// echo "lastname: " . $fb_user['last_name'];
$data = [
    'fb_id' => $fb_user['id'],
    'firstname' => $fb_user['first_name'],
    'lastname' => $fb_user['last_name'],
    'img' => $fb_user['picture']['url']
];



$user = new User();
$user->fb_login($data['fb_id'], $data['firstname'], $data['lastname'], $data['img']);

// $_SESSION['user'] = [
//     'fb' => true,
//     'fb_id' => $data['fb_id'],
//     'name' => $data['firstname'] . " " . $data['lastname'],
//     'img' => $data['img']
// ];

header('Location: index.php');
