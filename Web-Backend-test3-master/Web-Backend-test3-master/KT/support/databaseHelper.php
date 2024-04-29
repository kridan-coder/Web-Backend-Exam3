<?php

$database = new mysqli("localhost", "root", "", "kt3");

function ConfigureRole($roleName) {
    global $database;
    $role = $database->query("SELECT * FROM `role` WHERE `role`.`name` = '$roleName'");
    if ($role->num_rows == 0) {
        $database->query("INSERT INTO `role` (`roleId`, `Name`) VALUES (NULL, '$roleName')");
    }
}

function IdentifyOwner($bearerToken, $id): bool {
    global $database;

    if (isset($bearerToken)) {

        $token = str_replace("Bearer ", "", $bearerToken);
        $userId = ($database->query("SELECT * FROM `user` WHERE `user`.`token` = '$token'"))->fetch_assoc()["userId"];


        if ($id == $userId) {
            return ACCESS_ALLOWED;
        }

    }

    return ACCESS_DENIED; // Header doesn't contain a token or requester is not an owner
}


function ConfigureDB() {


    global $database;
    $adminRoleName = ADMIN_ROLE_NAME;

    ConfigureRole(USER_ROLE_NAME);
    ConfigureRole(MODERATOR_ROLE_NAME);
    ConfigureRole(ADMIN_ROLE_NAME);





    $adminRoleId = mysqli_fetch_array($database->query("SELECT `roleId` FROM `role` WHERE `role`.`name` = '$adminRoleName'"));
    $admin = $database->query("SELECT * FROM `user` WHERE `user`.`roleId` = '$adminRoleId[0]'");

    if($admin->num_rows == 0) {
        $token = generateToken();
        $password = md5("qwerty");
        $database->query("INSERT INTO `user` (`userId`, `username`, `roleId`, `name`, `surname`, `token`, `password`) 
                               VALUES (NULL, 'Main', '$adminRoleId[0]', 'Admin', 'Rootovich', '$token', '$password')");
    }
    //echo json_encode(["was here" => true]);
}

function GetGeneralAccessLevel($bearerToken): Int {
    global $database;

    if (isset($bearerToken)) {

        $token = str_replace("Bearer ", "", $bearerToken);

        $authorizedUser = mysqli_fetch_array($database->query("SELECT * FROM `user` WHERE `user`.`token` = '$token'"));

        if (isset($authorizedUser)) {
            $Role_Id = mysqli_fetch_array($database->query("SELECT roleId FROM `user` WHERE `user`.`token` = '$token'"))[0];

            if (isset($Role_Id)) {

                $Role_Name = mysqli_fetch_array($database->query("SELECT `name` FROM `role` WHERE `role`.`roleId` = '$Role_Id'"))[0];

                if (ADMIN_ROLE_NAME === $Role_Name) {

                    return ADMIN_ACCESS_LEVEL;
                }
                else if (MODERATOR_ROLE_NAME === $Role_Name) {
                    return MODERATOR_ACCESS_LEVEL;
                }
                else if (USER_ROLE_NAME === $Role_Name) {
                    return USER_ACCESS_LEVEL;
                }
            }
            return USER_ACCESS_LEVEL; // Such role doesn't contain special rights or wasn't declare any role for user
        }
        else {
            return UNAUTHORIZED_ACCESS_LEVEL; // DB doesn't contain a token
        }
    }

    return UNAUTHORIZED_ACCESS_LEVEL; // Header doesn't contain a token
}

function GetUserId($bearerToken): ?int {
    global $database;

    if (isset($bearerToken)) {
        $token = str_replace("Bearer ", "", $bearerToken);
        $userId = ($database->query("SELECT * FROM `user` WHERE `user`.`token` = '$token'"))->fetch_assoc()["userId"] ?? null;
        if ($userId != NULL) {
            return $userId;
        }
    }

    return NULL;
}


function GetUserRole($user): ?string {
    global $database;

    $userToken = $user["token"];
    $Role_Id = mysqli_fetch_array($database->query("SELECT roleId FROM `user` WHERE `user`.`token` = '$userToken'"))["roleId"];
    if (isset($Role_Id)) {
        $Role_Name = mysqli_fetch_array($database->query("SELECT `name` FROM `role` WHERE `role`.`id` = '$Role_Id'"))["name"];
        if (isset($Role_Name)) {
            return $Role_Name;
        }
    }
    return NULL;
}



?>