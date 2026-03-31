<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;

class ContentController extends FrontendController
{

public function portalAction(){
    return $this->render("content/portal.html.twig");
}

}