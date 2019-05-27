<?php

return [
    //"sms.ru", "array"
    'driver' => env('SMS_DRIVER'),
    
    'drivers' => [
        'sms.ru' => [
            'app_id' => env('SMS_RU_APP_ID'),
            'url' => env('SMS_RU_URL')
        ],
        'nexmo' => [
            'app_id' => env('SMS_NEXMO_APP_ID'),
            'url' => env('SMS_NEXMO_URL')
        ],
    ],
];