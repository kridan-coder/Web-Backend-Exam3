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

//    $database->query("INSERT INTO `user` (`userId`, `username`, `roleId`, `name`, `surname`, `token`, `password`)
//                               VALUES (NULL, 'Main', '$adminRoleId[0]', 'Admin', 'Rootovich', '$token', 'qwerty')");

    //$username = htmlentities($formData["Username"]);
    //$password = htmlentities($formData["Password"]);


    $name = $formData->name;
    $surname = $formData->surname;
    $username = $formData->username;
    $password = md5($formData->password);

    //echo json_encode(['chto' => $name, 'agagaga' => $username ]);

    $headers = getallheaders();

    $user = $database->query("SELECT * FROM `user` WHERE `user`.`username` = '$username'");

    if ($user->num_rows != 0) {



        http_response_code(405);
        echo json_encode([
            'message' => 'User with this username already exists'
        ]);
        exit();

    }
    else {

        $token = GenerateToken();
        $adminRoleName = USER_ROLE_NAME;
        $adminRoleId = mysqli_fetch_array($database->query("SELECT `roleId` FROM `role` WHERE `role`.`name` = '$adminRoleName'"));
           $database->query("INSERT INTO `user` (`userId`, `username`, `roleId`, `name`, `surname`, `token`, `password`)
                               VALUES (NULL, '$username', '$adminRoleId[0]', '$name', '$surname', '$token', '$password')");

        echo json_encode(['token' => $token]);

    }

}



?>