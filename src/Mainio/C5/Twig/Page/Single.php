<?php
/**
 * This class is not particularly needed if the core overrides are used.
 * If not (e.g. in package context), it is better to use this one than
 * overriding the core classes.
 */
namespace Mainio\C5\Twig\Page;

use Core;
use \Concrete\Core\Page\Single as SinglePage;

class Single extends SinglePage
{

    public static function getPathToNode($node, $pkg)
    {
        $pr = Core::make('page/path_resolver', array($pkg));
        // We need to force the database saved view files to be with the '.php'
        // extension because otherwise the view files are not loaded properly.
        $pr->setForceExtension('php');
        return $pr->resolvePagePath($node);
    }

}
