<?php
require_once 'loader.php';
require_once '../vendor/autoload.php';

use FlaskPHP\FlaskPHP;
use FlaskPHP\Template\PhpTemplate;
use FlaskPHP\Template\TwigTemplate;

$app = new FlaskPHP(__DIR__);

$app->route('/', function () {
    return 'This is Index.';
});

$app->route('/twig', function () {
    return TwigTemplate::render('twigs/a.php', [
        'A'=>'Apple',
        'B'=>'Banana',
        'C'=>'Cup'
    ]);
});

$app->route('/php', function () {
    return PhpTemplate::render('twigs/a.php', [
        'A'=>'Apple',
        'B'=>'Banana',
        'C'=>'Cup'
    ]);
});

$app->get('/get/<string:str>', function ($str) {
    return $str;
});

$app->post('/post/<str>', function ($str) {
    return $str;
});

$app->delete('/delete/<str>', function ($str) {
    return $str;
});

$app->run();