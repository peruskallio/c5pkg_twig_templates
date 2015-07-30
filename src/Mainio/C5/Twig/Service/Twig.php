<?php
namespace Mainio\C5\Twig\Service;

use Config;
use Core;

class Twig
{

    protected $pkg;

    public function __construct($pkg = null)
    {
        $this->pkg = $pkg;
    }

    public function getCacheDirectory()
    {
        $cacheDir = Config::get('concrete.cache.directory') . '/twig_cache';
        if (is_object($this->pkg)) {
            $cacheDir .= '/' . $this->pkg->getPackageHandle();
        } else {
            $cacheDir .= '/application';
        }
        return $cacheDir;
    }

    public function clearCacheDirectory()
    {
        return Core::make('helper/file')->removeAll($this->getCacheDirectory());
    }

}
