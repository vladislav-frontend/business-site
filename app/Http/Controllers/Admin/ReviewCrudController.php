<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\ReviewRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReviewCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReviewCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Review::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/review');
        CRUD::setEntityNameStrings('review', 'reviews');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('post_id');
        CRUD::column('user_ip');
        CRUD::column('user_name');
        CRUD::column('description');
        CRUD::column('rating');
        CRUD::column('status');
        CRUD::column('admin_id');
        CRUD::column('admin_comment');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ReviewRequest::class);

        CRUD::field('post_id');
        CRUD::addField([
            'name'         => 'user_ip',
            'label'        => 'User IP',
            'type'         => 'text',
            'value'        => $_SERVER["REMOTE_ADDR"],
            'attributes'   => [
                'disabled' => 'disabled',
            ]
        ]);
        CRUD::field('user_name');
        CRUD::field('description');
        CRUD::addField([
            'name'    => 'rating',
            'label'   => 'Rating',
            'type'    => 'radio',
            'options' => [
                1     => "1",
                2     => "2",
                3     => "3",
                4     => "4",
                5     => "5"
            ]
        ]);
        CRUD::addField([
            'name'          => 'status',
            'label'         => 'Status',
            'type'          => 'select_from_array',
            'options'       => [
                'draft'     => 'draft',
                'published' => 'published',
                'trash'     => 'trash'
            ],
            'allows_null'   => false,
            'default'       => 'draft'
        ]);
        CRUD::addField([
            'name'        => 'admin_id',
            'label'       => "Admin",
            'type'        => 'select_from_array',
            'options'     => User::all()->pluck('name', 'id'),
            'allows_null' => false,
            'default'     => 'one',
        ]);
        CRUD::field('admin_comment');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
