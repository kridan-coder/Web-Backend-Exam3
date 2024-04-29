<?php

function PrintUsers($users, $generalAccessLevel) {

    if ($users->num_rows > 0) {
        $array = array();

        while ($row = $users->fetch_assoc()) {

            $obj = new stdClass;
            $obj->Id = $row['id'];
            $obj->Name = $row['name'];
            $obj->Surname = $row['surname'];
            $obj->Status = $row['status'];
            $obj->City = GetUserCity($row);
            if ($generalAccessLevel === ADMIN_ACCESS_LEVEL) {
                $obj->Birthday = $row['birthday'];
                $obj->Role = GetUserRole($row);
            }
            array_push($array, $obj);
        }
        header('HTTP/1.0 200 OK');
        echo json_encode($array);
    }
}

function printJSON($entities) {
    if ($entities->num_rows > 0) {
        $array = array();
        while ($row = $entities->fetch_assoc()) {
            array_push($array, $row);
        }
        header('HTTP/1.0 200 OK');
        echo json_encode($array);
    }
}

function printTopic($entities, $children) {
    if ($entities->num_rows > 0) {
        $array = array();
        while ($row = $entities->fetch_assoc()) {
            array_push($array, $row);
        }
    }

    $childs = array();

    if ($children->num_rows > 0) {
        while ($row = $children->fetch_assoc()) {
            array_push($childs, $row);
        }
    }
    $array += array("childs" => $childs);

    header('HTTP/1.0 200 OK');
    echo json_encode($array);
}