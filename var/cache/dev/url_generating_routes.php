<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    '_wdt' => [['token'], ['_controller' => 'web_profiler.controller.profiler::toolbarAction'], [], [['variable', '/', '[^/]++', 'token', true], ['text', '/_wdt']], [], [], []],
    '_profiler_home' => [[], ['_controller' => 'web_profiler.controller.profiler::homeAction'], [], [['text', '/_profiler/']], [], [], []],
    '_profiler_search' => [[], ['_controller' => 'web_profiler.controller.profiler::searchAction'], [], [['text', '/_profiler/search']], [], [], []],
    '_profiler_search_bar' => [[], ['_controller' => 'web_profiler.controller.profiler::searchBarAction'], [], [['text', '/_profiler/search_bar']], [], [], []],
    '_profiler_phpinfo' => [[], ['_controller' => 'web_profiler.controller.profiler::phpinfoAction'], [], [['text', '/_profiler/phpinfo']], [], [], []],
    '_profiler_xdebug' => [[], ['_controller' => 'web_profiler.controller.profiler::xdebugAction'], [], [['text', '/_profiler/xdebug']], [], [], []],
    '_profiler_search_results' => [['token'], ['_controller' => 'web_profiler.controller.profiler::searchResultsAction'], [], [['text', '/search/results'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_open_file' => [[], ['_controller' => 'web_profiler.controller.profiler::openAction'], [], [['text', '/_profiler/open']], [], [], []],
    '_profiler' => [['token'], ['_controller' => 'web_profiler.controller.profiler::panelAction'], [], [['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_router' => [['token'], ['_controller' => 'web_profiler.controller.router::panelAction'], [], [['text', '/router'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_exception' => [['token'], ['_controller' => 'web_profiler.controller.exception_panel::body'], [], [['text', '/exception'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_exception_css' => [['token'], ['_controller' => 'web_profiler.controller.exception_panel::stylesheet'], [], [['text', '/exception.css'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    'app_admin_index' => [[], ['_controller' => 'App\\Controller\\AdminController::index'], [], [['text', '/admin/']], [], [], []],
    'app_admin_content' => [[], ['_controller' => 'App\\Controller\\AdminController::content'], [], [['text', '/admin/contenu']], [], [], []],
    'app_admin_content_articles_index' => [[], ['_controller' => 'App\\Controller\\AdminController::indexArticles'], [], [['text', '/admin/contenu/articles/']], [], [], []],
    'app_admin_content_article_add' => [[], ['_controller' => 'App\\Controller\\AdminController::addArticle'], [], [['text', '/admin/contenu/articles/ajouter-article']], [], [], []],
    'app_admin_content_article_edit' => [['id'], ['_controller' => 'App\\Controller\\AdminController::editArticle'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/admin/contenu/articles/modifier-article']], [], [], []],
    'app_admin_commentaries' => [[], ['_controller' => 'App\\Controller\\AdminController::commentaries'], [], [['text', '/admin/contenu/commentaires']], [], [], []],
    'app_admin_users' => [[], ['_controller' => 'App\\Controller\\AdminController::users'], [], [['text', '/admin/contenu/utilisateurs']], [], [], []],
    'app_admin_reports' => [[], ['_controller' => 'App\\Controller\\AdminController::reports'], [], [['text', '/admin/contenu/signalements']], [], [], []],
    'app_admin_structure' => [[], ['_controller' => 'App\\Controller\\AdminController::structure'], [], [['text', '/admin/structure']], [], [], []],
    'app_admin_structure_categories' => [[], ['_controller' => 'App\\Controller\\AdminController::categories'], [], [['text', '/admin/structure/categories']], [], [], []],
    'app_admin_structure_categories_add' => [[], ['_controller' => 'App\\Controller\\AdminController::addCategories'], [], [['text', '/admin/structure/categories/add']], [], [], []],
    'app_admin_structure_reportReasons' => [[], ['_controller' => 'App\\Controller\\AdminController::reportReasons'], [], [['text', '/admin/structure/signalements']], [], [], []],
    'app_admin_structure_reportReasons_add' => [[], ['_controller' => 'App\\Controller\\AdminController::addReportReasons'], [], [['text', '/admin/structure/signalements/add']], [], [], []],
    'app_category' => [['categorySlug', 'id'], ['_controller' => 'App\\Controller\\BlogController::categoryPage'], [], [['variable', '/', '[^/]++', 'id', true], ['variable', '/', '[^/]++', 'categorySlug', true]], [], [], []],
    'app_category_article' => [['categorySlug', 'id'], ['_controller' => 'App\\Controller\\BlogController::showArticle'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/article'], ['variable', '/', '[^/]++', 'categorySlug', true]], [], [], []],
    'app_article_comment_update' => [['categorySlug', 'id', 'commentId'], ['_controller' => 'App\\Controller\\BlogController::updateComment'], [], [['text', '/update'], ['variable', '/', '[^/]++', 'commentId', true], ['text', '/comment'], ['variable', '/', '[^/]++', 'id', true], ['text', '/article'], ['variable', '/', '[^/]++', 'categorySlug', true]], [], [], []],
    'app_article_comment_del' => [['categorySlug', 'id', 'commentId'], ['_controller' => 'App\\Controller\\BlogController::delComment'], [], [['text', '/delete'], ['variable', '/', '[^/]++', 'commentId', true], ['text', '/comment'], ['variable', '/', '[^/]++', 'id', true], ['text', '/article'], ['variable', '/', '[^/]++', 'categorySlug', true]], [], [], []],
    'app_comment_like_add' => [['categorySlug', 'id', 'commentId'], ['_controller' => 'App\\Controller\\BlogController::toggleCommentLike'], [], [['variable', '/', '[^/]++', 'commentId', true], ['text', '/like-comment'], ['variable', '/', '[^/]++', 'id', true], ['variable', '/', '[^/]++', 'categorySlug', true]], [], [], []],
    'accueil' => [[], ['_controller' => 'App\\Controller\\IndexController::index'], [], [['text', '/accueil']], [], [], []],
    'app_register' => [[], ['_controller' => 'App\\Controller\\RegistrationController::register'], [], [['text', '/inscription']], [], [], []],
    'app_login' => [[], ['_controller' => 'App\\Controller\\SecurityController::login'], [], [['text', '/connexion']], [], [], []],
    'app_logout' => [[], ['_controller' => 'App\\Controller\\SecurityController::logout'], [], [['text', '/logout']], [], [], []],
];
