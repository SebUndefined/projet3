<?php

$app->get('/', "BlogWriter\Controller\HomeController::indexAction")
->bind('home');