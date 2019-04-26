<?php
namespace Dvi\Support\Acl\Service\Controller\Base\Listing;

use Adianti\Control\TAction;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;
use Adianti\Registry\TSession;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Wrapper\BootstrapDatagridWrapper;
use Adianti\Wrapper\BootstrapFormBuilder;
use Dvi\Support\Service\Database\Transaction;
use Exception;

/**
 *  ControlListTrait
 * @property TDataGrid $datagrid
 * @property TPageNavigation $pageNavigation
 * @property TRepository $repository
 * @property TCriteria $criteria
 * @property BootstrapFormBuilder $form
 */
trait ControlDviListTrait
{
    protected $datagrid;
    protected $pageNavigation;
    protected $repository;
    protected $criteria;

    protected function beforeLoadDatagrid($param)
    {
        $class = get_called_class();
        $model = $class::getModel();
        $this->repository = new TRepository($model);

        $this->criteria = new TCriteria;

        if (empty($param['order'])) {
            $param['order'] = 'id';
            $param['direction'] = 'asc';
        }
        $this->criteria->setProperties($param); // order, offset
        $this->criteria->setProperty('limit', $this->getLimit());
    }

    public function loadDataGrid($param)
    {
        try {
            Transaction::open(self::$database);

            $this->beforeLoadDatagrid($param);

            $this->checkFilters();

            $this->createLayout();

            $items = $this->repository->load($this->criteria, false);

            $this->datagrid->clear();
            if ($items) {
                $this->addDatagridItems($items);

                $this->configPageNavigation($param);
            }

            $this->afterLoadDatagrid($param);

            Transaction::close();
        } catch (Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    protected function afterLoadDatagrid($param): void
    {
        //do not remove
    }

    #region [LAYOUT DATAGRID]
    protected function createDatagrid()
    {
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid());
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        $this->datagrid->setHeight(320);

        $this->createDatagridColumns();

        $this->createDatagridActions();

        $this->datagrid->createModel();
    }

    protected function createDatagridColumns(): void
    {
        $this->createColumnId();
    }

    protected function createColumnId()
    {
        $column = new TDataGridColumn('id', 'Id', 'center', '10%');
        $this->datagrid->addColumn($column);
    }

    protected function createDatagridActions(): void
    {
        $this->createDatagridActionEdit();
    }

    /**@return TDataGridAction*/
    protected function createDatagridActionEdit()
    {
        $action = new TDataGridAction([$this->getFormClass(), 'onEdit']);
        $action->setUseButton(true);
        $action->setField('id');
        $action->setLabel(_t('Edit'));
        $action->setImage('fa:pencil fa-2x green');
        $this->datagrid->addAction($action);
        return $action;
    }

    protected function createPagination()
    {
        $this->pageNavigation = new TPageNavigation();
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction($this->getPageNavigationAction());
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
    }
    #endregion layoutgrid

    #region [SERVICES]
    abstract protected function getLimit();

    public function onSearch($param)
    {
        try {
            TTransaction::open($this->getDatabase());

            $this->createLayout();

            $data = $this->form->getData();

            $this->form->setData($data);

            TSession::setValue(get_called_class().'_filters', null);

            $filters = [];
            if (!empty($data->id)) {
                $filters[] = new TFilter('id', '=', $data->id);
            }

            $this->createSearchFilters($data, $filters);

            TSession::setValue(get_called_class().'_filters', $filters);
            TSession::setValue(get_called_class().'_filter_data', $data);

            $this->loadDataGrid($param);

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    abstract protected function createSearchFilters($data, &$filters);

    protected function checkFilters(): void
    {
        $filters = TSession::getValue(get_called_class() . '_filters');
        if ($filters) {
            foreach ($filters as $filter) {
                $this->criteria->add($filter);
            }
        }
        TSession::setValue(get_called_class() . '_filters', null);
    }

    protected function configPageNavigation($param): void
    {
        $this->criteria->resetProperties();
        $count = $this->repository->count($this->criteria);

        $this->pageNavigation->setCount($count);
        $this->pageNavigation->setProperties($param);
        $this->pageNavigation->setLimit($this->getLimit());
    }

    protected function getPageNavigationAction(): TAction
    {
        return new TAction(array($this, 'loadDataGrid'));
    }

    protected function addDatagridItems(array $items): void
    {
        $this->datagrid->addItems($items);
    }
    #endregion
}
