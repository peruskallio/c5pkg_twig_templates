<?php
namespace Mainio\C5\Twig\Core\Override\Page;

use Core;
use Environment;
use Mainio\C5\Twig\Core\Original\Page\Page as CorePage;
use Package;

class Page extends CorePage
{

    /**
     * @return PageController
     */
    public function getPageController()
    {
        if (!isset($this->controller)) {
            $env = Environment::get();

            $pkg = null;
            if ($this->getPackageID() > 0) {
                $pkg = Package::getByID($this->getPackageID());
            }
            $pr = Core::make('page/path_resolver', array($pkg));

            if ($this->getPageTypeID() > 0) {
                $pt = $this->getPageTypeObject();
                $ptHandle = $pt->getPageTypeHandle();
                $r = $env->getRecord(DIRNAME_CONTROLLERS.'/'.DIRNAME_PAGE_TYPES.'/'.$ptHandle.'.php', $pt->getPackageHandle());
                $prefix = $r->override ? true : $pt->getPackageHandle();
                $class = core_class('Controller\\PageType\\'.camelcase($ptHandle), $prefix);
            } elseif ($this->isGeneratedCollection()) {
                $file = $this->getCollectionFilename();

                $ext = $pr->getFileExtensionFor($file);
                $collectionViewFilename = $pr->getViewFilename($ext);

                if (strpos($file, '/' . $collectionViewFilename) !== false) {
                    $path = substr($file, 0, strpos($file, '/' . $collectionViewFilename));
                } else {
                    $path = substr($file, 0, strpos($file, '.' . $ext));
                }
                $r = $env->getRecord(DIRNAME_CONTROLLERS.'/'.DIRNAME_PAGE_CONTROLLERS.$path.'.php', $this->getPackageHandle());
                $prefix = $r->override ? true : $this->getPackageHandle();
                $class = core_class('Controller\\SinglePage\\'.str_replace('/', '\\', camelcase($path, true)), $prefix);
            }

            if (isset($class) && class_exists($class)) {
                $this->controller = Core::make($class, array($this));
            } else {
                $this->controller = Core::make('\PageController', array($this));
            }
        }

        return $this->controller;
    }

}
