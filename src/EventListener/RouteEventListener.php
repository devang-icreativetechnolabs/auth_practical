<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RouteEventListener
{
    public function __invoke(RequestEvent $event)
    {
        $request = $event->getRequest();

        dd($request);
        // Get the current route
        $route = $request->attributes->get('_route');

        // Allow public access to login and register pages
        $publicRoutes = ['app_login', 'app_register'];

        if (!in_array($route, $publicRoutes) && !$request->getUser()) {
            // Redirect unauthenticated users to the login page
            $response = new RedirectResponse('app_login');
            $event->setResponse($response);
        }
    }
}
