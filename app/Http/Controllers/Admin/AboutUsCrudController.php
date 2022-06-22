<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AboutUsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Language;
use App\Models\AboutUs;
use App\Models\AboutUsTranslations;

/**
 * Class AboutUsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AboutUsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\AboutUs::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/about-us');
        CRUD::setEntityNameStrings('about us', 'about uses');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setTitle('About Us');
        $this->crud->setHeading('About Us');

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
        CRUD::setValidation(AboutUsRequest::class);

        $this->crud->setTitle('About Us');
        $this->crud->setHeading('About Us');

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
        $this->crud->setTitle('About Us');
        $this->crud->setHeading('About Us');

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

        $aboutus = $this->crud->update($request->get($this->crud->model->getKeyName()), $this->crud->getStrippedSaveRequest());

        $this->editTranslations($aboutus, $translations, $languages);

        return $this->crud->performSaveAction($aboutus->getKey());
    }

    function editTranslations($aboutus, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $aboutus;
        $this->crud->setSaveAction();

        $all_translations = $aboutus->translations()->get();
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
