<?php

function errorMsg($code, $status, $message)
{
    $response = array("code"=>$code, "status"=>$status, "message"=>$message);
    return (json_encode($response));
}
