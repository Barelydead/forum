<?php

namespace CJ\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \CJ\Comment\HTMLForm\CreateCommentForm;
use \CJ\Comment\HTMLForm\EditCommentForm;

/**
 * A controller for the comment section
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;

    /**
     * process incomming POST
     */
    public function newComment()
    {
        $user = $this->di->get("user");

        if (!$user->isLoggedIn()) {
            $this->di->get("response")->redirect($this->di->get("url")->create("user/login"));
        }

        $form = new CreateCommentForm($this->di, $user->getLoggedInUser());
        $form->check();

        $data = ["form" => $form->getHTML(), "formTitle" => "Add new post"];

        $this->di->get("view")->add("components/form", $data);
        $this->di->get("pageRender")->renderPage(["title" => "CommentForm"]);
    }

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
     * remove one comment
     */
    public function removeComment($index)
    {
        $user = $this->di->get("user");
        $comment = $this->di->get("comment");
        $comment->getComment($index);

        if ($comment->userId !== $this->di->get("session")->get("user")) {
            if ($user->isUserAdmin()) {
                // Do nothing
            } else {
                $this->di->get("response")->redirect($this->di->get("url")->create("comment"));
            }
        }

        $comment->deleteComment($index);
        $this->di->get("response")->redirect($this->di->get("url")->create("comment"));
    }

    /**
     * load edit page
     */
    public function editPost($id)
    {
        $comment = $this->di->get("comment")->getComment($id);


        if ($comment->user !== $this->di->get("session")->get("user")) {
            $this->di->get("response")->redirect($this->di->get("url")->create("login"));
        }


        $form = new EditCommentForm($this->di, $comment);
        $form->check();

        $data = ["form" => $form->getHTML(), "formTitle" => "Edit post"];


        $this->di->get("view")->add("components/form", $data, "main");
        $this->di->get("pageRender")->renderPage(["title" => "guestbook - edit"]);
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
     * render reply to comment
     */
    public function commentReply($qId, $rId)
    {
        $user = $this->di->get("user");

        $form = new \CJ\Comment\HTMLForm\CommentReplyForm($this->di, $qId, $rId, $user->getLoggedInUser());
        $form->check();
        $data = [
            "form" => $form->getHTML(),
            "formTitle" => "Comment on answer",
            "title" => "Comment on a Reply"
        ];

        $this->di->get("view")->add("components/form", $data);
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


    public function editTags($id)
    {
        $tag = $this->di->get("tag");

        $tags = $tag->getTagsForPost($id);
        $tags = $tag->getTagString($tags);

        $data = ["title" => "edit tags"];

        $form = new \CJ\Comment\HTMLForm\EditTags($this->di, $id, $tags);
        $form->check();


        $this->di->get("view")->add("components/form", ["form" => $form->getHTML(), "formTitle" => "Edit tags"], "main");

        $this->di->get("pageRender")->renderPage($data);
    }

    public function editTagDescription($id)
    {
        $tag = $this->di->get("tag");
        $updateTag = $tag->find("id", $id);

        $user = $this->di->get("user");

        if (!$user->isLoggedIn()) {
            $this->di->get("response")->redirect($this->di->get("url")->create("user/login"));
        }

        $data = ["title" => "edit tag description"];

        $form = new \CJ\Comment\HTMLForm\TagDescription($this->di, $updateTag);
        $form->check();

        $this->di->get("view")->add("components/form", ["form" => $form->getHTML(), "formTitle" => "Add tag description"], "main");
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

    public function deletePost($id)
    {
        $comment = $this->di->get("comment")->getComment($id);
        $req = $this->di->get("request");
        $res = $this->di->get("response");


        if ($comment->user !== $this->di->get("session")->get("user")) {
            if (!isset($comment->user)) {
                $res->redirect("forum/posts");
            } else {
                $res->redirect($this->di->get("url")->create("login"));
            }
        }

        $form = new \CJ\Comment\HTMLForm\DeletePost($this->di, $comment);
        $form->check();

        $data = ["form" => $form->getHTML(), "formTitle" => "Delete post"];


        $this->di->get("view")->add("components/form", $data, "main");
        $this->di->get("pageRender")->renderPage(["title" => "Delete Post"]);
    }
}
