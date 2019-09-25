<?php
namespace Dvi\Support\Service\Controller\Base\Listing;

use Adianti\Base\Lib\Control\TAction;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGridActionGroup;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Util\TBreadCrumb;
use Adianti\Wrapper\BootstrapFormBuilder;
use Dvi\Support\Acl\Service\Controller\Base\Listing\ControlDviListTrait;
use Dvi\Support\Service\Controller\Base\Contract\InterfaceControlList;
use Dvi\Support\Service\Controller\Base\ControlFormListBase;
use Dvi\Support\Service\Controller\Base\Form\ControlFormLayout;
use Dvi\Support\Service\Database\Transaction;

/**
 *  ControlListBase
 *
 * @author     Davi Menezes
 * @property BootstrapFormBuilder $form
 */
abstract class ControlListBase extends ControlFormListBase implements InterfaceControlList
{
    protected $form;
    protected $use_action_group;
    /**@var TDataGridActionGroup $datagrid_action_group*/
    protected $datagrid_action_group;

    use ControlDviListTrait;
    use ControlFormLayout;

    protected function createLayout()
    {
        if ($this->already_create_layout) {
            return;
        }
        $this->createForm();

        $this->createDatagrid();

        $this->createPagination();

        $panel_grid = new TPanelGroup;
        $panel_grid->add($this->datagrid);

        $panel_grid->addFooter($this->pageNavigation);

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create([$this->getPageTitle()]));
        $container->add($this->form);
        $container->add($panel_grid);

        parent::add($container);

        $this->already_create_layout = true;
    }

    protected function createFormActions()
    {
        $this->createActionSearch();

        $this->createFormActionNew();
    }

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

    abstract protected function getFormClass();

    protected static function getDatabase()
    {
        return 'default';
    }

    protected static function getFormName()
    {
        return get_called_class() . '_form';
    }

    protected function getLimit()
    {
        return 10;
    }

    protected static function getListClass()
    {
        return get_called_class();
    }

    protected function createFormActionNew()
    {
        $btn = $this->form->addActionLink('Novo', new TAction([$this->getFormClass(), 'index']), 'fa:plus');
        $btn->class = 'btn btn-primary';
    }

    protected function createActionSearch(): void
    {
        $button = $this->form->addAction('Consultar', new TAction([$this, 'onSearch']), 'fa:search');
        $button->addStyleClass('btn-primary');
    }
}
