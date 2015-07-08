<?php
namespace Mainio\C5\Twig;

use Core;
use Page;
use Twig_Extension;
use Twig_SimpleFunction;

class Concrete5Extension extends Twig_Extension
{

    protected $c5stuff;
    protected $uiHelper;

    public function __construct($c5stuff = null)
    {
        $this->uiHelper = Core::make('helper/concrete/ui');
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('url_to', array($this, 'urlTo')),
            new Twig_SimpleFunction('action', array($this, 'action')),
            new Twig_SimpleFunction('interface_button', array($this, 'interfaceButton'), array(
                'is_safe' => array('html')
            )),
            new Twig_SimpleFunction('interface_submit', array($this, 'interfaceSubmit'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function action()
    {
        $c = Page::getCurrentPage();
        $controller = $c->getPageController();
        return call_user_func_array(array($controller, 'action'), func_get_args());
    }

    public function urlTo()
    {
        return call_user_func_array(array('URL', 'to'), func_get_args());
    }

    public function interfaceButton()
    {
        return call_user_func_array(array($this->uiHelper, 'button'), func_get_args());
    }

    public function interfaceSubmit()
    {
        return call_user_func_array(array($this->uiHelper, 'submit'), func_get_args());
    }

    public function getName()
    {
        return 'concrete5';
    }
}