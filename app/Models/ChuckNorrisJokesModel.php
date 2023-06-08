<?php

declare(strict_types = 1);

namespace App\Models;

use dibi\Connection;
use Dibi\Fluent;

final class ChuckNorrisJokesModel
{
    public const TABLE = 'chuck_joke';

    public function __construct(private readonly Connection $db)
    {
    }

    public function getAllJokes(): Fluent
    {
        return $this->db->select('*')->from(self::TABLE);
    }

    public function getRandomJoke()
    {
        return $this->db->select('*')
            ->from(self::TABLE)
            ->orderBy('RAND()')
            ->limit(1)
            ->fetch();
    }
}