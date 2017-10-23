<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;
use \CJ\Comment\Tag;

/**
 * Form to create an item.
 */
class EditTags extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $id, $tags)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "tags" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "class" => "form-control",
                    "value" => $tags
                ],
                "postId" => [
                    "type" => "hidden",
                    "value" => "$id"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Edit tags",
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
        $id = $this->form->value("postId");
        $tags = $this->form->value("tags");
        $db = $this->di->get("db");
        $db->execute("DELETE FROM f_post2tag WHERE post = ?", [$id]);

        $tags = explode(" ", $tags);
        foreach ($tags as $singleTag) {
            $tag = new Tag();
            $tag->setDb($this->di->get("db"));
            $singleTag = strToLower($singleTag);

            if ($tag->find("tag", $singleTag)) {
                // do nothing
            } else {
                $tag->tag = $singleTag;
                $tag->save();
            }
        }

        $tag = new Tag();
        $tag->setDb($this->di->get("db"));
        $db = $this->di->get("db");
        foreach ($tags as $singleTag) {
            $tagId = $tag->getIdFromTagName($singleTag);

            $db->execute("INSERT INTO f_post2tag(post,tag) VALUES (?,?)", [$id, $tagId]);
        }


        $this->form->addOutput("Din inlägg har blivit postat");

        return true;
    }
}
