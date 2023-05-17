<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/admin' => [[['_route' => 'app_admin_index', '_controller' => 'App\\Controller\\AdminController::index'], null, null, null, true, false, null]],
        '/admin/contenu' => [[['_route' => 'app_admin_content', '_controller' => 'App\\Controller\\AdminController::content'], null, null, null, false, false, null]],
        '/admin/contenu/articles' => [[['_route' => 'app_admin_articles', '_controller' => 'App\\Controller\\AdminController::articles'], null, null, null, false, false, null]],
        '/admin/contenu/commentaires' => [[['_route' => 'app_admin_commentaries', '_controller' => 'App\\Controller\\AdminController::commentaries'], null, null, null, false, false, null]],
        '/admin/contenu/utilisateurs' => [[['_route' => 'app_admin_users', '_controller' => 'App\\Controller\\AdminController::users'], null, null, null, false, false, null]],
        '/admin/contenu/signalements' => [[['_route' => 'app_admin_reports', '_controller' => 'App\\Controller\\AdminController::reports'], null, null, null, false, false, null]],
        '/admin/structure' => [[['_route' => 'app_admin_structure', '_controller' => 'App\\Controller\\AdminController::structure'], null, null, null, false, false, null]],
        '/admin/structure/categories' => [[['_route' => 'app_admin_structure_categories', '_controller' => 'App\\Controller\\AdminController::categories'], null, null, null, false, false, null]],
        '/admin/structure/categories/add' => [[['_route' => 'app_admin_structure_categories_add', '_controller' => 'App\\Controller\\AdminController::addCategories'], null, null, null, false, false, null]],
        '/admin/structure/signalements' => [[['_route' => 'app_admin_structure_reportReasons', '_controller' => 'App\\Controller\\AdminController::reportReasons'], null, null, null, false, false, null]],
        '/admin/structure/signalements/add' => [[['_route' => 'app_admin_structure_reportReasons_add', '_controller' => 'App\\Controller\\AdminController::addReportReasons'], null, null, null, false, false, null]],
        '/blog' => [[['_route' => 'app_blog', '_controller' => 'App\\Controller\\BlogController::index'], null, null, null, false, false, null]],
        '/politique' => [[['_route' => 'politique', '_controller' => 'App\\Controller\\BlogController::politique'], null, null, null, false, false, null]],
        '/economie' => [[['_route' => 'economie', '_controller' => 'App\\Controller\\BlogController::economie'], null, null, null, false, false, null]],
        '/geopolitique' => [[['_route' => 'geopolitique', '_controller' => 'App\\Controller\\BlogController::geopolitique'], null, null, null, false, false, null]],
        '/societe' => [[['_route' => 'societe', '_controller' => 'App\\Controller\\BlogController::societe'], null, null, null, false, false, null]],
        '/arts-litteratures' => [[['_route' => 'artsLitteratures', '_controller' => 'App\\Controller\\BlogController::artsLitteratures'], null, null, null, false, false, null]],
        '/parole-libre' => [[['_route' => 'paroleLibre', '_controller' => 'App\\Controller\\BlogController::paroleLibre'], null, null, null, false, false, null]],
        '/accueil' => [[['_route' => 'accueil', '_controller' => 'App\\Controller\\IndexController::index'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/([^/]++)(?'
                        .'|/(?'
                            .'|search/results(*:102)'
                            .'|router(*:116)'
                            .'|exception(?'
                                .'|(*:136)'
                                .'|\\.css(*:149)'
                            .')'
                        .')'
                        .'|(*:159)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        102 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        116 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        136 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        149 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        159 => [
            [['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
