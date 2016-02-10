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
        return $pr->resolvePagePath($node);
    }

}
