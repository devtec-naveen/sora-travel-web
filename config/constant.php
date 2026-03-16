<?php

return [

    'image_base_url' => env('IMAGE_BASE_URL'),

    'role' => [
        1 => 'user',
        2 => 'admin'
    ],

    'roleText' => [
        'user' => 1,
        'admin' => 2
    ],

    'flight_trip_types' => [
        'oneway' => 'oneway',
        'roundtrip' => 'roundtrip',
        'multicity' => 'multicity',
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


    'airlines' => [
        'AI' => [
            'name' => 'Air India',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/3/3e/Air_India_Logo.svg',
        ],
        '6E' => [
            'name' => 'IndiGo',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/7/7c/IndiGo_logo.svg',
        ],
        'SG' => [
            'name' => 'SpiceJet',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/5/52/SpiceJet_logo.svg',
        ],
        'UK' => [
            'name' => 'Vistara',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/e/e2/Vistara_logo.svg',
        ],
        'IX' => [
            'name' => 'Air India Express',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/f/f2/Air_India_Express_logo.svg',
        ],
        'JA' => [
            'name' => 'Jet Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/0/05/Jet_Airways_logo.svg',
        ],
        'QF' => [
            'name' => 'Qantas Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/9/96/Qantas_airways_logo.svg',
        ],
        'BA' => [
            'name' => 'British Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/d/d7/British_Airways_logo.svg',
        ],
        'LH' => [
            'name' => 'Lufthansa',
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/d/df/Lufthansa_Logo_2018.svg',
        ],
        'AF' => [
            'name' => 'Air France',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/c/c3/Air_France_Logo.svg',
        ],
        'EK' => [
            'name' => 'Emirates',
            'logo' => 'https://upload.wikimedia.org/wikipedia/commons/c/c7/Emirates_logo.svg',
        ],
        'QR' => [
            'name' => 'Qatar Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/6/60/Qatar_Airways_Logo.svg',
        ],
        'CX' => [
            'name' => 'Cathay Pacific',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/1/1e/Cathay_Pacific_logo.svg',
        ],
        'SQ' => [
            'name' => 'Singapore Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/f/fc/Singapore_Airlines_Logo.svg',
        ],
        'TK' => [
            'name' => 'Turkish Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/0/0e/Turkish_Airlines_Logo.svg',
        ],
        'ET' => [
            'name' => 'Ethiopian Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/3/30/Ethiopian_Airlines_logo.svg',
        ],
        'NH' => [
            'name' => 'All Nippon Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/e/ef/All_Nippon_Airways_Logo.svg',
        ],
        'JL' => [
            'name' => 'Japan Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/4/4f/Japan_Airlines_logo.svg',
        ],
        'UA' => [
            'name' => 'United Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/0/0b/United_Airlines_Logo.svg',
        ],
        'DL' => [
            'name' => 'Delta Air Lines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/0/05/Delta_Air_Lines_Logo.svg',
        ],
        'AA' => [
            'name' => 'American Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/3/36/American_Airlines_logo_2013.svg',
        ],
        'AC' => [
            'name' => 'Air Canada',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/4/4e/Air_Canada_Logo_2017.svg',
        ],
        'KQ' => [
            'name' => 'Kenya Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/f/f2/Kenya_Airways_logo.svg',
        ],
        'EY' => [
            'name' => 'Etihad Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/4/4f/Etihad_Airways_logo.svg',
        ],
        'MS' => [
            'name' => 'EgyptAir',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/7/7e/EgyptAir_Logo.svg',
        ],
        'SU' => [
            'name' => 'Aeroflot',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/e/e3/Aeroflot_logo.svg',
        ],
        'NZ' => [
            'name' => 'Air New Zealand',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/3/3e/Air_New_Zealand_logo.svg',
        ],
        'OM' => [
            'name' => 'MIAT Mongolian Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/1/1a/MIAT_Mongolian_Airlines_logo.svg',
        ],
        'PC' => [
            'name' => 'Air Niugini',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/4/46/Air_Niugini_logo.svg',
        ],
        'MH' => [
            'name' => 'Malaysia Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/1/16/Malaysia_Airlines_logo.svg',
        ],
        'PR' => [
            'name' => 'Philippine Airlines',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/9/90/Philippine_Airlines_logo.svg',
        ],
        'TG' => [
            'name' => 'Thai Airways',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/3/3f/Thai_Airways_logo.svg',
        ],
        'VJ' => [
            'name' => 'VietJet Air',
            'logo' => 'https://upload.wikimedia.org/wikipedia/en/4/41/Vietjet_Air_logo.svg',
        ],
    ],
];
