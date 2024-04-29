<?php


function BadRequest400() {
    http_response_code(400);
    echo json_encode([

        'message' => 'Another data format expected'
    ]);
    exit();
}

function BadRequest420() {
    http_response_code(400);
    echo json_encode([

        'message' => 'Topic id might be incorrect. Avoid using apostrophe '
    ]);
    exit();
}

function BadRequest430() {
    http_response_code(400);
    echo json_encode([

        'message' => 'Parent id might be incorrect'
    ]);
    exit();
}

function Forbidden403() {
    http_response_code(403);
    echo json_encode([

        'message' => 'Access denied'
    ]);
    exit();
}

function EntityNotFound404() {
    http_response_code(404);
    echo json_encode([

        'message' => 'Object(s) do(es) not exist'
    ]);
    exit();
}

function InternalServerError500() {
    http_response_code(500);
    echo json_encode([

        'message' => 'Internal Server Error'
    ]);
    exit();
}
