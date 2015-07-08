<?php
namespace Mainio\C5\Twig\Controller;

use Controller as CoreController;
use \Mainio\C5\Twig\View\View;

class Controller extends CoreController
{

    public function __construct() {
        parent::__construct();

        if ($this->viewPath) {
            $this->view = new View($this->viewPath);
            if (preg_match('/Concrete\\\Package\\\(.*)\\\Controller/i', get_class($this), $matches)) {
                $pkgHandle = uncamelcase($matches[1]);
                $this->view->setPackageHandle($pkgHandle);
            }
            $this->view->setController($this);
        }
    }

}
