<?php

function format($str){
    return highlight_string("<?php\n\n" . var_export($str, true) . "\n\n?>");
}
