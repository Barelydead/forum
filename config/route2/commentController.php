<?php
/**
 * Routes for user controller.
 */
return [
    "routes" => [
        [
            "info" => "form for new post",
            "requestMethod" => "get|post",
            "path" => "new",
            "callable" => ["formController", "newComment"],
        ],
        [
            "info" => "Display posts",
            "requestMethod" => "get",
            "path" => "posts",
            "callable" => ["commentController", "renderComments"],
        ],
        [
            "info" => "Display a single post with all replies",
            "requestMethod" => "get|post",
            "path" => "post/{id}",
            "callable" => ["commentController", "renderComment"],
        ],
        [
            "info" => "Reply to a post",
            "requestMethod" => "get|post",
            "path" => "reply/{id}",
            "callable" => ["commentController", "replyToPost"],
        ],
        [
            "info" => "Comment on a reply",
            "requestMethod" => "get|post",
            "path" => "reply/{qId}/comment/{rId}",
            "callable" => ["formController", "commentReply"],
        ],
        [
            "info" => "Get post overview based on tag name",
            "requestMethod" => "get|post",
            "path" => "tags",
            "callable" => ["commentController", "renderTagView"],
        ],
        [
            "info" => "Get list of questions with a certian tag name",
            "requestMethod" => "get|post",
            "path" => "tag/{name}",
            "callable" => ["commentController", "tagContent"],
        ],
        [
            "info" => "Edit question",
            "requestMethod" => "get|post",
            "path" => "edit/{id}",
            "callable" => ["formController", "editPost"],
        ],
        [
            "info" => "edit tags for question",
            "requestMethod" => "get|post",
            "path" => "edit/tags/{id}",
            "callable" => ["formController", "editTags"],
        ],
        [
            "info" => "Add tag desc",
            "requestMethod" => "get|post",
            "path" => "update/tag/{id}",
            "callable" => ["formController", "editTagDescription"],
        ],
        [
            "info" => "mark answer as accepted",
            "requestMethod" => "get|post",
            "path" => "post/mark/reply/{id}",
            "callable" => ["commentController", "markAnswer"],
        ],
        [
            "info" => "vote on post",
            "requestMethod" => "get|post",
            "path" => "post/{id}/{user}/{type}",
            "callable" => ["commentController", "voteOnPost"],
        ],
        [
            "info" => "Delete post",
            "requestMethod" => "get|post",
            "path" => "post/delete/{id}",
            "callable" => ["formController", "deletePost"],
        ],
    ]
];
