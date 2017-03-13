<?php

$app->get('/', "BlogWriter\Controller\HomeController::indexAction")
->bind('home');

// Detailed info about an article
$app->match('/article/{slug}', "BlogWriter\Controller\HomeController::articleAction")
->bind('article');

$app->get('/contact', "BlogWriter\Controller\HomeController::contactAction")
->bind('contact');