<?php
/**
 * Configuration file for routes.
 */
return [
    // Load these routefiles in order specified and optionally mount them
    // onto a base route.
    "routeFiles" => [
        [
            // These are for internal error handling and exceptions
            "mount" => null,
            "file" => __DIR__ . "/route2/internal.php",
        ],
        [
            // For debugging and development details on Anax
            "mount" => "debug",
            "file" => __DIR__ . "/route2/debug.php",
        ],
        [
            // Add routes from userController/
            "mount" => null,
            "file" => __DIR__ . "/route2/userController.php",
        ],
        [
            // Add routes from commentController/
            "mount" => "forum",
            "file" => __DIR__ . "/route2/commentController.php",
        ],
        [
            // Add routes from defaultController and mount on user/
            "mount" => null,
            "file" => __DIR__ . "/route2/defaultController.php",
        ],
        [
            // To read flat file content in Markdown from content/
            "mount" => null,
            "file" => __DIR__ . "/route2/flat-file-content.php",
        ],
        [
            // Keep this last since its a catch all
            "mount" => null,
            "file" => __DIR__ . "/route2/404.php",
        ],
    ],

];
