<?php

function route($method, $urlData, $formData)
{
    switch ($method) {
        case 'POST':
            Post($method, $urlData, $formData);
            break;
        default:
            header('HTTP/1.0 501 Not Implemented');
            break;
    }
}


function Post($method, $urlData, $formData)
{
    global $database;

    //$username = htmlentities($formData["Username"]);
    //$password = htmlentities($formData["Password"]);

    $username = $formData->username;
    $password = md5($formData->password);
    //echo json_encode(['chto' => $username, 'agagaga' => $password ]);
    $headers = getallheaders();

    $user = mysqli_fetch_array($database->query("SELECT * FROM `user` WHERE `user`.`username` = '$username' AND `user`.`password` = '$password'"));

    if (isset($user)) {
        if (isset($user["token"])) {
            if("Bearer " . $user["token"] == $headers["Authorization"]) {
                http_response_code(405);
                echo json_encode([
                    'status' => false,
                    'message' => 'Already logged in'
                ]);
                exit();
            }

            $token = GenerateToken();
            $database->query("UPDATE `user` SET `token` = '$token' WHERE `user`.`Username` = '$username' AND `user`.`Password` = '$password'");

            echo json_encode(['token' => $token]);
        }
        else {
            $token = GenerateToken();
            $database->query("UPDATE `user` SET `token` = '$token' WHERE `user`.`Username` = '$username' AND `user`.`Password` = '$password'");

            echo json_encode(['token' => $token]);
        }
        exit();
    }
    else {
        http_response_code(404);
        echo json_encode([
            'status' => false,
            'message' => 'User with such Username or/and Password does not exist.'
        ]);
        exit();
    }

}



?>