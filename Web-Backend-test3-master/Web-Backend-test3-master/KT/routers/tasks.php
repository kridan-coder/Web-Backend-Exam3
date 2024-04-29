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
    if (Count($urlData) === 0) {
        if ($generalAccessLevel == ADMIN_ACCESS_LEVEL){
            $users = $database->query("SELECT `id`,`name`,`topicId` FROM `task`");

            if ($users->num_rows == 0) {
                EntityNotFound404();
            }



            printJSON($users);
            exit();}


        else {
            $users = $database->query("SELECT `id`,`name`,`topicId` FROM `task` WHERE `isDraft` = 0");

            if ($users->num_rows == 0) {
                EntityNotFound404();
            }



            printJSON($users);
            exit();
        }

    }
    else if (Count($urlData) === 1) {
        if (is_numeric($urlData[0])) {

            if ($generalAccessLevel == ADMIN_ACCESS_LEVEL){
                $userId = (int)$urlData[0];
                $user = $database->query("SELECT * FROM `task` WHERE `id` = $userId");

                if ($user->num_rows != 0){
                    printJSON($user);

                }
                else {
                    EntityNotFound404();
                }

                exit();
            }
            else {
                $userId = (int)$urlData[0];
                $user = $database->query("SELECT * FROM `task` WHERE `id` = $userId AND `isDraft` = 0");

                if ($user->num_rows != 0){

                    printJSON($user);

                }
                else {
                    EntityNotFound404();
                }

                exit();
            }





        } //  /users/{id}:  Get user information
    }
}
function Post($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
    $headers = getallheaders();
    if (Count($urlData) === 0) {
        if ($generalAccessLevel == ADMIN_ACCESS_LEVEL){

            $name = $formData->name;
            $topicId = $formData->topicId;
            $description = $formData->description;
            $price = intval($formData->price);

            $description = strval($description);

            if ($name != "" and $topicId != "" and $description != "" and $price != ""){
                $database->query("INSERT INTO `task` (`id`, `name`,`topicId`, `price`, `description`) 
                               VALUES (NULL, '$name', '$topicId', '$price', '$description')");
            }
            else {
                BadRequest400();
            }

            $name = strval($name);

            $topic = $database->query("SELECT * FROM `task` WHERE `task`.`name` = '$name' AND `task`.`topicId` = '$topicId' AND `task`.`description` = '$description'");

            if ($topic->num_rows == 0) {
                BadRequest420();
            }

            printJSON($topic);
            exit();
        }

        else {
            Forbidden403();
        }

    }
    else if (Count($urlData) === 2) {
        if (is_numeric($urlData[0])) {

            if ($urlData[1] == 'solution' and $generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL) {



                $taskId = (int)$urlData[0];

                $sourceCode = $formData->sourceCode;
                $programmingLanguage = $formData->programmingLanguage;

                //echo json_encode(["hahah"=>$topicId]);

                //$users = $database->query("SELECT `id`,`name`,`topicId` FROM `task` WHERE `topicId` = '$topicId'");

                $database->query("INSERT INTO `solution` (`id`, `sourceCode`,`programmingLanguage`, `taskId`) 
                               VALUES (NULL, '$sourceCode', '$programmingLanguage', '$taskId')");

                $users = $database->query("SELECT * FROM `solution` WHERE `sourceCode` = '$sourceCode'");

                if ($users->num_rows == 0) {
                    EntityNotFound404();
                }



                printJSON($users);
                exit();
                // Access is allowed



            }   //  /users/{id}/city: Set city to user by Id (by user for yourself or admin)

        }
    }
    else {
        BadRequest400();
    }
}
function Patch($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
    if (Count($urlData) === 1) {
        if (is_numeric($urlData[0])) {
            if ($generalAccessLevel == ADMIN_ACCESS_LEVEL) {
                $name = $formData->name;
                $topicId = $formData->topicId;
                $description = $formData->description;
                $price = intval($formData->price);

                $id = $urlData[0];


//            $database->query("INSERT INTO `task` (`id`, `name`,`topicId`, `price`, `description`)
//                               VALUES (NULL, '$name', '$topicId', '$price', '$description')");


                $database->query("UPDATE `task` SET `name`='$name', `topicId`='$topicId', `price`='$price', `description`='$description'   WHERE `id` = '$id'");

                $topic = $database->query("SELECT * FROM `task` WHERE `task`.`name` = '$name' AND `task`.`topicId` = '$topicId' AND `task`.`description` = '$description'");

                if ($topic->num_rows == 0) {
                    BadRequest420();
                }

                printJSON($topic);
            }
            else {
                Forbidden403();
            }
//            $database->query("UPDATE `user` SET `Name` = '$name',`Username` = '$username',`Surname` = '$surname',
//                                        `Password` = '$password', `Birthday` = NULL, `Avatar` = '$avatar' WHERE `user`.`Id` = '$userId'");

        }
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

            $database->query("DELETE FROM `task` WHERE `task`.`id` = $userId");

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



?>