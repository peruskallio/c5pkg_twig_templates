<?php
namespace Mainio\C5\Twig\Command;

use Core;
use Package;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TwigClearCacheCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('twig:clear_cache')
            ->setDescription('Clears the twig cache for the specified entity')
            ->addArgument(
                'package',
                InputArgument::OPTIONAL,
                'Which package you want to extract the translations from?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pkg = Package::getByHandle($input->getArgument('package'));
        $serviceHandle = 'twig';
        if (is_object($pkg)) {
            $serviceHandle = $pkg->getPackageHandle() . '/' . $serviceHandle;
        }

        if (Core::bound($serviceHandle)) {
            $ts = Core::make($serviceHandle);
            if ($ts->clearCacheDirectory()) {
                $output->writeln("Twig cache cleared.");
            } else {
                throw new \Exception("Could not clear the twig cache directory. Please make sure the directory is writeable.");
            }
        } else {
            throw new \Exception("The twig service is not bound for the specified entity.");
        }
    }

}