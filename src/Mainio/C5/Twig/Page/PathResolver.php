<?php
namespace Mainio\C5\Twig\Page;

use Core;
use Concrete\Core\Page\Single as SinglePage;
use Package;

class PathResolver implements PathResolverInterface
{

    protected $package;
    protected $fileExtensions = array();
    protected $forceExtension;

    public function __construct(Package $pkg = null)
    {
        $this->package = $pkg;
    }

    public function addFileExtension($extension)
    {
        $this->fileExtensions[] = $extension;
    }

    public function removeFileExtension($extension)
    {
        if (($ind = array_search($extension, $this->fileExtensions)) !== false) {
            unset($this->fileExtensions[$ind]);
        }
    }

    public function setForceExtension($ext)
    {
        $this->forceExtension = $ext;
    }

    public function sanitizePath($path)
    {
        //takes a damn cpath and returns no first slash, and no more than 1 intermediate slash in
        // the middle at any point
        $node = preg_replace("/([\/]+)/", "/", $path);
        if (substr($node, 0, 1) == "/") {
            $node = substr($node, 1);
        }
        // now do the same for the last node
        if (substr($node, strlen($node) - 1, 1) == '/') {
            $node = substr($node, 0, strlen($node) -1);
        }
        return $node;
    }

    public function getViewFilename($extension = null)
    {
        if ($extension === null) {
            $extension = array_shift(array_values($this->fileExtensions));
        }
        return Core::make('helper/file')->replaceExtension(FILENAME_COLLECTION_VIEW, $extension);
    }

    public function getFileExtensionFor($path)
    {
        foreach ($this->fileExtensions as $ext) {
            $test = '.' . $ext;
            $pos = strrpos($path, $test);
            if ($pos === strlen($path) - strlen($test)) {
                return $ext;
            }
        }
        return Core::make('helper/file')->getExtension($path);
    }

    public function getFileBasenameFor($path)
    {
        $ext = $this->getFileExtensionFor($path);
        if (strlen($ext) > 0) {
            return basename($path, '.' . $ext);
        }
        return basename($path);
    }

    public function resolvePagePath($node)
    {
        foreach ($this->fileExtensions as $extension) {
            $path = $this->resolvePagePathWithExtension($node, $extension);
            if ($path) {
                return $path;
            }
        }
        return false;
    }

    public function resolvePagePathWithExtension($node, $extension)
    {
        $node = $this->sanitizePath($node);
        // checks to see whether a passed $node is a static content node
        // (static content nodes exist within the views directory)

        // first, we look to see if the exact path exists (plus .php)
        $pathToFile = null;
        $fh = Core::make('helper/file');
        $pkg = $this->package;
        if (is_object($pkg)) {
            if (is_dir(DIR_PACKAGES . '/' . $pkg->getPackageHandle())) {
                $dirp = DIR_PACKAGES . '/' . $pkg->getPackageHandle();
            } else {
                $dirp = DIR_PACKAGES_CORE . '/' . $pkg->getPackageHandle();
            }

            $file1 = $dirp . '/' . DIRNAME_PAGES . '/' . $node . '/' . $this->getViewFilename($extension);
            $file2 = $dirp . '/' . DIRNAME_PAGES . '/' . $node . '.' . $extension;
        } else {
            $file1 = DIR_FILES_CONTENT . '/' . $node . '/' . $this->getViewFilename($extension);
            $file2 = DIR_FILES_CONTENT . '/' . $node . '.' . $extension;
            $file3 = DIR_FILES_CONTENT_REQUIRED . '/' . $node . '/' . $this->getViewFilename($extension);
            $file4 = DIR_FILES_CONTENT_REQUIRED . '/' . $node . '.' . $extension;
        }

        if (isset($this->forceExtension) && $this->forceExtension !== null) {
            $extension = $this->forceExtension;
        }
        if (file_exists($file1)) {
            $pathToFile = "/{$node}/" . $this->getViewFilename($extension);
        } elseif (file_exists($file2)) {
            $pathToFile = "/{$node}." . $extension;
        } elseif (isset($file3) && file_exists($file3)) {
            $pathToFile = "/{$node}/" . $this->getViewFilename($extension);
        } elseif (isset($file4) && file_exists($file4)) {
            $pathToFile = "/{$node}." . $extension;
        } else {
            $pathToFile = false;
        }

        return $pathToFile;
    }

    public function resolvePageTypeControllerPath($node)
    {
        throw new \Exception(t("Implementation missing!"));
    }


}
