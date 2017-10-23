<?php

namespace CJ\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \CJ\User\User;

/**
 * Form to create an item.
 */
class UpdatePasswordForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di, $id)
    {
        $this->id = $id;
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "old-password" => [
                    "type" => "password",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],
                "new-password" => [
                    "type" => "password",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],
                "new-repassword" => [
                    "type" => "password",
                    "validation" => ["match" => "new-password"],
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

    /**
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $password = $this->form->value("password");
        $newPassword = $this->form->value("new-password");

        $user = $this->di->get("user");
        $user->find("id", $this->id);

        if (password_verify($password, $user->password)) {
            $this->form->addOutput("gamla lösenordet stämmer inte");
            return false;
        }

        $user->setPassword($newPassword);
        $user->save();

        $this->form->addOutput("Uppdaterad");
        return true;
    }
}
