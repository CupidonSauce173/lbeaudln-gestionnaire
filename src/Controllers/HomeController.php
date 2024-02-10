<?php

namespace Lbeaudln\Gestionnaire\Controllers;

use JetBrains\PhpStorm\NoReturn;

class HomeController
{
    /**
     * Controller method - index | GET
     *
     * Redirects the user to the login page. This method serves as the default entry point
     * for a controller where no specific action is determined, effectively guiding users
     * to start from the login form.
     *
     * No parameters are required.
     *
     * @return void
     */
    #[NoReturn] public function index(): void
    {
        self::redirect("/login");
    }

    /**
     * Redirects the user to a specified URL path. This static method facilitates
     * redirection throughout the application by setting the HTTP Location header.
     * The script execution is terminated after setting the header to ensure the
     * immediate redirection.
     *
     * @param string $urlPath The URL path to redirect the user to.
     *
     * @return void
     */
    #[NoReturn] public static function redirect(string $urlPath): void
    {
        header("Location: $urlPath");
        exit;
    }
}