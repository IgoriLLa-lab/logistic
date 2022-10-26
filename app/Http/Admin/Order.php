<?php

namespace App\Http\Admin;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Http\Controllers\ProductController;
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
            AdminColumn::text('user.name', 'Manager Name')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('type', 'Type')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('status', 'Status')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::count('orderItems.product_id', 'Count product')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            $products = AdminColumn::lists('orderItems.product', 'Products<br/><small>lists</small>')->setWidth('50px')->setHtmlAttribute('class', 'hidden-sm hidden-xs hidden-md'),
            AdminColumn::text('orderItems.discount', 'Discount')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::text('orderItems.cost', 'Cost')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),

        ];

        $display = AdminDisplay::datatablesAsync()
            ->setName('firstdatatables')
            ->with('user', 'orderItems')
            ->setDisplaySearch(true)
            ->setOrder([[0, 'asc']])
            ->paginate(5)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center');

        $products->getHeader()->setHtmlAttribute('class', 'hidden-sm hidden-xs hidden-md');

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

            AdminFormElement::select(),

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
