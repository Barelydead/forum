<?php

namespace Anax\Page;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * A default page rendering class.
 */
class DebugController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * Render a page displaying information.
     *
     * @return void
     */
    public function info()
    {
        $this->di->get("view")->add("default2/info");
        $this->di->get("pageRender")->renderPage([
            "title" => "Info",
        ]);
    }
}
