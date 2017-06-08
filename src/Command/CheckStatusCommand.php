<?php
declare(strict_types = 1);

namespace Scyzoryck\DeepDetectSample\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckStatusCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct('app:dd:status');
        $this->setDescription('Checks Deep Detect status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rawResponse = $this->client->get('info');
        $status = json_decode($rawResponse->getBody()->getContents(), true);
        $color = $status['status']['code'] === 200 ? 'info' : 'error';

        $helper = new FormatterHelper();
        $output->writeln($helper->formatBlock([
            'STATUS',
            'Code: ' . $status['status']['code'],
            'Message: ' . $status['status']['msg'],
        ], $color, true));
    }
}