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

}
function Post($method, $urlData, $formData, $generalAccessLevel)
{

}
function Patch($method, $urlData, $formData, $generalAccessLevel)
{

}
function Delete($method, $urlData, $formData, $generalAccessLevel)
{

}



?>