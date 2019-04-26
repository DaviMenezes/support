<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormField;

/**
 * FormFieldItems
 */
abstract class FormFieldItems extends FormField
{
    protected $model;
    protected $indexed_id;
    protected $indexed_value;
    protected $order;
    protected $direction;
    protected $filters = array();

    public function items($items)
    {
        $this->field->addItems($items);
        return $this;
    }

    public function model($model, $indexed_id = 'id', $indexed_value = 'name')
    {
        $this->model = $model;
        $this->indexed_id = $indexed_id;
        $this->indexed_value = $indexed_value;
        return $this;
    }

    public function order($order, $direction = 'asc')
    {
        $this->order = $order;
        $this->direction = $direction;
        return $this;
    }

    public function filters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function filter(TFilter $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function get()
    {
        if ($this->model) {
            $repository = new TRepository($this->model);

            if ($this->order) {
                $repository->orderBy($this->order, $this->direction);
            }

            $criteria = new TCriteria();
            if ($this->filters) {
                foreach ($this->filters as $filter) {
                    $criteria->add($filter);
                }
            }
            $items = $repository->getIndexedArray($this->indexed_id, $this->indexed_value, $criteria);
            $this->items($items);
        }
        return parent::get();
    }
}
