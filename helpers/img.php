<?php

function imgUrl($url){
    $pattern = "/https/";
    if(preg_match($pattern, $url)){
        echo "<img src='". $url ."' width='150' height='150'><br><br>";
    }else{
        echo "<img src='img/". $url ."' width='150' height='150'><br><br>";
    }
}