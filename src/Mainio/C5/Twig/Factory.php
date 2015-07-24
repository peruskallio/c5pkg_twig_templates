<?php
namespace Mainio\C5\Twig;

use Config;
use Package;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class Factory
{

    public static function createTranslator($locale = null)
    {
        if ($locale === null) {
            $locale = \Localization::activeLocale();
        }
        $translator = new \Symfony\Component\Translation\Translator($locale);
        $translator->addLoader('mo', new \Symfony\Component\Translation\Loader\MoFileLoader());

        // Only when the Translate object is set for the Localization object,
        // we have a custom locale set that requires translations.
        $tra = \Localization::getTranslate();
        if (is_object($tra)) {
            // This introduces more overhead to the translations loading but it
            // is not possible to get the list of translations from the Zend's
            // Translator object.
            $systemTranslation = DIR_LANGUAGES . '/' . $locale . '/LC_MESSAGES/messages.mo';
            if (file_exists($systemTranslation)) {
                $translator->addResource('mo', $systemTranslation, $locale);
            }

            foreach (Package::getList() as $pkg) {
                $packageTranslation = $pkg->getPackagePath() . '/' . DIRNAME_LANGUAGES . '/' . $locale . '/LC_MESSAGES/messages.mo';
                if (file_exists($packageTranslation)) {
                    $translator->addResource('mo', $packageTranslation, $locale);
                }
            }

            $appTranslation = DIR_LANGUAGES_SITE_INTERFACE . '/' . $locale . '.mo';
            if (file_exists($appTranslation)) {
                $translator->addResource('mo', $appTranslation, $locale);
            }
        }

        return $translator;
    }

    /**
     * We need a context specific twig environment object because
     * a) The twig libraries or the Symfony twig bridge are not
     *    available in the core so we cannot rely on their availability
     *    any other way
     * b) We'll need to have a single twig environment per twig cache
     *    directory.
     */
    public static function createEnvironment($paths, \Symfony\Component\Translation\Translator $translator, $options = array())
    {
        $viewPath = $paths['base'] . '/' . DIRNAME_VIEWS;
        $twigBridgePath = $paths['lib'] . '/symfony/twig-bridge/Symfony/Bridge/Twig';

        $opts = array();
        if (Config::get('app.package_dev_mode')) {
            $opts['debug'] = true;
        } else {
            $opts['cache'] = $viewPath . '/!twig_cache';
        }
        $opts = array_merge_recursive($opts, $options);
        $twig = new Twig_Environment(new Twig_Loader_Filesystem(
            array(
                $twigBridgePath . '/Resources/views/Form',
            )
        ), $opts);

        if (is_object($translator)) {
            $twig->addExtension(
                new \Symfony\Bridge\Twig\Extension\TranslationExtension(
                    $translator
                )
            );
        }

        $twig->addExtension(
            new Concrete5Extension()
        );

        if (Config::get('app.package_dev_mode')) {
            $twig->addExtension(new Twig_Extension_Debug());
        }

        return $twig;
    }

}