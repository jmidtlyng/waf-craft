<?php
use craft\helpers\App;

return [
    'id' => App::env('APP_ID') ?: 'CraftCMS',
    'modules' => [
      'YkRss' => \modules\YkRss\YkRss::class,
    ],
    'bootstrap' => ['YkRss'],
];
