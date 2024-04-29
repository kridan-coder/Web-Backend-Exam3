<?php
function route($method, $urlData, $formData)
{
    switch ($method) {
        case 'GET':
            Get($method, $urlData, $formData);
            break;
        default:
            header('HTTP/1.0 501 Not Implemented');
            break;
    }
}



function Get($method, $urlData, $formData)
{
    global $database;
    $headers = getallheaders();

    if (isset($headers["Authorization"])) {
        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        $user = $database->query("SELECT * FROM `user` WHERE `user`.`token`='$token'");

        if (in_array($user->fetch_array()['token'], array($token))){
            $database->query("UPDATE `user` SET `token` = NULL WHERE `user`.`token` = '$token'");
            header('HTTP/1.0 200 OK');
        }
        else {
            http_response_code(406);
            echo json_encode([
                'status' => false,
                'message' => 'Bearer token is incorrect for logging out'
            ]);
        }


    }
    else {
        http_response_code(409);
        echo json_encode([
            'status' => false,
            'message' => 'No Bearer token provided'
        ]);
    }
}
?>