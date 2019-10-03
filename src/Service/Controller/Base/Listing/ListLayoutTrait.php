<?php

namespace Dvi\Support\Service\Controller\Base\Listing;

use Adianti\Base\Lib\Control\TAction;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Util\TBreadCrumb;

trait ListLayoutTrait
{
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


}
