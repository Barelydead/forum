<?php

namespace CJ\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\User\User;

/**
 * Form to create an item.
 */
class UpdateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $id)
    {
        parent::__construct($di);
        $this->id = $id;

        $user = $this->getUserDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "mail" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $user->mail,
                    "class" => "form-control"
                ],
                "username" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $user->username,
                    "class" => "form-control"
                ],
                "name" => [
                    "type" => "text",
                    "value" => $user->name,
                    "class" => "form-control"
                ],
                "age" => [
                    "type" => "text",
                    "value" => $user->age,
                    "class" => "form-control"
                ],
                "description" => [
                    "type" => "textarea",
                    "value" => $user->description,
                    "class" => "form-control"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Update",
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "btn btn-default"
                ],
            ]
        );
    }

    public function getUserDetails($id)
    {
        $user = $this->di->get("user");
        $user->find("id", $id);

        return $user;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $mail = $this->form->value("mail");
        $username = $this->form->value("username");
        $name = $this->form->value("name");
        $age = $this->form->value("age");
        $description = $this->form->value("description");

        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->find("id", $this->id);
        $user->updated = date("H:i:m H-m-s");
        $user->mail = $mail;
        $user->username = $username;
        $user->name = $name;
        $user->age = $age;
        $user->description = $description;
        $user->save();

        $this->form->addOutput("Uppdaterad");
        return true;
    }
}
