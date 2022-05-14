<?php

use AngryMoustache\Rambo\Resources\Administrator;
use AngryMoustache\Rambo\Resources\Attachment;
use App\Resources\Page;
use App\Resources\PageTranslation;
use App\Resources\Tag;
use App\Resources\Pull;

return [
    'admin-route' => 'admin',
    'admin-guard' => 'rambo',
    'resources' => [
        Attachment::class,
        Administrator::class,
        Pull::class,
        Tag::class,
        Page::class,
        PageTranslation::class,
    ],
    'navigation' => [
        'General' => [
            Administrator::class,
            Attachment::class,
        ],
        Pull::class,
        Tag::class,
        Page::class,
    ],
];
