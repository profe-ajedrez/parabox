<?php declare(strict_types=1);

namespace app\controllers;


use Exception;
use parabox\controllers\BaseController;
use parabox\services\connection\ConnectionPg as ConnectionPgAlias;
use parabox\services\dependencies\DependencyContainer;

class PianoController extends BaseController
{
    const VERSION = "0.1.0";


    /**
     *
     * @throws Exception
     */
    public function index()
    {

        $this->view->addFragment("first_fragment.html");
        $this->view->addFragment("meta.html");
        $this->view->addFragment("shared_css.html");
        $this->view->addFragment("closing_head.html");
        $this->view->addFragment("pianowarmonger/activity.html");
        $this->view->addFragment("shared_js.html");
        $this->view->addFragment("ending_tags.html");

        $strView = $this->view->render();
        $strView = $this->replaceCustomTags($strView);

        $this->serve($strView);
    }
}