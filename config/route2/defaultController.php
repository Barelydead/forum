<?php
/**
 * Routes for user controller.
 */
return [
    "routes" => [
        [
            "info" => "Render user profile",
            "requestMethod" => "get",
            "path" => "users/profile/{name}",
            "callable" => ["userController", "userProfile"],
        ],
        [
            "info" => "Render user overview",
            "requestMethod" => "get",
            "path" => "users",
            "callable" => ["userController", "userOverview"],
        ],
        [
            "info" => "INDEX",
            "requestMethod" => "get",
            "path" => "",
            "callable" => ["commentController", "forumStart"],
        ],
    ]
];
