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
 * Class OrderItems
 *
 * @property \App\Models\OrderItems $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class OrderItems extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Информация о заказах';

    /**
     * @var string
     */
    protected $alias;

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
        $display = AdminDisplay::tabbed(); //вывод табами

        $display->setTabs(function () {

            $tabs = [];

            $columnsOrder = [
                AdminColumn::count('product.product_id', 'Количество продуктов', 'products')->setWidth('50px')->setHtmlAttribute('class', 'text-center')
            ];

            $columnsOrderInfo = [
                AdminColumn::text('product.name', 'ProductName')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
                AdminColumn::count('order.status', 'Кол-во выполненных заказов<br/><small>(count)</small>')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
                AdminColumn::text('discount', 'Discount')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            ];

            $main = AdminDisplay::datatablesAsync()
                ->setName('orderDataTables')
                ->setOrder([[0, 'asc']])
                //->setDisplaySearch(true)
                ->paginate(10)
                ->with(['order', 'product'])
                ->setColumns($columnsOrder)
                ->setHtmlAttribute('class', 'table-primary table-hover th-center');

            $mainS = AdminDisplay::datatablesAsync()
                ->setName('orderSTables')
                ->setOrder([[0, 'asc']])
//                ->setDisplaySearch(true)
                ->paginate(10)
                ->with(['order', 'product'])
                ->setColumns($columnsOrderInfo)
                ->setHtmlAttribute('class', 'table-primary table-hover th-center');

            $tabs[] = AdminDisplay::tab($main, 'Заказы')->setActive(true);


            $tabs[] = AdminDisplay::tab($mainS, 'Отчет о заказах (количество и стоимость)');


            return $tabs;
        });

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
            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('name', 'Name')
                    ->required()
                ,
                AdminFormElement::html('<hr>'),
                AdminFormElement::datetime('created_at')
                    ->setVisible(true)
                    ->setReadonly(false)
                ,
                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4')->addColumn([
                AdminFormElement::text('id', 'ID')->setReadonly(true),
                AdminFormElement::html('last AdminFormElement without comma')
            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8'),
        ]);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
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
