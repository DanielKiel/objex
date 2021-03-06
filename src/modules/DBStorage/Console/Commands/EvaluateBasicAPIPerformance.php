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
        setSchema('Debug\MyNamespace',[
            'definition' => [
                'userName' => [
                    'type' => 'text',
                    'validation' => 'strlen(userName) > 3'
                ],
                'firstName' =>[
                    'type' => 'text',
                    'validation' =>  'strlen(firstName) > 3'
                ],
                'lastName' => [
                    'type' => 'text',
                    'validation' =>  'strlen(lastName) > 3'
                ]
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

        if ((int) $requests <= 101) {
            $stopwatch->start('perform_post');
            for ($index = 0; $index < $requests; $index++) {
                $request = $client->request('POST',$baseUrl . 'api/debug_my-namespace',[
                    'json' => [
                        'userName' => 'dk.projects.manager@gmail.com',
                        'firstName' => 'Daniel',
                        'lastName' => 'Koch'
                    ],
                    'headers' => [
                        'X-Auth-Token' => $token
                    ]
                ]);

                if ($request->getStatusCode() !== 200) {
                    $output->writeln('wrong request');
                }
            }
            $event = $stopwatch->stop('perform_post');

            $helper = new Table($output);
            $helper->setHeaders(['obj per sec',  's', 'ms', 'memory']);

            $sec = round($event->getDuration() / 1000, 2);
            $o_per_m = round((int) $requests / $sec, 2);

            $helper->addRow([$o_per_m, $sec,$event->getDuration(), $event->getMemory()]);

            //deleteSchema('Debug\MyNamespace');

            $output->writeln('performed ' . ( (int) $requests - 1 ). ' post requests');
            $helper->render();
        }
        else {
            $output->writeln('avoid performing single post requests to store such a lot of objects');
        }

        $bulk = [];
        $stopwatch->start('perform_bulk');
        for ($index = 0; $index < $requests; $index++) {
            array_push($bulk, [
                'userName' => 'dk.projects.manager@gmail.com',
                'firstName' => 'Daniel',
                'lastName' => 'Koch'
            ]);
        }

        $request = $client->request('POST',$baseUrl . 'api/debug_my-namespace/bulk',[
            'json' => $bulk,
            'headers' => [
                'X-Auth-Token' => $token
            ]
        ]);

        if ($request->getStatusCode() !== 200) {
            $output->writeln('wrong request');
        }

        $event = $stopwatch->stop('perform_bulk');

        $helper = new Table($output);
        $helper->setHeaders(['obj per sec',  's', 'ms', 'memory']);

        $sec = round($event->getDuration() / 1000, 2);
        $o_per_m = round((int) $requests / $sec, 2);

        $helper->addRow([$o_per_m, $sec,$event->getDuration(), $event->getMemory()]);

        //deleteSchema('Debug\MyNamespace');

        $output->writeln('performed a bulk request');
        $helper->render();



        $stopwatch->start('perform_save');
        for ($index = 0; $index < $requests; $index++) {
            $object = saveObject('debug_my-namespace',[
                'userName' => 'dk.projects.manager@gmail.com',
                'firstName' => 'Daniel',
                'lastName' => 'Koch'
            ]);
        }
        $event = $stopwatch->stop('perform_save');

        $helper = new Table($output);
        $helper->setHeaders(['obj per sec',  's', 'ms', 'memory']);

        $sec = round($event->getDuration() / 1000, 2);
        $o_per_m = round((int) $requests / $sec, 2);

        $helper->addRow([$o_per_m, $sec,$event->getDuration(), $event->getMemory()]);

        $output->writeln('performed ' . ( (int) $requests - 1 ). ' saving calls');
        $helper->render();

        $bulk = [];
        $stopwatch->start('perform_bulk');
        for ($index = 0; $index < $requests; $index++) {
            array_push($bulk, [
                'userName' => 'dk.projects.manager@gmail.com',
                'firstName' => 'Daniel',
                'lastName' => 'Koch'
            ]);
        }
        bulkObjects('debug_my-namespace',$bulk);

        $event = $stopwatch->stop('perform_bulk');

        $helper = new Table($output);
        $helper->setHeaders(['obj per sec',  's', 'ms', 'memory']);

        $sec = round($event->getDuration() / 1000, 2);
        $o_per_m = round((int) $requests / $sec, 2);

        $helper->addRow([$o_per_m, $sec,$event->getDuration(), $event->getMemory()]);

        $output->writeln('performed a bulk operation');
        $helper->render();
    }
}