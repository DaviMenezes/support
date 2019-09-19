<?php
namespace Dvi\Support\Service\Controller\Base\Form;

use Adianti\Base\Lib\Control\TAction;
use Adianti\Base\Lib\Wrapper\BootstrapFormBuilder;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Database\TRecord;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Dvi\Support\Http\Request;
use Dvi\Support\Service\Database\Transaction;
use Exception;
use ReflectionClass;

/**
 *  ControlFormTrait
 *
 * @property BootstrapFormBuilder $form
 */
trait ControlFormTrait
{
    protected $form_custom_data;

    #region [SERVICES]
    public function onSave(Request $request)
    {
        try {
            Transaction::open(self::$database);

            $this->beforeSave();

            $this->fillObjectWithFormData($request);

            $this->save();

            $this->afterSave();

            Transaction::close();
        } catch (Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    protected function beforeSave()
    {
        $this->createLayout();

        $data = $this->getFormData();

        $this->form->setData($data);

        $this->form_custom_data = $data;

        $errors = array();
        foreach ($this->form->getFields() as $fieldObject) {
            try {
                $field_name = $fieldObject->getName();

                if (!isset($data->$field_name)) {
                    continue;
                }

                $fieldObject->setValue($data->$field_name);
                $fieldObject->validate();
            } catch (Exception $e) {
                $errors[] = $e->getMessage() . '.';
            }
        }

        if (count($errors) > 0) {
            throw new Exception(implode("<br>", $errors));
        }
        return true;
    }

    protected function fillObjectWithFormData(Request $request): void
    {
        if ($this->form) {
            $data = (array)$this->form_custom_data ?? $this->form->getData();
        } else {
            $data = $request->all();
        }

        self::$currentObject->fromArray($data);
    }

    public function salvageQuestion()
    {
        try {
            TTransaction::open($this->getDatabase());

            $this->beforeSave();

            $action_yes = new TAction([$this, 'salvageConfirmed']);

            new TQuestion($this->getSalvageQuestionMessage(), $action_yes);

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    protected function getSalvageQuestionMessage(): string
    {
        return 'Salvando ' . (new ReflectionClass(self::getModel()))->getShortName();
    }

    public function salvageConfirmed(Request $request)
    {
        $this->onSave($request);
    }

    protected function save()
    {
        self::$currentObject->store();

        return self::$currentObject;
    }

    protected function afterSave()
    {
        new TMessage('info', 'Dados salvos.');
        $this->redirect();
    }

    public function onEdit(Request $request)
    {
        try {
            Transaction::open(self::$database);
            if (empty($request->query('id'))) {
                throw new Exception('Permissão negada');
            }

            $this->createLayout();

            /**@var TRecord $model*/
            $class = get_called_class();
            $model = $class::getModel();
            $result = $model::find($request->body('id'));

            $this->fillFormWithData($result);

            Transaction::close();
        } catch (Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    protected function redirect(): void
    {
        AdiantiCoreApplication::loadPage((new ReflectionClass(get_called_class()))->getShortName(), 'onEdit', ['id' => self::$currentObject->id]);
    }

    protected function createFormActionSave()
    {
        $btn = $this->form->addAction('Salvar', new TAction([$this, 'onSave']));
        $btn->addStyleClass('btn btn-primary');
    }

    protected function createFormActionBackToList()
    {
        $btn = $this->form->addActionLink('Voltar', new TAction([$this->getListClass(), 'index']), 'fa:arrow-left');
        $btn->class = 'btn btn-primary';
        return $btn;
    }

    protected function fillFormWithData($result)
    {
        $this->form->setData($result);
    }
    #endregion
}
