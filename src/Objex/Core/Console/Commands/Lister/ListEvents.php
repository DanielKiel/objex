<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 15:46
 */

namespace Objex\Core\Console\Commands\Lister;



use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ListEvents extends Command
{
    protected static $defaultName = 'list:listener';
    protected $dispatcher;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->dispatcher = objex()->get('dispatcher');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('event', InputArgument::OPTIONAL, 'An event name'),
            ))
            ->setDescription('Displays configured listeners for an application')
            ->setHelp(<<<'EOF'
The <info>%command.name%</info> command displays all configured listeners:
  <info>php %command.full_name%</info>
To get specific listeners for an event, specify its name:
  <info>php %command.full_name% kernel.request</info>
EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('welcome to objex');

        $io = new SymfonyStyle($input, $output);
        $options = array();
        if ($event = $input->getArgument('event')) {
            if (!$this->dispatcher->hasListeners($event)) {
                $io->getErrorStyle()->warning(sprintf('The event "%s" does not have any registered listeners.', $event));
                return;
            }
            $options = array('event' => $event);
        }

        $helper = new Table($output);
        $helper->setHeaders(['event', 'listener', 'priority']);

        foreach ($this->dispatcher->getListeners() as $event => $listeners) {
            if (array_key_exists('event', $options)) {
                if ($event !== $options['event']) {
                    continue;
                }
            }


            foreach ($listeners as $listener) {
                $helper->addRow([
                    $event,
                    get_class($listener[0]),
                    $this->dispatcher->getListenerPriority($event, $listener)
                ]);
            }
        }

        $helper->render();




//        $helper = new DescriptorHelper();
//        $options['format'] = $input->getOption('format');
//        $options['raw_text'] = $input->getOption('raw');
//        $options['output'] = $io;
//        $helper->describe($io, $this->dispatcher, $options);
    }
}