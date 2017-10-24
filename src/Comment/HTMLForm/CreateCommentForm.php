<?php

namespace CJ\Comment\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\Comment\Comment;
use \CJ\Comment\Tag;
use \CJ\User\User;

/**
 * Form to create an item.
 */
class CreateCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $user)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],

                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],
                "tags" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "class" => "form-control",
                    "placeholder" => "space separated tags eg. a_tag anothertag"
                ],
                "user" => [
                    "type" => "hidden",
                    "value" => "$user->id"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Create post",
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
        $message = $this->form->value("message");
        $userId = $this->form->value("user");
        $tags = $this->form->value("tags");

        $comment = new Comment();
        $comment->setDb($this->di->get("db"));

        $comment->content = $message;
        $comment->question = true;
        $comment->created = date("Y-m-d H:i:s");
        $comment->user = $userId;
        $comment->title = $title;
        $comment->likes = 0;
        $comment->save();

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $userId);
        $user->karma += 1;
        $user->save();

        $tags = explode(" ", $tags);
        $tags = array_slice($tags, 0, 3);

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

            $db->execute("INSERT INTO f_post2tag(post,tag) VALUES (?,?)", [$comment->id, $tagId]);
        }


        $this->form->addOutput("Din inlägg har blivit postat");

        return true;
    }
}
