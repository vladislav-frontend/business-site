<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HomeRequest;
use App\Models\Language;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HomeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HomeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Home::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/home');
        CRUD::setEntityNameStrings('home', 'homes');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setTitle('Home');
        CRUD::setHeading('Home');

        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Title',
            'type'      => 'relationship',
            'key'       => 'translations_title',
            'entity'    => 'translations',
            'attribute' => 'title',
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Description',
            'type'      => 'relationship',
            'key'       => 'translations_description',
            'entity'    => 'translations',
            'attribute' => 'description',
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Summary',
            'type'      => 'relationship',
            'key'       => 'translations_summary',
            'entity'    => 'translations',
            'attribute' => 'summary',
        ]);
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
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
        CRUD::setValidation(HomeRequest::class);

        CRUD::setTitle('Home');
        CRUD::setHeading('Home');

        foreach (Language::all() as $language) {
            if ($this->crud->getOperation() != 'create') {
                $translations = $this->crud->getCurrentEntry()->translations->where('language_id', $language->id)->first();
            } else {
                $translations = null;
            }

            CRUD::addFields([
                [
                    'name' => $language->name . '[language_id]',
                    'type' => 'hidden',
                    'value' => $language->id,
                    'tab' => $language->name,
                ],
                [
                    'name'    => $language->name.'[title]',
                    'label'   => 'Title',
                    'type'    => 'related_textarea',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'value'   => $translations->title ?? '',
                    'tab'     => $language->name,
                ],
                [
                    'name'    => $language->name.'[description]',
                    'label'   => 'Description',
                    'type'    => 'related_textarea',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'value'   => $translations->description ?? '',
                    'tab'     => $language->name,
                ],
                [
                    'name'  => $language->name.'[summary]',
                    'type'  => 'related_ckeditor',
                    'label' => 'Summary',
                    'value' => $translations->summary ?? '',
                    'tab'   => $language->name,
                ],
            ]);
        }
    }

    protected function setupShowOperation()
    {
        CRUD::setTitle('Home');
        CRUD::setHeading('Home');

        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Title',
            'type'      => 'relationship',
            'key'       => 'translations_title',
            'entity'    => 'translations',
            'attribute' => 'title',
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Description',
            'type'      => 'relationship',
            'key'       => 'translations_description',
            'entity'    => 'translations',
            'attribute' => 'description',
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Summary',
            'type'      => 'relationship',
            'key'       => 'translations_summary',
            'entity'    => 'translations',
            'attribute' => 'summary',
        ]);
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();

        $languages = Language::all();
        foreach ($languages as $language) {
            $translations[] = $this->crud->getRequest()->input($language->name);
            $request->request->remove($language->name);
        }

        $this->crud->setRequest($request);

        $home = $this->crud->update($request->get($this->crud->model->getKeyName()), $this->crud->getStrippedSaveRequest());

        $this->editTranslations($home, $translations, $languages);

        return $this->crud->performSaveAction($home->getKey());
    }

    function editTranslations($home, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $home;
        $this->crud->setSaveAction();

        $all_translations = $home->translations()->get();
        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == $language->id && data_get($translation, 'language_id') == $language->id) {
                        $one_translation->title         = data_get($translation, "title");
                        $one_translation->description   = data_get($translation, "description");
                        $one_translation->summary       = data_get($translation, "summary");
                        $one_translation->save();
                    }
                }
            }
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
    }
}
