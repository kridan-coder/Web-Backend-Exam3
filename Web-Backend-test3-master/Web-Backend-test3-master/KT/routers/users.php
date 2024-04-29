<?php



function route($method, $urlData, $formData)
{
    $headers = getallheaders();
    if (isset($headers["Authorization"])) $generalAccessLevel = GetGeneralAccessLevel($headers["Authorization"]);
    else $generalAccessLevel = UNAUTHORIZED_ACCESS_LEVEL;

    //if ($generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL) $ownerAccess = IdentifyOwner($headers["Authorization"], (int)$controllerData[0], 'post');
    // $ownerAccess = ACCESS_DENIED;

    switch ($method) {
        case 'GET':
            Get($method, $urlData, $formData, $generalAccessLevel);
            break;
        case 'POST':
            Post($method, $urlData, $formData, $generalAccessLevel);
            break;
        case 'PATCH':
            Patch($method, $urlData, $formData, $generalAccessLevel);
            break;
        case 'DELETE':
            Delete($method, $urlData, $formData, $generalAccessLevel);
            break;
        default:
            header('HTTP/1.0 501 Not Implemented');
            break;
    }
}


function Get($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
    $headers = getallheaders();


    if ($method === 'GET') {

            if (Count($urlData) === 0) {
                if ($generalAccessLevel == ADMIN_ACCESS_LEVEL){
                    $users = $database->query("SELECT `userId`,`username`,`roleId` FROM `user`");

                    if ($users->num_rows == 0) {
                        EntityNotFound404();
                    }



                    printJSON($users);
                    exit();}
                else {
                    Forbidden403();
                }

            }


//  /users/:  Get all users

        else if (Count($urlData) === 1) {
            if (is_numeric($urlData[0])) {
                $ownerAccess = ACCESS_DENIED;
                if ($generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL){
                    $ownerAccess = IdentifyOwner($headers["Authorization"], (int)$urlData[0]);
                }



                if ($generalAccessLevel == ADMIN_ACCESS_LEVEL or $ownerAccess){
                    $userId = (int)$urlData[0];
                    $user = $database->query("SELECT `userId`,`username`,`roleId`, `name`, `surname` FROM `user` WHERE `userId` = $userId");

                    if ($user->num_rows != 0){
                        printJSON($user);
                    }
                    else {
                        EntityNotFound404();
                    }

                    exit();
                }
                else {
                    Forbidden403();
                }

            } //  /users/{id}:  Get user information
        }


    }
}

function Post($method, $urlData, $formData, $generalAccessLevel)
{

}
function Patch($method, $urlData, $formData, $generalAccessLevel)
{

    global $database;
    $headers = getallheaders();
    if (Count($urlData) === 1) {
        if (is_numeric($urlData[0])) {
            $ownerAccess = ACCESS_DENIED;
            if ($generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL){
                $ownerAccess = IdentifyOwner($headers["Authorization"], (int)$urlData[0]);
            }



            if ($generalAccessLevel == ADMIN_ACCESS_LEVEL or $ownerAccess){
                $userId = (int)$urlData[0];

                $name = $formData->name;
                $surname = $formData->surname;
                $password = md5($formData->password);

                $database->query("UPDATE `user` SET `name`='$name', `surname`='$surname', `password`='$password' WHERE `user`.`userId` = $userId");

                $user = $database->query("SELECT `userId`,`username`,`roleId`, `name`, `surname` FROM `user` WHERE `userId` = $userId");

                if ($user->num_rows != 0){
                    printJSON($user);
                }
                else {
                    EntityNotFound404();
                }

                exit();
            }
            else {
                Forbidden403();
            }

        } //  /users/{id}:  Get user information
    }
    else {
        header('HTTP/1.0 501 Not Implemented');
    }
}
function Delete($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
    $headers = getallheaders();
    if (Count($urlData) === 1) {
        if (is_numeric($urlData[0]) and $generalAccessLevel == ADMIN_ACCESS_LEVEL) {


            $userId = (int)$urlData[0];

            $database->query("DELETE FROM `user` WHERE `user`.`userId` = $userId");

            echo json_encode([

                'message' => 'OK'
            ]);
            exit();


        }
        else {
            Forbidden403();
        }
    }
    else {
        header('HTTP/1.0 501 Not Implemented');
    }
}
//dd


?>