<?php
namespace Dvi\Support\Service\Controller\Base;

use Adianti\Widget\Container\TPanelGroup;
use App\Control\User\Model\User;
use Dvi\Support\Service\Controller\Base\ControlBase;
use Dvi\Support\Service\Controller\ControlFormService;

/**
 *  ControlFormListBase
 * @property User $loggedUser
 * @property TPanelGroup $panel
 */
abstract class ControlFormListBase extends ControlBase
{
    protected $form;

    use ControlFormService;
}
