<?php
return [
    'messaging' => [
        'credentials' => env('FIREBASE_CREDENTIALS', 'storage/firebase/service-account.json'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
    ],
];
