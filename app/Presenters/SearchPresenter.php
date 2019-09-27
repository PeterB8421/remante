<?php

namespace App\Presenters;

use App\Model\SearchManager;

class SearchPresenter extends BasePresenter
{
    private $manager;

    public function __construct(SearchManager $manager){
        $this->manager = $manager;
    }

    public function renderDefault($query)
    {
        $this->template->results = $this->manager->search($query);
    }

    public function renderFilter($vyrobci, $typy, $offset = 0, $order = "id", $limit = 10){
        $this->template->results = $this->manager->getFiltered($offset, $order, $limit, $vyrobci, $typy);
    }
}