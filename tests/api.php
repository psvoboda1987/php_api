<?php

declare(strict_types = 1);

use GuzzleHttp\Client;
use Tester\Assert;
use Tracy\Debugger;
use Tracy\ILogger;

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

$client = new Client();

$baseUrl = 'http://api.svobodaweb.cz';

$endpoints = [
    'animal-facts',
    'animal-facts/random',
    'chuck-norris-jokes',
    'chuck-norris-jokes/random',
    'cities',
    'counties',
    'countries',
    'daily-quotes',
    'lorem-ipsum',
    'moon' => [
        'phase',
        'phase-name',
        'age-in-days',
        'earth-distance-in-km',
        'sun-distance-in-km',
        'illuminated-fraction',
        'next-first-quarter',
        'previous-last-quarter',
        'next-full-moon',
        'full-moon',
        'next-new-moon',
        'new-moon',
    ],
    'postal-codes',
    'regions',
    'validations/email/psvoboda1987@gmail.com',
];

runTest($baseUrl, $endpoints, $client);

function runTest($baseUrl, $endpoints, $client): void
{
    try {
        foreach ($endpoints as $key => $endpoint) {
            if (is_array($endpoint)) {
                foreach ($endpoint as $route) {
                    baseTest($client->request('GET', "$baseUrl/$key/$route"));
                }
            } else {
                baseTest($client->request('GET', "$baseUrl/$endpoint"));
            }
        }

        Assert::truthy(
            "$baseUrl/convert/xml-to-json?xml=%3CCD%3E%3CTITLE%3EEmpire%20Burlesque%3C/TITLE%3E%3CARTIST%3EBob%20Dylan%3C/ARTIST%3E%3CCOUNTRY%3EUSA%3C/COUNTRY%3E%3CCOMPANY%3EColumbia%3C/COMPANY%3E%3CPRICE%3E10.90%3C/PRICE%3E%3CYEAR%3E1985%3C/YEAR%3E%3C/CD%3E"
        );
        Assert::truthy(
            "$baseUrl/convert/json-to-xml?json={%22status%22:1,%22message%22:%22ok%22,%22itemsCount%22:1,%22data%22:[%22{\%22TITLE\%22:\%22Empire%20Burlesque\%22,\%22ARTIST\%22:\%22Bob%20Dylan\%22,\%22COUNTRY\%22:\%22USA\%22,\%22COMPANY\%22:\%22Columbia\%22,\%22PRICE\%22:\%2210.90\%22,\%22YEAR\%22:\%221985\%22}%22]}"
        );
    } catch (\Throwable $e) {
        var_dump($e->getTraceAsString());
        Debugger::log($e->getMessage(), ILogger::EXCEPTION);
    }
}

function baseTest($response): void
{
    Assert::truthy($response);
    Assert::truthy($response->getHeaders());
    Assert::equal(200, $response->getStatusCode());

    $contents = $response->getBody()->getContents();
    Assert::truthy($contents);

    $decodedData = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    Assert::hasKey('data', $decodedData);
    Assert::hasKey('status', $decodedData);
    Assert::hasKey('message', $decodedData);
    Assert::hasKey('itemsCount', $decodedData);
    Assert::truthy($decodedData['data']);
}
