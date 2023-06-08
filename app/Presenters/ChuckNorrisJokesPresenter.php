<?php

declare(strict_types = 1);

namespace App\Presenters;

use App\Models\ChuckNorrisJokesModel;
use dibi\Connection;

final class ChuckNorrisJokesPresenter extends BasePresenter
{
    public function __construct(Connection $db, private readonly ChuckNorrisJokesModel $chuckNorrisJokesModel)
    {
        parent::__construct($db);
    }

    // GET chuck-norris-jokes
    public function actionDefault(): void
    {
        $this->query = $this->chuckNorrisJokesModel->getAllJokes();

        $this->setQueryLimit();
        $this->sendItemsResponse($this->query->fetchPairs(null, 'joke'));
    }

    // GET chuck-norris-jokes/random
    public function actionRandom(): void
    {
        $this->sendItemsResponse(
            (array)$this->chuckNorrisJokesModel->getRandomJoke()
        );
    }
}
