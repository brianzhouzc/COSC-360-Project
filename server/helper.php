<?php

function errorResponse($status, $detail)
{
    $error = array("status" => $status, "detail" => $detail);
    $response = array("errors" => $error);
    return (json_encode($response));
}

function dataResponse($status, $detail, $data = null)
{
    if (isset($data)) {
        if (!is_array($data))
            $data = array($data);
        $data = array("data" => (array("status" => $status, "detail" => $detail) + $data));
    } else {
        $data = array("data" => (array("status" => $status, "detail" => $detail)));
    }
    return (json_encode($data));
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

function isset_notempty()
{
    for ($i = 0; $i < func_num_args(); $i++) {
        $var = func_get_arg($i);
        if (!(isset($var) && !empty($var)))
            return false;
    }
    return true;
}

function exitandclose($msg, $connection)
{
    $connection->close();
    exit($msg);
}

function getValueFromKey($arr, $key)
{
    if (isset($arr[$key]))
        return $arr[$key];
    else
        return null;
}
