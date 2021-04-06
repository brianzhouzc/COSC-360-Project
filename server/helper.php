<?php

function errorMsg($status, $detail)
{
    $error = array("status" => $status, "detail" => $detail);
    $response = array("errors" => $error);
    return (json_encode($response));
}

function generateRandomString($length = 6)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
