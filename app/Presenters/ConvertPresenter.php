<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ConvertModel;
use dibi\Connection;
use RuntimeException;
use Throwable;

final class ConvertPresenter extends BasePresenter
{
    public function __construct(Connection $db, private readonly ConvertModel $convertModel)
    {
        parent::__construct($db);
    }

    // POST|GET convert/json-to-xml?json={"status":1,"message":"ok","itemsFound":6,"itemsCount":1,"data":{"25162":"Žernovka"}}
    public function actionJsonToXml(): void
    {
        $json = $this->getHttpRequest()->getPost('json');

        if ($json === null) {
            $json = $this->getHttpRequest()->getQuery('json');
        }

        if ($json === null) {
            throw new RuntimeException('no json given');
        }

        try {
            $this->sendItemsResponse(
                (array)$this->convertModel->array2xml(
                    json_decode($json, true, 512, JSON_THROW_ON_ERROR)
                )
            );
        } catch (Throwable) {
            $this->sendItemsResponse([]);
        }
    }

    // POST|GET convert/xml-to-json?xml
    public function actionXmlToJson(): void
    {
        $xml = $this->getHttpRequest()->getQuery('xml');

        if ($xml === null) {
            $xml = $this->getHttpRequest()->getQuery('xml');
        }

        if ($xml === null) {
            throw new RuntimeException('xml not privided');
        }

        try {
            $this->sendItemsResponse($this->convertModel->xml2json($xml));
        } catch (Throwable $e) {
            $this->createLog($e);

            $response = $this->getResponse();
            $response->offsetSet('message', 'no xml provided');

            $this->sendItemsResponse($response);
        }
    }
}
