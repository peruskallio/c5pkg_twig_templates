<?php

namespace Mainio\C5\Twig\Page\Controller;

use Page;
use \Concrete\Core\Page\Controller\DashboardPageController as CoreDashboardPageController;
use \Mainio\C5\Twig\Page\View\PageView;

class DashboardPageController extends CoreDashboardPageController
{

    protected $formFactory;

    // Remove the "form" helper in order to preserve the "form" name
    // for our internal use with twig.
    protected $helpers = array();

    public function __construct(Page $c) {
        parent::__construct($c);
        $this->view = new PageView($this->c);
    }

}
