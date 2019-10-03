<?php

namespace Dvi\Support\Service\Controller\Base\Listing;

use Adianti\Widget\Dialog\TMessage;
use Dvi\Support\Service\Database\Transaction;

trait ListFlowTrait
{
    public function show()
    {
        if (empty($_GET['method']) or (!empty($_GET['method']) and $_GET['method'] == 'loadDataGrid')) {
            $this->index();
        }
        parent::show();
    }

    public function index()
    {
        try {
            Transaction::open(static::getDatabase());

            $this->createLayout();
            $this->loadDataGrid(http()->all());

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }
}
