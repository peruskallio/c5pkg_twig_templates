<?php

namespace Mainio\C5\Twig\Page\View;

use Core;
use \Concrete\Core\Page\View\PageView as CorePageView;

/**
 * Most of this could be done within a trait if concrete5 required PHP 5.4.
 * For now, we need to repeat the same stuff in all the classes that require
 * the provided functionality.
 */
class PageView extends CorePageView
{

    protected $useTwig = true;
    protected $useTwigTemplate = false;

    protected $format = 'html';

    public function setUseTwig($useTwig)
    {
        $this->useTwig = (bool)$useTwig;
    }

    public function setUseTwigTemplate($useTwigTemplate)
    {
        $this->useTwigTemplate = (bool)$useTwigTemplate;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function renderViewContents($scopeItems) {
        extract($scopeItems);
        if ($this->innerContentFile) {
            // If the extension is html.twig, generete the inner $innerContent
            // using twig.
            if ($this->useTwig) {
                if (!isset($scopeItems['action'])) {
                    $scopeItems['action'] = $this->controller->getAction();
                }
                $innerContent = $this->renderTwigTemplate($this->innerContentFile, $scopeItems);
                $scopeItems['innerContent'] = $innerContent;
            } else {
                ob_start();
                include($this->innerContentFile);
                $innerContent = ob_get_contents();
                ob_end_clean();
            }
        }

        if (file_exists($this->template)) {
            ob_start();
            $this->onBeforeGetContents();
            if ($this->useTwigTemplate) {
                // This is just a hack not to disturb the default loading
                // process. Normally we wouldn't want to echo the content
                // out like this.
                echo $this->renderTwigTemplate($this->template, $scopeItems);
            } else {
                include($this->template);
            }
            $contents = ob_get_contents();
            $this->onAfterGetContents();
            ob_end_clean();
            return $contents;
        } else {
            return $innerContent;
        }
    }

    protected function renderTwigTemplate($file, array $scopeItems = array())
    {
        $fh = Core::make('helper/file');
        $dir = dirname($file);
        $file = substr($file, strlen($dir)+1);
        $ext = 'twig';
        if (strlen($this->format)) {
            $ext = $this->format . '.' . $ext;
        }
        $file = $fh->replaceExtension($file, $ext);

        $prefix = '';
        if (strlen($this->c->getPackageID())) {
            $prefix = $this->c->getPackageHandle() . '/';
        }
        $twig = Core::make($prefix . 'environment/twig');
        $loader = $twig->getLoader();
        $paths = $loader->getPaths();
        $loader->addPath($dir);

        $innerContent = $twig->render($file, $scopeItems);
        $loader->setPaths($paths);

        return $innerContent;
    }

}
