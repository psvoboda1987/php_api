<?php

declare(strict_types = 1);

namespace unit;

use App\Models\LoremModel;
use Dibi\Connection;
use Faker\Factory;
use Nette\Neon\Neon;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

final class LoremTest extends TestCase
{
    private LoremModel $loremModel;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $connection = new Connection(Neon::decode(file_get_contents(__DIR__ .'/../..//config/local.neon'))['dibi']);
        $this->loremModel = new LoremModel($connection, new Factory());
    }

    public function testData(): void
    {
        self::assertNotEmpty($this->loremModel->getFakeData('city'));
    }
}

