<?php
namespace Mainio\C5\Twig\Page;

interface PathResolverInterface
{

    public function addFileExtension($extension);
    public function removeFileExtension($extension);
    public function sanitizePath($path);
    public function getViewFilename($extension);
    public function getFileExtensionFor($path);
    public function getFileBasenameFor($path);
    public function resolvePagePath($node, $pkg);

}
