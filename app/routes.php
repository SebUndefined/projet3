<?php

$app->get('/', "BlogWriter\Controller\HomeController::indexAction")
->bind('home');


$app->get('/contact', "BlogWriter\Controller\HomeController::contactAction")
->bind('contact');