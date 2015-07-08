<?php

namespace Mainio\C5\Twig\Page;

use Core;
use \Concrete\Core\Page\Single as SinglePage;

class Single extends SinglePage
{

    /**
     * When using the twig templates, we need to fake the actual view file
     * names to the ".php" extension because otherwise the internal controller
     * loading functionality would not work properly as it is using the same
     * file name as is being stored in the database. Therefore, this function
     * needs to return a file with a ".php" extension to avoid breaking the
     * controller loading.
     * 
     * In the custom view object, we can modify the view name to search for the
     * correct twig template file with the ".html.twig" extension. 
     */
    public static function getPathToNode($node, $pkg) {
        $node = static::sanitizePath($node);
        // checks to see whether a passed $node is a static content node
        // (static content nodes exist within the views directory)

        // first, we look to see if the exact path exists (plus .php)
        $pathToFile = null;
        $fh = Core::make('helper/file');
        if (is_object($pkg)) {
            if (is_dir(DIR_PACKAGES . '/' . $pkg->getPackageHandle())) {
                $dirp = DIR_PACKAGES . '/' . $pkg->getPackageHandle();
            } else {
                $dirp = DIR_PACKAGES_CORE . '/' . $pkg->getPackageHandle();
            }

            $file1 = $dirp . '/' . DIRNAME_PAGES . '/' . $node . '/' . FILENAME_COLLECTION_VIEW;
            $file2 = $dirp . '/' . DIRNAME_PAGES . '/' . $node . '.php';

            $file1 = $fh->replaceExtension($file1, 'html.twig');
            $file2 = $fh->replaceExtension($file2, 'html.twig');
        } else {
            $file1 = DIR_FILES_CONTENT . '/' . $node . '/' . FILENAME_COLLECTION_VIEW;
            $file2 = DIR_FILES_CONTENT . '/' . $node . '.php';
            $file3 = DIR_FILES_CONTENT_REQUIRED . '/' . $node . '/' . FILENAME_COLLECTION_VIEW;
            $file4 = DIR_FILES_CONTENT_REQUIRED . '/' . $node . '.php';

            $file1 = $fh->replaceExtension($file1, 'html.twig');
            $file2 = $fh->replaceExtension($file2, 'html.twig');
            $file3 = $fh->replaceExtension($file3, 'html.twig');
            $file4 = $fh->replaceExtension($file4, 'html.twig');
        }

        if (file_exists($file1)) {
            $pathToFile = "/{$node}/" . FILENAME_COLLECTION_VIEW;
        } else if (file_exists($file2)) {
            $pathToFile = "/{$node}.php";
        } else if (isset($file3) && file_exists($file3)) {
            $pathToFile = "/{$node}/" . FILENAME_COLLECTION_VIEW;
        } else if (isset($file4) && file_exists($file4)) {
            $pathToFile = "/{$node}.php";
        }

        if (!$pathToFile) {
            $pathToFile = false;
        }

        return $pathToFile;

    }

}
