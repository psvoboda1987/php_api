<?php

declare(strict_types = 1);

namespace unit;

use App\Models\AnimalFactsModel;
use Dibi\Connection;
use Nette\Neon\Neon;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

final class AnimalsFactsTest extends TestCase
{
    private AnimalFactsModel $animalFactsModel;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $connection = new Connection(Neon::decode(file_get_contents(__DIR__ . '/../..//config/local.neon'))['dibi']);
        $this->animalFactsModel = new AnimalFactsModel($connection);
    }

    public function testData(): void
    {
        self::assertNotEmpty($this->animalFactsModel->getRandomAnimal()->fetchAll());
        self::assertNotEmpty($this->animalFactsModel->getAll()->fetchAll());
    }
}

