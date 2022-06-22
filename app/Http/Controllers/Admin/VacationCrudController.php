<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VacationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Language;
use App\Models\Vacation;
use App\Models\VacationTranslations;

/**
 * Class VacationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VacationCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Vacation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/vacation');
        CRUD::setEntityNameStrings('vacation', 'vacations');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Name',
            'type'      => 'relationship',
            'entity'    => 'translations',
            'attribute' => 'name',
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Summary',
            'type'      => 'relationship',
            'entity'    => 'translations',
            'attribute' => 'summary',
        ]);
        CRUD::column('created_at');

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
        CRUD::setValidation(VacationRequest::class);

        $languages = Language::all();
        foreach ($languages as $language) {
            if ($this->crud->getOperation() != 'create') {
                $translations = $this->crud->getCurrentEntry()->translations->where('language_id', $language->id)->first();
            } else {
                $translations = null;
            }

            $this->crud->addFields([
                [
                    'name'    => $language->name.'[language_id]',
                    'type'    => 'hidden',
                    'value'   => $language->id,
                    'tab'     => $language->name,
                ],
                [
                    'name'    => $language->name.'[name]',
                    'type'    => 'textarea',
                    'label'   => 'Name',
                    'wrapper' => ['class' => 'form-group col-md-12'],
                    'value'   => $translations->name ?? '',
                    'tab'     => $language->name,
                ],
                [
                    'name'    => $language->name.'[summary]',
                    'type'    => 'related_ckeditor',
                    'label'   => 'Summary',
                    'wrapper' => ['class' => 'form-group col-md-12'],
                    'value'   => $translations->summary ?? '',
                    'tab'     => $language->name,
                ],
            ]);
        }

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

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Name',
            'type'      => 'relationship',
            'key'       => 'translations_name',
            'entity'    => 'translations',
            'attribute' => 'name'
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Summary',
            'type'      => 'relationship',
            'key'       => 'translations_summary',
            'entity'    => 'translations',
            'attribute' => 'summary'
        ]);
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    public function store(VacationRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $this->crud->validateRequest();

        $languages = Language::all();
        $translations = [];
        foreach ($languages as $language) {
            $translations[] = $this->crud->getRequest()->input($language->name);
            $request->request->remove($language->name);
        }

        $this->crud->setRequest($request);

        /** @var Vacation $vacation */
        $vacation = $this->crud->create($this->crud->getStrippedSaveRequest());

        $this->createTranslations($vacation, $translations, $languages);

        return redirect('admin/vacation');
    }

    function createTranslations(Vacation $vacation, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $vacation;
        $this->crud->setSaveAction();

        foreach ($translations as $translation) {
            $model_translation = new VacationTranslations();

            foreach ($languages as $language) {
                if ($translation['language_id'] == $language->id) {
                    $model_translation->language_id = data_get($translation, 'language_id');
                    $model_translation->name        = data_get($translation, "name");
                    $model_translation->summary     = data_get($translation, "summary");
                    $vacation->translations()->save($model_translation);
                }
            }
        }

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
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

        $vacation = $this->crud->update($request->get($this->crud->model->getKeyName()), $this->crud->getStrippedSaveRequest());

        $this->editTranslations($vacation, $translations, $languages);

        return $this->crud->performSaveAction($vacation->getKey());
    }

    function editTranslations($vacation, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $vacation;
        $this->crud->setSaveAction();

        $all_translations = $vacation->translations()->get();
        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == $language->id && data_get($translation, 'language_id') == $language->id) {
                        $one_translation->name        = data_get($translation, "name");
                        $one_translation->summary     = data_get($translation, "summary");
                        $one_translation->save();
                    }
                }
            }
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
    }
}
