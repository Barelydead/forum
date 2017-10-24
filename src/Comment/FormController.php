<?php

namespace CJ\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \CJ\Comment\HTMLForm\CreateCommentForm;
use \CJ\Comment\HTMLForm\EditCommentForm;

/**
 * A controller for the comment section
 */
class FormController implements InjectionAwareInterface
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

    public function deletePost($id)
    {
        $comment = $this->di->get("comment")->getComment($id);
        $res = $this->di->get("response");

        if (!$comment) {
            $res->redirect("forum/posts");
        }

        if ($comment->user !== $this->di->get("session")->get("user")) {
            $res->redirect($this->di->get("url")->create("login"));
        }

        $form = new \CJ\Comment\HTMLForm\DeletePost($this->di, $comment);
        $form->check();

        $data = ["form" => $form->getHTML(), "formTitle" => "Delete post"];


        $this->di->get("view")->add("components/form", $data, "main");
        $this->di->get("pageRender")->renderPage(["title" => "Delete Post"]);
    }
}
