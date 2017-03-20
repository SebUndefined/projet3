<?php

$app->get('/', "BlogWriter\Controller\HomeController::indexAction")
->bind('home');



// Show all articles

$app->get('/all-articles/{page}', "BlogWriter\Controller\HomeController::articleIndexAction")
->bind('all-articles');

// Detailed info about an article
$app->match('/article/{slug}', "BlogWriter\Controller\HomeController::articleAction")
->bind('article');

//All Categories
$app->get('/all-categories', "BlogWriter\Controller\HomeController::categoriesIndexAction")
->bind('all-categories');

//Articles for one category
$app->get('/category/{slug}', "BlogWriter\Controller\HomeController::categoryAction")
->bind('category');

//Contact page
$app->get('/contact', "BlogWriter\Controller\HomeController::contactAction")
->bind('contact');

$app->get('/login', "BlogWriter\Controller\AdminController::loginIndex")
->bind('login');

$app->get('/manager', "BlogWriter\Controller\AdminController::adminIndex")
->bind('manager');

$app->match('/manager/category/add', "BlogWriter\Controller\AdminController::addCategoryAction")
->bind('manager_category_add');