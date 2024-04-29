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
        if ($generalAccessLevel > UNAUTHORIZED_ACCESS_LEVEL)
            $users = $database->query("SELECT `id`,`name`, `parentId` FROM `topic`");

            if ($users->num_rows == 0) {
                EntityNotFound404();
            }



            printJSON($users);
            exit();

    }
    else if (Count($urlData) === 1) {
        if (is_numeric($urlData[0])) {




                $userId = (int)$urlData[0];
                $user = $database->query("SELECT `id`,`name`, `parentId` FROM `topic` WHERE `id` = $userId");

                $children = $database->query("SELECT `id`,`name`, `parentId` FROM `topic` WHERE `parentId` = $userId");

                if ($user->num_rows != 0){
                    while ($row = $user->fetch_assoc()) {
                        $topicClass = new Topic($row['id'], $row['name'], $row['parentId']);

                        if ($children->num_rows != 0){
                            $arr = array();
                            while ($row2 = $children->fetch_assoc()) {


                                array_push($arr, new TopicWithoutChildren($row2['id'], $row2['name'], $row2['parentId']));
                            }

                        }
                        $topicClass->childs = $arr;
                        //echo json_encode(["sadsaddas" =>$topicClass]);

                        echo json_encode($topicClass);
                    }

                }
                else {
                    EntityNotFound404();
                }

                exit();


        } //  /users/{id}:  Get user information
    }

    else if (Count($urlData) === 2) {
        if (is_numeric($urlData[0])) {

            if ($urlData[1] == 'childs') {



                    $topicId = (int)$urlData[0];

                //echo json_encode(["hahah"=>$topicId]);

                    $users = $database->query("SELECT `id`,`name`,`topicId` FROM `task` WHERE `topicId` = '$topicId'");

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
function Post($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
    $headers = getallheaders();
    if (Count($urlData) === 0) {
        if ($generalAccessLevel == ADMIN_ACCESS_LEVEL){

            $name = $formData->name;
            $parentId = $formData->parentId;

            if ($parentId != ""){
                $database->query("INSERT INTO `topic` (`id`, `name`,`parentId`) 
                               VALUES (NULL, '$name', '$parentId')");
            }
            else {
                $database->query("INSERT INTO `topic` (`id`, `name`,`parentId`) 
                               VALUES (NULL, '$name', NULL)");
            }

            $name = strval($name);



            $topic = $database->query("SELECT `id`,`name`,`parentId` FROM `topic` WHERE `topic`.`name` = '$name' AND `topic`.`parentId` = '$parentId'");

            $userId = (int)$urlData[0];
            while ($row = $topic->fetch_assoc()) {
                $userId = $row['id'];
            }
            $user = $database->query("SELECT `id`,`name`, `parentId` FROM `topic` WHERE `id` = $userId");

            $children = $database->query("SELECT `id`,`name`, `parentId` FROM `topic` WHERE `parentId` = $userId");

            if ($user->num_rows != 0){
                while ($row = $user->fetch_assoc()) {
                    $topicClass = new Topic($row['id'], $row['name'], $row['parentId']);

                    if ($children->num_rows != 0){
                        $arr = array();
                        while ($row2 = $children->fetch_assoc()) {


                            array_push($arr, new TopicWithoutChildren($row2['id'], $row2['name'], $row2['parentId']));
                        }

                    }
                    $topicClass->childs = $arr;
                    //echo json_encode(["sadsaddas" =>$topicClass]);

                    echo json_encode($topicClass);
                }

            }
            else {
                EntityNotFound404();
            }

            exit();

        }

        else {
            Forbidden403();
        }

    }
}
function Patch($method, $urlData, $formData, $generalAccessLevel)
{
    global $database;
    $headers = getallheaders();
    if (Count($urlData) === 1) {
        if (is_numeric($urlData[0]) and $generalAccessLevel == ADMIN_ACCESS_LEVEL) {
            $name = $formData->name;
            $parentId = $formData->parentId;

            $userId = (int)$urlData[0];

            //echo json_encode($parentId);

            $database->query("UPDATE `topic` SET `name`='$name', `parentId`='$parentId' WHERE `topic`.`id` = $userId");


            $user = $database->query("SELECT `id`,`name`, `parentId` FROM `topic` WHERE `id` = $userId");

            $children = $database->query("SELECT `id`,`name`, `parentId` FROM `topic` WHERE `parentId` = $userId");

            if ($user->num_rows != 0){
                while ($row = $user->fetch_assoc()) {
                    $topicClass = new Topic($row['id'], $row['name'], $row['parentId']);

                    if ($children->num_rows != 0){
                        $arr = array();
                        while ($row2 = $children->fetch_assoc()) {


                            array_push($arr, new TopicWithoutChildren($row2['id'], $row2['name'], $row2['parentId']));
                        }

                    }
                    $topicClass->childs = $arr;
                    //echo json_encode(["sadsaddas" =>$topicClass]);

                    echo json_encode($topicClass);
                }

            }
            else {
                EntityNotFound404();
            }

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
function Delete($method, $urlData, $formData, $generalAccessLevel)
{

    global $database;
    $headers = getallheaders();
    if (Count($urlData) === 1) {
        if (is_numeric($urlData[0]) and $generalAccessLevel == ADMIN_ACCESS_LEVEL) {


            $userId = (int)$urlData[0];

            $database->query("DELETE FROM `topic` WHERE `topic`.`id` = $userId");

            echo json_encode([

                'message' => 'OK'
            ]);
            exit();


        }
        else {
            Forbidden403();
        }
    }


}
?>