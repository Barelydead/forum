<?php
/**
 * Routes for user controller.
 */
return [
    "routes" => [
        [
            "info" => "Check if user is logged in on all routes",
            "requestMethod" => "get",
            "path" => "user/**",
            "callable" => ["userController", "checkUser"],
        ],
        [
            "info" => "Login a user.",
            "requestMethod" => "get|post",
            "path" => "login",
            "callable" => ["userController", "login"],
        ],
        [
            "info" => "Create a user.",
            "requestMethod" => "get|post",
            "path" => "create",
            "callable" => ["userController", "create"],
        ],
        [
            "info" => "Logout the user",
            "requestMethod" => "get|post",
            "path" => "user/logout",
            "callable" => ["userController", "logout"],
        ],
        [
            "info" => "get the users profile",
            "requestMethod" => "get",
            "path" => "user/profile",
            "callable" => ["userController", "profile"],
        ],
        [
            "info" => "Update user profile",
            "requestMethod" => "get|post",
            "path" => "user/edit/profile",
            "callable" => ["userController", "editProfile"],
        ],
        [
            "info" => "update password",
            "requestMethod" => "get|post",
            "path" => "user/edit/password",
            "callable" => ["userController", "editPassword"],
        ],
    ]
];
