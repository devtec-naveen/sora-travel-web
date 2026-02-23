<?php

return [

    'role' => [
        1 => 'user',
        2 => 'admin'
    ],

    'roleText' => [
        'user' => 1,
        'admin' => 2
    ],

    'httpCode' => [

        // Success Codes
        'SUCCESS_OK' => 200,
        'SUCCESS_CREATED' => 201,
        'SUCCESS_ACCEPTED' => 202,
        'SUCCESS_NO_CONTENT' => 204,

        // Client Errors
        'BAD_REQUEST' => 400,
        'UNAUTHORIZED' => 401,
        'FORBIDDEN' => 403,
        'NOT_FOUND' => 404,
        'METHOD_NOT_ALLOWED' => 405,
        'CONFLICT' => 409,
        'CSRF_TOKEN_ERROR' => 419,
        'UNPROCESSABLE_ENTITY' => 422,

        // Server Errors
        'INTERNAL_SERVER_ERROR' => 500,
        'SERVICE_UNAVAILABLE' => 503,
    ],
];
