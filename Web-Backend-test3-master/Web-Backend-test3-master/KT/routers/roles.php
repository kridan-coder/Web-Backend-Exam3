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

        default:
            header('HTTP/1.0 501 Not Implemented');
            break;
    }
}


function Get($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
        $headers = getallheaders();
    if (Count($urlData) === 0) {
        if ($generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL){
            $users = $database->query("SELECT `roleId`,`name` FROM `role`");

            if ($users->num_rows == 0) {
                EntityNotFound404();
            }



            printJSON($users);
            exit();}

        else {
            Forbidden403();
        }

    }
    else if (Count($urlData) === 1) {
        if (is_numeric($urlData[0])) {



            if ($generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL){
                $userId = (int)$urlData[0];
                $user = $database->query("SELECT `roleId`,`name` FROM `role` WHERE `roleId` = $userId");

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



?>