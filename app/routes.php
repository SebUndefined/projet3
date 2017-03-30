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

//Manager root
$app->get('/manager', "BlogWriter\Controller\AdminController::adminIndex")
->bind('manager');

//Manager Categories
$app->get('/manager/category', "BlogWriter\Controller\AdminController::adminCategoryAction")
->bind('manager_category');

$app->match('/manager/category/add', "BlogWriter\Controller\AdminController::addCategoryAction")
->bind('manager_category_add');

$app->match('/manager/category/{id}/edit', "BlogWriter\Controller\AdminController::editCategoryAction")
->bind('manager_category_edit');

$app->get('/manager/category/{id}/delete', "BlogWriter\Controller\AdminController::deleteCategoryAction")
->bind('manager_category_delete');

//Manager Comments
$app->match('/comment/{idParent}/{idArticle}/add', "BlogWriter\Controller\HomeController::addCommentAction")
->bind('comment_add');
$app->get('/manager/comment/{id}/delete', "BlogWriter\Controller\AdminController::deleteCommentAction")
->bind('manager_comment_delete');



//Manager Reportings
$app->get('/manager/reporting', "BlogWriter\Controller\AdminController::adminReportingAction")
->bind('manager_reporting');
$app->get('/manager/reporting/{id}/delete', "BlogWriter\Controller\AdminController::deleteReportingAction")
->bind('manager_reporting_delete');

//Manager Articles
$app->match('/manager/article/add', "BlogWriter\Controller\AdminController::addArticleAction")
->bind('manager_article_add');
$app->match('/manager/article/{id}/edit', "BlogWriter\Controller\AdminController::editArticleAction")
->bind('manager_article_edit');
$app->get('/manager/article', "BlogWriter\Controller\AdminController::adminArticleAction")
->bind('manager_article');
$app->get('/manager/article/{id}/delete', "BlogWriter\Controller\AdminController::deleteArticleAction")
->bind('manager_article_delete');





