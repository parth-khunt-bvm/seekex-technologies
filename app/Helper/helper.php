<?php

function numberformat($value){
    return number_format((float)$value, 2,'.', '');
}

function date_formate($date){
    return date("d-M-Y", strtotime($date));
}
function ccd($data){
    echo "<pre>";
    print_r($data);
    die();
}

?>
