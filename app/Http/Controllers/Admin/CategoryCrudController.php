<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Language;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Services\Translit;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
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
        CRUD::setValidation(CategoryRequest::class);

        CRUD::addField([
            'name'  => 'category_slug',
            'label' => "Slug",
            'type'  => 'text',
            'attributes' => [
                'readonly'  => 'readonly',
                'disabled'  => 'disabled',
            ]
        ]);

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
            ]);
        }

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name'  => 'category_slug',
            'label' => "Slug",
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Name',
            'type'      => 'relationship',
            'entity'    => 'translations',
            'attribute' => 'name',
        ]);
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

    public function store(CategoryRequest $request)
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

        /** @var Category $category */
        $category = $this->crud->create($this->crud->getStrippedSaveRequest());

        $this->createTranslations($category, $translations, $languages);

        return redirect('admin/category');
    }

    function createTranslations(Category $category, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $category;
        $this->crud->setSaveAction();

        foreach ($translations as $translation) {
            $model_translation = new CategoryTranslation;

            foreach ($languages as $language) {
                if ($translation['language_id'] == $language->id) {
                    $model_translation->language_id = data_get($translation, 'language_id');
                    $model_translation->name        = data_get($translation, "name");
                    $category->translations()->save($model_translation);
                }
            }
        }

        foreach ($translations as $translation) {
            foreach ($languages as $language) {
                if ($translation['language_id'] == 1) {
                    $category->category_slug = strtolower(Translit::ru(data_get($translation, "name")));
                    $category->save();
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

        $category = $this->crud->update($request->get($this->crud->model->getKeyName()), $this->crud->getStrippedSaveRequest());

        $this->editTranslations($category, $translations, $languages);

        return $this->crud->performSaveAction($category->getKey());
    }

    function editTranslations($category, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $category;
        $this->crud->setSaveAction();

        $all_translations = $category->translations()->get();
        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == $language->id && data_get($translation, 'language_id') == $language->id) {
                        $one_translation->name        = data_get($translation, "name");
                        $one_translation->save();
                    }
                }
            }
        }

        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == 1 && data_get($translation, 'language_id') == 1) {
                        $category->category_slug = strtolower(Translit::ru(data_get($translation, "name")));
                        $category->save();
                    }
                }
            }
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
    }
}
