<?php

namespace Mainio\C5\Twig\Page\Controller;

use Page;
use \Concrete\Core\Page\Controller\PageController as CorePageController;
use \Mainio\C5\Twig\Page\View\PageView;

class PageController extends CorePageController
{

    protected $formFactory;

    public function __construct(Page $c) {
        parent::__construct($c);
        $this->view = new PageView($this->c);
    }

}
