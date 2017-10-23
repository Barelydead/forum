<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;
use \CJ\Comment\Tag;

/**
 * Form to create an item.
 */
class TagDescription extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $tag)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "tag" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "class" => "form-control",
                    "value" => $tag->tag,
                    "readonly" => true
                ],
                "description" => [
                    "type" => "text",
                    "value" => "$tag->description",
                    "validation" => ["not_empty"],
                    "class" => "form-control",
                ],
                "id" => [
                    "type" => "hidden",
                    "value" => "$tag->id"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Edit tag",
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
        $description = $this->form->value("description");

        $tag = $this->di->get("tag");
        $tag->find("id", $id);
        $tag->description = $description;
        $tag->save();

        $this->form->addOutput("Tag successfully updated");

        return true;
    }
}
