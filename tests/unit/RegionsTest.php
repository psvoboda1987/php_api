<?php

declare(strict_types = 1);

namespace unit;

use App\Models\RegionsModel;
use Dibi\Connection;
use Nette\Neon\Neon;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

final class RegionsTest extends TestCase
{
    private RegionsModel $regions;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $connection = new Connection(Neon::decode(file_get_contents(__DIR__ . '/../..//config/local.neon'))['dibi']);
        $this->regions = new RegionsModel($connection);
    }

    public function testData(): void
    {
        self::assertNotEmpty($this->regions->getRegions()->fetchPairs(null, 'region'));
        self::assertNotEmpty($this->regions->getAllCitiesCodes()->fetchPairs('city', 'zip'));
    }
}

