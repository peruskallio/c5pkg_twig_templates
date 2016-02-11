<?php
namespace Mainio\C5\Twig\Core\Override\Page;

use Core;
use Mainio\C5\Twig\Core\Original\Page\Single as CoreSinglePage;

class Single extends CoreSinglePage
{

    public static function sanitizePath($path)
    {
        $pr = Core::make('page/path_resolver');
        return $pr->sanitizePath($path);
    }

    public static function getPathToNode($node, $pkg)
    {
        $pr = Core::make('page/path_resolver', array($pkg));
        return $pr->resolvePagePath($node);
    }

}
