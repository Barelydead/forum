<?php

namespace CJ\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;

/**
 * Form to create an item.
 */
class LoginUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "use_fieldset" => false
            ],
            [
                "mail" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],
                "password" => [
                    "type" => "password",
                    "validation" => ["not_empty"],
                    "class" => "form-control"
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
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
        $mail       = $this->form->value("mail");
        $password   = $this->form->value("password");

        $user = $this->di->get("user");

        $res = $user->verifyPassword($mail, $password);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("Mailadress or password did not match.");
            return false;
        }

        $user->find("mail", $mail);

        $this->di->get("session")->set("user", $user->id);
        $this->form->addOutput("User logged in.");
        return true;
    }
}
