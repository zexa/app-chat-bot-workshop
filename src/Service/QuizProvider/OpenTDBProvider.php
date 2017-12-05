<?php

namespace Paysera\Workshop\ChatBot\Service\QuizProvider;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\Psr7\build_query;
use function GuzzleHttp\json_decode;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Paysera\Workshop\ChatBot\Exception\QuizProviderException;
use Paysera\Workshop\ChatBot\Normalizer\QuestionNormalizer;

class OpenTDBProvider implements QuizProviderInterface
{
    private $client;
    private $questionNormalizer;

    public function __construct(
        ClientInterface $client,
        QuestionNormalizer $questionNormalizer
    ) {
        $this->client = $client;
        $this->questionNormalizer = $questionNormalizer;
    }

    public function getNextQuestion()
    {
        $uri = (new Uri('https://opentdb.com/api.php'))
            ->withQuery(build_query([
                'amount' => 1,
                'difficulty' => 'easy',
            ]))
        ;
        $request = new Request('GET', $uri);

        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $guzzleException) {
            throw new QuizProviderException('Failed to get next Question', 0, $guzzleException);
        }

        $contents = json_decode($response->getBody()->getContents(), true);
        if (!isset($contents['results']) && count($contents['results']) !== 1) {
            throw new QuizProviderException('No results found');
        }
        
        return $this->questionNormalizer->mapToEntity($contents['results'][0]);
    }
}
