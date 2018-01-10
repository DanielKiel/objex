<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 12:56
 */

namespace Objex\DBStorage\Console\Commands;


use GuzzleHttp\Client;
use Objex\Core\Stopwatch\Stopwatch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Required;

class EvaluateBasicAPIPerformance extends Command
{
    protected static $defaultName = 'dbstorage:perform';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('requests', InputArgument::OPTIONAL, 'how much requests have to performed'),
            ))
            ->setDescription('make some requests against the api')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        setSchema('MyNamespace',[
            'definition' => [
                'userName' => [
                    new Email(),
                    new Required()
                ],
                'firstName' => new Length(['min' => 3]),
                'lastName' => new Length(['min' => 3])
            ]
        ]);

        $token = objex()->get('DBStorage')
            ->getRepository('Objex\Security\Models\Machine')
            ->save('myMachine_' . uniqid())
            ->getApiKey();

        $client = new Client([
            'exceptions' => false
        ]);

        $baseUrl = getenv('APP_URL');

        $stopwatch = Stopwatch::getInstance()->getWatch();

        $arg = $input->getArgument('requests');
        $requests = (!is_null($arg) ? (int) $arg : 100) + 1;


        $stopwatch->start('perform');
        for ($index = 0; $index < $requests; $index++) {
            $client->request('POST',$baseUrl . 'api/my-namespace',[
                'json' => [
                    'userName' => 'dk.projects.manager@gmail.com',
                    'firstName' => 'Daniel',
                    'lastName' => 'Koch'
                ],
                'headers' => [
                    'X-Auth-Token' => $token
                ]
            ]);
        }
        $event = $stopwatch->stop('perform');

        $helper = new Table($output);
        $helper->setHeaders(['s', 'ms', 'memory']);
        $helper->addRow([round($event->getDuration() / 1000, 2),$event->getDuration(), $event->getMemory()]);

        deleteSchema('MyNamespace');

        $output->writeln('performed ' . ( (int) $requests - 1 ). ' requests');
        $helper->render();
    }
}