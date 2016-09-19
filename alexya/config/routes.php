<?php
/**
 * Alexya Framework - The intelligent Loli Framework
 *
 * This file contains the default routes.
 * All of them can be overridden by using
 * the class `\Alexya\Router\Router`
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */

return [
    /**
     * Default route.
     *
     * Performs the routing based on the request URI.
     *
     * It goes as follow:
     *
     *     /Controller/Action
     *
     * If `Action` is empty the default action `index` will be called on `Controller`,
     * this means that `/Login/index` is the same as `/Login`.
     *
     * If the controller for the request doesn't exist an exception will be thrown.
     *
     * If the action can't be executed, the default action `index` will be executed.
     *
     * The controller action can return a string being the response content,
     * how ever, it's recommended that it returns an instance of the `\Alexya\Http\Response`
     * object to send.
     *
     * Finally, the response is sent and the script finish the execution.
     */
    "{DEFAULT}" => function() {
        $request = \Alexya\Http\Request::main();
        $uri     = $request->uri();

        $page    = $uri[1];
        $action  = ($_POST["action"] ?? ($uri[2] ?? "index"));
        $params  = array_slice($uri, 3);

        $triad = new \Alexya\Foundation\Page($page, $request);

        if($triad->Controller == null) {
            // The requested action isn't available, throw exception
            throw new Exception("The requested action couldn't be performed!");
        }

        if(
            !is_callable([$triad->Controller, $action]) ||
            empty($page)
        ) {
            // The requested action isn't available, use `index` as action
            $action = "index";
        }

        if(!empty($_POST)) {
            unset($_POST["action"]);
            $params = $_POST;
        }

        $response = $triad->Controller->$action(... array_values($params));

        if(!$response instanceof \Alexya\Http\Response) {
            // Allow user to return the content of the response instead of the
            // whole response object
            $response = new \Alexya\Http\Response([
                "Content-Type" => "text/html"
            ], $response);
        }

        $response->send();
        die();
    },

    /**
     * Page verification.
     *
     * This route assures that the requested page is either /External`
     * or `/Internal`, if none of both is requested user will be redirected
     * to `/External/Login`.
     */
    "(.*)" => function() {
        $uri = \Alexya\Http\Request::main()->uri();

        if(
            $uri[1] != "External" &&
            $uri[1] != "Internal"
        ) {
            \Alexya\Http\Response::redirect("/External/Login");
        }
    },

    /**
     * Login verification for External page.
     *
     * This route will be executed if the External page is requested.
     * It won't do anything but check that the user isn't logged yet, and if
     * it is, it will be redirected to `/Internal/Start`.
     *
     * It will also check that the invitation code is properly verified.
     *
     * This way the default route is also executed even so it can render the page.
     */
    "/External(/?)(.*)" => function() {
        // Check login status.
        if(\Alexya\Container::Account()->isLogged()) {
            \Alexya\Http\Response::redirect("/Internal/Start");
        }

        // Check invitation code verification.
        if(!\Alexya\Container::Settings()->get("application.invitation.enabled")) {
            return;
        }

        if(
            !\Alexya\Container::Account()->hasVerifiedInvitationCode() &&
            \Alexya\Http\Request::main()->uri()[2] != "InvitationCode"
        ) {
            \Alexya\Http\Response::redirect("/External/InvitationCode");
        }
    },

    /**
     * Login verification for Internal page.
     *
     * This route will be executed if the Internal page is requested.
     * It won't do anything but check that the user is logged yet, and if
     * it isn't, it will be redirected to `/External/Login`.
     *
     * This way the default route is also executed even so it can render the page.
     */
    "/Internal(/?)(.*)" => function() {
        // Check that account is logged
        if(!\Alexya\Container::Account()->isLogged()) {
            \Alexya\Http\Response::redirect("/External/Login");
        }
        // Check that account has choosen a company
        if(
            \Alexya\Container::Account()->factions_id == 0 &&
            \Alexya\Http\Request::main()->uri()[2]    != "CompanyChoose"
        ) {
            \Alexya\Http\Response::redirect("/Internal/CompanyChoose");
        }
    },

    /**
     * Delete session and redirect to /External/Login
     */
    "/Internal/Logout" => function() {
        session_destroy();
        \Alexya\Http\Response::redirect("/External/Login");
    }
];
