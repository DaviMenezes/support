<?php
namespace Dvi\Support\Acl\Service\Controller\Base\Form;

use Dvi\Support\Service\Base\Contract\ControlFormBaseInterface;
use Dvi\Support\Service\Controller\Base\ControlFormListBase;
use Dvi\Support\Service\Controller\Base\Form\ControlFormLayout;
use Dvi\Support\Service\Controller\ControlFormService;

abstract class ControlFormBase extends ControlFormListBase implements ControlFormBaseInterface
{
    use \Dvi\Support\Service\Controller\Base\Form\ControlFormTrait;
    use ControlFormLayout;
    use ControlFormService;

    abstract protected static function getListClass();

    public function __construct($param, $mantem_conexao = false)
    {
        if (in_array(http()->url('method'), ['onEdit', 'edit']) && !http()->url('id')) {
            throw new \Exception('Na tentativa de editar, informe um id');
        }

        parent::__construct($param, $mantem_conexao);
    }

    protected function createLayout()
    {
        try {
            if ($this->already_create_layout) {
                return;
            }

            $this->createForm();

            $this->setLayoutContainer();

            $this->already_create_layout = true;
        } catch (\Exception $e) {
            throw new \Exception('Criando layout - '.$e->getMessage());
        }
    }

    public function show()
    {
        if (empty(http()->get('method'))) {
            $this->index();
        }
        parent::show();
    }

    protected static function getDatabase()
    {
        return 'appportal';
    }

    protected static function getFormName()
    {
        return get_called_class() . '_form';
    }
}
