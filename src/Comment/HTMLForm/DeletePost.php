<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;

/**
 * Form to create an item.
 */
class DeletePost extends FormModel
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
                "title" => [
                    "type" => "text",
                    "class" => "form-control",
                    "readonly" => true,
                    "value" => "$comment->title"
                ],
                "content" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "id" => "text-box",
                    "readonly" => true,
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
                    "value" => "delete",
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
        $id = $this->form->value("id");
        $comment = $this->di->get("comment");

        $comment->find("id", $id);
        $comment->delete();

        $this->form->addOutput("Kommentar borttagen");
        return true;
    }
}
