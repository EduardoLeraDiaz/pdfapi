<?php

use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit_Framework_Assert as Assertions;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Defines application features from the specific context.
 */
class CallContext implements context
{
    protected $client;

    protected $request;

    protected $response;

    protected $message = null;

    protected $baseUrl;

    protected $exampleFile;


    public function __construct($baseUrl, $exampleFile=null)
    {
        $this->exampleFile = $exampleFile;
        $this->baseUrl = rtrim(trim($baseUrl), '\\');
        $this->client = new Client();
    }

    /**
     * @When I send a :method request to :uri
     */
    public function iSendARequestTo($method,$uri)
    {
        $request = new Request($method, $this->baseUrl . $uri);
        $this->response = $this->client->send($request, ['timeout' => 2]);
    }

    /**
     * @When I send a POST request to :arg1 with example pdf file and values:
     */
    public function iSendAPostRequestToWithExamplePdfFileAndValues($uri, TableNode $post)
    {
        $url = $this->baseUrl . $uri;
        $fields = array();

        foreach ($post->getRowsHash() as $key => $val) {
            $fields[$key] = $val;
        }
        $this->response = $this->client->request('POST', $url, [
            'multipart' => [
                [
                    'name' => 'data',
                    'contents' => json_encode($fields)
                ],
                [
                    'name' => 'document',
                    'contents' => fopen($this->exampleFile, 'r')
                ],

            ],
            'headers' => [
                "accept: application/json",
                "cache-control: no-cache",
                "content-type: application/json",
            ]
        ]);
    }

    /**
     * @Then the response should contain json:
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString)
    {
        $etalon = json_decode($jsonString->getRaw(), true);
        $actual = json_decode($this->response->getBody(), true);

        if (null === $etalon) {
            throw new \RuntimeException(
                "Can not convert etalon to json:\n" . $jsonString->getRaw()
            );
        }

        if (null === $actual) {
            throw new \RuntimeException(
                "Can not convert actual to json:\n" . (string) $this->response->getBody()
            );
        }

        Assertions::assertGreaterThanOrEqual(count($etalon), count($actual));
        foreach ($etalon as $key => $needle) {
            Assertions::assertArrayHasKey($key, $actual);
            Assertions::assertEquals($etalon[$key], $actual[$key]);
        }
    }

    /**
     * Checks that response has specific status code.
     *
     * @param string $code status code
     *
     * @Then /^(?:the )?response code should be (\d+)$/
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->response->getStatusCode());
        Assertions::assertSame($expected, $actual);
    }
}