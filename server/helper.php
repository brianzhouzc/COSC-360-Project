<?php
require_once "Mail.php";

function send_email($to, $email_subject, $email_body)
{
    if (class_exists('Mail')) {
        $host = "smtp.mailgun.org";
        $username = "postmaster@mg.notaserver.me";
        $password = "bb53d2de7382d30351cded3d3c89714a-a09d6718-94dc3e25";
        $port = "587";

        //$to = "test@example.com";
        $email_from = "donotreply@mg.notaserver.me";
        //$email_subject = "Awesome Subject line" ;
        //$email_body = "This is the message body" ;
        $email_address = "donotreply@mg.notaserver.me";
        $content = "text/html; charset=utf-8";
        $mime = "1.0";

        $headers = array(
            'From' => $email_from,
            'To' => $to,
            'Subject' => $email_subject,
            'Reply-To' => $email_address,
            'MIME-Version' => $mime,
            'Content-type' => $content
        );

        $params = array(
            'host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password
        );

        $smtp = Mail::factory('smtp', $params);
        $mail = $smtp->send($to, $headers, $email_body);

        if (PEAR::isError($mail)) {
            echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            //echo ("<p>Message sent successfully!</p>");
        }
    } else {
        echo ("Cannot send email, necessary class does not exist.");
    }
}

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
