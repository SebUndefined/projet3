<?php

$app->get('/', "BlogWriter\Controller\HomeController::indexAction")
->bind('home');



// Show all articles
$app->get('/all-articles/{page}', "BlogWriter\Controller\HomeController::articleIndexAction")
->bind('all-articles');

// Detailed info about an article
$app->match('/article/{slug}', "BlogWriter\Controller\HomeController::articleAction")
->bind('article');

$app->get('/contact', "BlogWriter\Controller\HomeController::contactAction")
->bind('contact');