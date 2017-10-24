<?php
namespace CJ\User;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * A controller class to rule all User related stuff
 */
class UserController implements InjectionAwareInterface
{
    use InjectionAwareTrait;


    /*
     * Check if user is logged in. redirect to login if not
    */
    public function checkUser()
    {
        $response = $this->di->get("response");
        $user = $this->di->get("user");

        if (!$user->isLoggedIn()) {
            $response->redirect("login");
        }
    }


    public function login()
    {
        $title      = "Login user";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form = new \CJ\User\HTMLForm\LoginUserForm($this->di);

        $form->check();

        $view->add("components/form", ["form" => $form->getHTML(), "formTitle" => "Login"], "main");

        $pageRender->renderPage(["title" => $title]);
    }

    public function create()
    {
        $title      = "Create user profile";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $form = new \CJ\User\HTMLForm\CreateUserForm($this->di);

        $form->check();

        $view->add("components/form", ["form" => $form->getHTML(), "formTitle" => "Create user"], "main");

        $pageRender->renderPage(["title" => $title]);
    }

    public function logout()
    {
        $response = $this->di->get("response");
        $user = $this->di->get("user");

        $user->logOutUser();

        $response->redirect("user");
    }

    public function userProfile($name)
    {
        $title      = "{$name}s Profile";
        $view       = $this->di->get("view");
        $comment    = $this->di->get("comment");
        $pageRender = $this->di->get("pageRender");
        $user       = $this->di->get("user");

        $userInfo = $user->getUserFromName($name);
        $votes = $user->getVotes($userInfo->id);
        $posts = $comment->getAllPostsForUser($userInfo->id);

        $view->add("components/profile", [
                                    "user" => $userInfo,
                                    "posts" => $posts,
                                    "votes" => $votes
                                ], "main");

        $pageRender->renderPage(["title" => $title]);
    }

    public function profile()
    {
        $title      = "Your profile";
        $view       = $this->di->get("view");
        $comment    = $this->di->get("comment");
        $pageRender = $this->di->get("pageRender");
        $user       = $this->di->get("user");
        $res        = $this->di->get("response");

        $this->checkUser();

        $userInfo = $user->getLoggedInUser();
        $votes = $user->getVotes($userInfo->id);
        $posts = $comment->getAllPostsForUser($user->id);

        $view->add("components/admin-nav", [], "main");

        $view->add("components/profile", [
                                    "user" => $userInfo,
                                    "posts" => $posts,
                                    "votes" => $votes
                                ], "main");

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * UserOverview
     */
    public function userOverview()
    {
        $title      = "Users overview";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $user       = $this->di->get("user");

        $users = $user->getAllUsers();

        $view->add("components/user-overview", ["users" => $users], "main");

        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Edit profile page
     */
    public function editProfile()
    {
        $title      = "edit profile";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $session    = $this->di->get("session");
        $form       = new \CJ\User\HTMLForm\UpdateUserForm($this->di, $session->get("user"));

        $form->check();

        $data = ["form" => $form->getHTML(), "formTitle" => "Update user"];

        $view->add("components/admin-nav", [], "main");
        $view->add("components/form", $data, "main");
        $pageRender->renderPage(["title" => $title]);
    }

    /**
     * Update password
     */
    public function editPassword()
    {
        $title      = "edit password";
        $view       = $this->di->get("view");
        $pageRender = $this->di->get("pageRender");
        $session    = $this->di->get("session");
        $form       = new \CJ\User\HTMLForm\UpdatePasswordForm($this->di, $session->get("user"));

        $form->check();

        $data = ["form" => $form->getHTML(), "formTitle" => "Edit password"];

        $view->add("components/admin-nav", [], "main");
        $view->add("components/form", $data, "main");
        $pageRender->renderPage(["title" => $title]);
    }
}
