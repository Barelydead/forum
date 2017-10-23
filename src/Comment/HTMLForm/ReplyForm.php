<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;
use \CJ\User\User;

/**
 * Form to create an item.
 */
class ReplyForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $id, $user)
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
                "questionId" => [
                    "type" => "hidden",
                    "value" => "$id"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Answer",
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
        $questionId = $this->form->value("questionId");

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        $comment->content = $message;
        $comment->created = date("Y-m-d H:i:s");
        $comment->user = $userId;
        $comment->questionId = $questionId;
        $comment->reply = 1;
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
