<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactsRequest;
use App\Models\Language;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContactsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Contacts::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contacts');
        CRUD::setEntityNameStrings('contacts', 'contacts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('phone_1');
        CRUD::column('phone_2');
        CRUD::column('telegram');
        CRUD::column('email');
        CRUD::column('skype');

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

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        CRUD::column('phone_1');
        CRUD::column('phone_2');
        CRUD::column('telegram');
        CRUD::column('email');
        CRUD::column('skype');

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
        CRUD::setValidation(ContactsRequest::class);

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
            ]);
        }

        CRUD::field('phone_1');
        CRUD::field('phone_2');
        CRUD::field('telegram');
        CRUD::field('email');
        CRUD::field('skype');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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

        $contacts = $this->crud->update($request->get($this->crud->model->getKeyName()), $this->crud->getStrippedSaveRequest());

        $this->editTranslations($contacts, $translations, $languages);

        return $this->crud->performSaveAction($contacts->getKey());
    }

    function editTranslations($contacts, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $contacts;
        $this->crud->setSaveAction();

        $all_translations = $contacts->translations()->get();
        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == $language->id && data_get($translation, 'language_id') == $language->id) {
                        $one_translation->title         = data_get($translation, "title");
                        $one_translation->description   = data_get($translation, "description");
                        $one_translation->save();
                    }
                }
            }
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
    }
}
