<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;
use \CJ\User\User;

/**
 * Form to create an item.
 */
class CommentReplyForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $questionId, $replyId, $user)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],
                "user" => [
                    "type" => "hidden",
                    "value" => "$user->id"
                ],
                "replyId" => [
                    "type" => "hidden",
                    "value" => "$replyId"
                ],
                "questionId" => [
                    "type" => "hidden",
                    "value" => "$questionId"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Comment",
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "btn btn-default"
                ],
            ]
        );
    }



    /**
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $message = $this->form->value("message");
        $userId = $this->form->value("user");
        $replyId = $this->form->value("replyId");
        $questionId = $this->form->value("questionId");

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        $comment->content = $message;
        $comment->user = $userId;
        $comment->replyId = $replyId;
        $comment->questionId = $questionId;
        $comment->comment = 1;
        $comment->save();

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $userId);
        $user->karma += 1;
        $user->save();

        $this->form->addOutput("Din svar har blivit postat");
        return true;
    }
}
