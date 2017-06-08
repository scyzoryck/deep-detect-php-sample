<?php
declare(strict_types = 1);

namespace Scyzoryck\DeepDetectSample\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    protected const OPTION_REMOVE = 'force';

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct('app:dd:init');
        $this->setDescription('Init Deep Detect models');
        $this->addOption(self::OPTION_REMOVE, 'f', InputOption::VALUE_NONE, 'Remove services if exists');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remove = $input->getOption(self::OPTION_REMOVE);
        if ($remove) {
            $this->client->delete('services/age');
        }
        $this->client->put('services/age', [
            'json' => [
                "mllib" => "caffe",
                "description" => "age classification",
                "type" => "supervised",
                "parameters" => [
                    "input" => [
                        "connector" => "image",
                        "height" => 227,
                        "width" => 227,
                    ],
                    "mllib" => [
                        "nclasses" => 8,
                    ],
                ],
                "model" => [
                    "repository" => "/var/deep-detect/models/age",
                ],
            ],
        ]);
        if ($remove) {
            $this->client->delete('services/gender');
        }
        $this->client->put('services/gender', [
            'json' => [
                "mllib" => "caffe",
                "description" => "gender classification",
                "type" => "supervised",
                "parameters" => [
                    "input" => [
                        "connector" => "image",
                    ],
                    "mllib" => [
                        "nclasses" => 2,
                    ],
                ],
                "model" => [
                    "repository" => "/var/deep-detect/models/gender",
                ],
            ],
        ]);
    }
}