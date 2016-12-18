<?php
require_once '../vendor/autoload.php';

use FlaskPHP\FlaskPHP;
use FlaskPHP\Template\PhpTemplate;
use FlaskPHP\Template\TwigTemplate;

$app = new FlaskPHP(__DIR__);

$app->route('/', function () {
    return 'This is Index.';
});

$app->route('/twig', function () {
    return TwigTemplate::render('twigs/a.twig', [
        'A'=>'Apple',
        'B'=>'Banana',
        'C'=>'Cup'
    ]);
});

$app->route('/php', function () {
    return PhpTemplate::render('phps/a.php', [
        'A'=>'Apple',
        'B'=>'Banana',
        'C'=>'Cup'
    ]);
});

$app->get('/redirect', function () {
    return redirect(url_for('/php'));
});

$app->get('/get/<int:int>', function ($int) {
    var_dump($int);
});

$app->get('/get/<float:float>', function ($float) {
    var_dump($float);
});

$app->get('/get/<string:str>', function ($str) {
    return $str;
});

$app->post('/post/<string:text>', function ($text) {
    return "input: {$text}";
});

$app->delete('/delete/<string:text>', function ($text) {
    return "input: {$text}";
});

$app->run();