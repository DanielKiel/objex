<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 15:46
 */

namespace Objex\Core\Console\Commands\Debug;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Services extends Command
{
    protected static $defaultName = 'list:services';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Displays configured services for an application')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command displays all configured services:
  <info>php %command.full_name%</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('welcome to objex');

        $helper = new Table($output);
        $helper->setHeaders(['service', 'class']);

        foreach (objex()->getServiceIds() as $service) {
            $helper->addRow([$service, get_class(objex()->get($service))]);
        }

        $helper->render();
    }
}