<?php

define(
    'UPLOAD_PATH',
    __DIR__ . '/../../../../assets/uploads/banner/'
);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

define('ALLOWED_EXTENSION', [

    'jpg',

    'jpeg',

    'png',

    'webp'

]);

define('MAX_WIDTH', 5000);

define('MAX_HEIGHT', 5000);