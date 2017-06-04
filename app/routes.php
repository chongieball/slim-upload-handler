<?php 

$app->post('/images', 'App\Controllers\UploadController:uploadImage');

$app->post('/videos', 'App\Controllers\UploadController:uploadVideo');