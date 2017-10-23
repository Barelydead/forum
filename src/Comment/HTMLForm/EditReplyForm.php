<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;

/**
 * Form to create an item.
 */
class EditCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $comment)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "id" => "text-box",
                    "class" => "form-control",
                    "value" => "$comment->content"
                ],
                "id" => [
                    "type" => "hidden",
                    "class" => "form-control",
                    "value" => "$comment->id"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Edit",
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
        $title = $this->form->value("title");
        $content = $this->form->value("content");
        $id = $this->form->value("id");

        $comment = $this->di->get("comment");

        $comment->find("id", $id);
        $comment->content = $content;
        $comment->updated = date("H:i:m H-m-s");
        $comment->title = $title;

        $comment->save();

        $this->form->addOutput("Kommentar uppdaterad");
        return true;
    }
}
