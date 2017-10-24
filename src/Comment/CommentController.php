<?php

namespace CJ\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * A controller for the comment section
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;

    /**
     * INDEX PAGE
     */
    public function forumStart()
    {
        $data = ["title" => "Start"];
        $user = $this->di->get("user");
        $tag = $this->di->get("tag");
        $comment = $this->di->get("comment");

        $userList = $user->getMostActiveUser();
        $postList = $comment->getCommentsWithUserInfo("CREATED", 2);
        $tagList = $tag->getAllTags();
        $usedTags = $tag->getMostUsedTags(3);

        $this->di->get("view")->add("components/start", [], "main");

        $this->di->get("view")->add("default1/article", ["content" => "<h2>latest Posts</h2>"], "main");
        $this->di->get("view")->add("components/list-comments", ["comments" => $postList, "tags" => $tagList], "main");

        $this->di->get("view")->add("default1/article", ["content" => "<h2>Most used tags</h2>"], "main");
        $this->di->get("view")->add("components/tags", ["tags" => $usedTags], "main");

        $this->di->get("view")->add("default1/article", ["content" => "<h2>Users with most karma</h2>"], "main");
        $this->di->get("view")->add("components/user-overview", ["users" => $userList], "main");
        $this->di->get("pageRender")->renderPage($data);
    }


    /**
     * render comment view
     */
    public function renderComments()
    {
        $tag = $this->di->get("tag");
        $comment = $this->di->get("comment");
        $request = $this->di->get("request");

        if ($request->getGet("sort") == "upvote") {
            $comments = $comment->getCommentsWithUserInfo("LIKES");
        } else {
            $comments = $comment->getCommentsWithUserInfo();
        }

        $data = ["title" => "guestbook"];

        $tags = $tag->getAllTags();

        $this->di->get("view")->add("components/post-nav", [], "main");
        $this->di->get("view")->add("components/list-comments", [
            "comments" => $comments,
            "tags" => $tags
        ], "main");

        $this->di->get("pageRender")->renderPage($data);
    }

    /**
     * render comment view
     */
    public function renderComment($id)
    {
        $comment = $this->di->get("comment");
        $user = $this->di->get("user");
        $tag = $this->di->get("tag");
        $request = $this->di->get("request");

        $sort = (null !== $request->getGet("sort")) ? $request->getGet("sort") : "created";
        $order = (null !== $request->getGet("order")) ? $request->getGet("order") : "ASC";

        $data = ["title" => "guestbook"];
        $post = $comment->getComment($id);

        if (empty($post)) {
            $this->di->get("view")->add("default1/article", ["content" => "This Question does not exist. It may have been deleted by the user"], "main");
        } else {
            $userInfo = $user->getUser($post->user);
            $replies = $comment->getReplies($id, $sort, $order);
            $comments = $comment->getReplyComments($id);
            $tags = $tag->getTagsForPost($id);
            $loggedInId = $user->getLoggedInUserId();

            $this->di->get("view")->add("components/comment", [
                "post" => $post,
                "replies" => $replies,
                "comments" => $comments,
                "tags" => $tags,
                "userInfo" => $userInfo,
                "loggedInUser" => $loggedInId
            ], "main");

            if ($user->isLoggedIn()) {
                $loggedInUser = $this->di->get("user")->getLoggedInUser();
                $form = new \CJ\Comment\HTMLForm\ReplyForm($this->di, $id, $loggedInUser);
                $form->check();
                $this->di->get("view")->add("components/form", ["form" => $form->getHTML(), "formTitle" => "Answer to post"], "main");
            } else {
                $this->di->get("view")->add("default1/article", ["content" => "You have to log in to answer"], "main");
            }
        }


        $this->di->get("pageRender")->renderPage($data);
    }


    /**
     * render page with forum tags
     */
    public function renderTagView()
    {
        $tag = $this->di->get("tag");
        $data = ["title" => "guestbook"];

        $tags = $tag->getAllTags();


        $this->di->get("view")->add("components/tags", ["tags" => $tags], "main");

        $this->di->get("pageRender")->renderPage($data);
    }



    /**
     * get Questions with tagName
     */
    public function tagContent($name)
    {
        $comment = $this->di->get("comment");
        $tag = $this->di->get("tag");
        $data = ["title" => "tag - $name"];

        $thisTag = $tag->find("tag", $name);


        $comments = $comment->getCommentsWithTagName($name);

        $this->di->get("view")->add("components/std-page-header", [
                                                            "header" => "Post tagged with $name",
                                                            "tag" => $thisTag
                                                        ], "main");
        $this->di->get("view")->add("components/list-comments", [
            "comments" => $comments,
            "name" => $name
        ], "main");

        $this->di->get("pageRender")->renderPage($data);
    }

    public function markAnswer($id)
    {
        $comment = $this->di->get("comment");
        $response = $this->di->get("response");

        $comment->toggleMarkAnswer($id);
        $response->redirect($_SERVER["HTTP_REFERER"]);
    }

    public function voteOnPost($id, $userId, $type)
    {
        $comment = $this->di->get("comment");
        $response = $this->di->get("response");

        $comment->vote($id, $userId, $type);
        $response->redirect($_SERVER["HTTP_REFERER"]);
    }
}
