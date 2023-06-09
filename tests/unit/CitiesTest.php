<?php

declare(strict_types = 1);

namespace unit;

use App\Models\CitiesModel;
use Dibi\Connection;
use Nette\Neon\Neon;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

final class CitiesTest extends TestCase
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $connection = new Connection(Neon::decode(file_get_contents(__DIR__ . '/../..//config/local.neon'))['dibi']);
        $this->citiesModel = new CitiesModel($connection);
    }

    public function testData(): void
    {
        self::assertNotEmpty($this->citiesModel->getAllCities()->fetchAll());
    }
}

