<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 11.01.18
 * Time: 13:04
 */

namespace Objex\DBStorage\Console\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeleteSchema extends Command
{
    protected static $defaultName = 'dbstorage:del_schema';

    protected function configure()
    {
        $this->setDefinition([
            new InputArgument('namespace', InputArgument::REQUIRED, 'define namespace to delete')
        ]);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $namespace = $input->getArgument('namespace');

        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion('do you really want to delete ' . $namespace . '?', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $result = deleteSchema($namespace);

        if ((bool) $result === true) {
            $output->writeln('deleted: ' . $namespace);
        }
    }
}