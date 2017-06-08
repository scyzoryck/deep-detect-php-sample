<?php
declare(strict_types = 1);

namespace Scyzoryck\DeepDetectSample\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Scyzoryck\DeepDetectSample\Prediction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    protected const ARGUMENT_URL = 'url';

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct('app:dd:run');
        $this->addArgument(self::ARGUMENT_URL, InputArgument::REQUIRED, 'Url to image');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument(self::ARGUMENT_URL);
        $age = $this->getAge($url);
        $gender = $this->getGender($url);
        $helper = new FormatterHelper();
        $output->writeln($helper->formatBlock([
            'Prediction',
            'Age: ' . str_replace('_', '-',$age->getClass()) . ', Probability: ' . number_format($age->getProbability() * 100) . '%',
            'Gender: ' . $gender->getClass() . ', Probability: ' . number_format($gender->getProbability() * 100) . '%',
        ], 'info', true));
    }

    private function getAge(string $url): Prediction
    {
        $ageResponse = $this->client->post('predict', [
            'json' => [
                "service" => "age",
                "parameters" => [
                    "output" => [
                        "best" => 1,
                    ],
                ],
                "data" => [$url],
            ],
        ]);
        $age = $this->extractPredictions($ageResponse);

        return $age[0];
    }

    private function getGender(string $url): Prediction
    {
        $response = $this->client->post('predict', [
            'json' => [
                "service" => "gender",
                "parameters" => [
                    "output" => [
                        "best" => 1,
                    ],
                ],
                "data" => [$url],
            ],
        ]);

        return $this->extractPredictions($response)[0];
    }

    /**
     * @param ResponseInterface $response
     * @return array|Prediction[]
     */
    protected function extractPredictions(ResponseInterface $response): array
    {
        $array = json_decode($response->getBody()->getContents(), true);

        return array_map(function (array $prediction) {
            return new Prediction($prediction['cat'], $prediction['prob']);
        }, $array['body']['predictions'][0]['classes']);
    }
}