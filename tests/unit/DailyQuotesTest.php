<?php

declare(strict_types = 1);

namespace unit;

use App\Models\DailyQuotesModel;
use Dibi\Connection;
use Nette\Neon\Neon;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

final class DailyQuotesTest extends TestCase
{
    private DailyQuotesModel $dailyQuotesModel;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $connection = new Connection(Neon::decode(file_get_contents(__DIR__ . '/../..//config/local.neon'))['dibi']);
        $this->dailyQuotesModel = new DailyQuotesModel($connection);
    }

    public function testData(): void
    {
        self::assertNotEmpty($this->dailyQuotesModel->getCategories()->fetchAll());
        self::assertNotEmpty($this->dailyQuotesModel->getAuthors()->fetchAll());
        self::assertNotEmpty($this->dailyQuotesModel->getRandom()->fetchAll());
        self::assertNotEmpty($this->dailyQuotesModel->getAll()->fetchAll());
    }
}

