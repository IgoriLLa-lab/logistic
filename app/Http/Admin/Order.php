<?php

namespace App\Http\Admin;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

/**
 * Class Order
 *
 * @property \App\Models\Order $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Order extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Заказы';

    /**
     * @var string
     */
    protected $alias;

    /**
     * Массив типов заказа
     *
     * @var array|string[]
     */
    protected array $arrayType = ['online' => 'online', 'offline' => 'offline']; //костыль с пар ключ значение

    /**
     * Массив статусов заказа
     *
     * @var array|string[]
     */
    protected array $arrayStatus = ['active'=>'active', 'completed'=>'completed', 'canceled'=>'canceled']; //костыль с пар ключ значение

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-lightbulb-o');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {

        $columns = [
            AdminColumn::text('id', 'Id')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('customer', 'Customer')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('phone', 'Phone')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('created_at', 'Created')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('completed_at', 'Completed')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('user.name', 'Имя Менеджера')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('type', 'Тип заказа')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('status', 'Статус')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::count('orderItems.product_id', 'Кол-во продуктов')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::lists('orderItems.product_name', 'Продукты', 'orderItems.product')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('orderItems.discount', 'Скидка')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('orderItems.cost', 'Сумма')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),

        ];

        $display = AdminDisplay::datatablesAsync()
            ->setName('firstdatatables')
            ->with('user', 'orderItems')
            ->setOrder([[0, 'asc']])
            ->paginate(5)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center');

        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $form = AdminForm::card()->addBody([

            AdminFormElement::text('customer', 'Customer'),
            AdminFormElement::text('phone', 'Phone'),

            AdminFormElement::datetime('created_at', 'Created')
                ->setVisible(true)
                ->setReadOnly(false),

            AdminFormElement::datetime('completed_at', 'Completed')
                ->setVisible(true)
                ->setReadOnly(false),

            AdminFormElement::text('user.name', 'Name User'),

            AdminFormElement::select('type', 'Type', $this->arrayType),

            AdminFormElement::select('status', 'Status', $this->arrayStatus),

            AdminFormElement::select('product_id', 'Кол-во продуктов'),
            AdminFormElement::select('product_name', 'Продукты'),

        ]);

        $form->getButtons()->setButtons([
            'save' => new Save(),
            'save_and_close' => new SaveAndClose(),
            'save_and_create' => new SaveAndCreate(),
            'cancel' => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        return $this->onEdit(null, $payload);
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return false;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
