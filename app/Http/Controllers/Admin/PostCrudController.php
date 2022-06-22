<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use App\Services\Translit;
use App\Models\Language;
use App\Models\Post;
use App\Models\Translation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Image;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    public function fetchProducts()
    {
        return $this->fetch(\App\Models\Translation::class);
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
            'name'   => 'image',
            'label'  => 'Image',
            'type'   => 'image',
            'height' => '50px',
            'width'  => '50px',
            'custom'  => 'border-radius: 50%;',

        ]);
        CRUD::addColumn('category_id');
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Name',
            'type'      => 'relationship',
            'entity'    => 'translations',
            'attribute' => 'name',
        ]);
        CRUD::addColumn([
            'name'      => 'views',
            'label'     => 'Views',
            'type'      => 'relationship',
            'entity'    => 'views',
            'attribute' => 'post_id',
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
        CRUD::setValidation(PostRequest::class);

        CRUD::addField('user_id');
        CRUD::addField('category_id');
        CRUD::addField([
            'name'  => 'post_slug',
            'label' => "Slug",
            'type'  => 'text',
            'attributes' => [
                'readonly'  => 'readonly',
                'disabled'  => 'disabled',
            ]
        ]);
        CRUD::addField([
            'label'        => 'Image',
            'name'         => 'image',
            'type'         => 'image',
            'upload'       => true,
            // 'crop'         => true,
            'aspect_ratio' => 1,
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
                    'type'    => 'textarea',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                    'value'   => $translations->description ?? '',
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
                    'name'  => $language->name.'[summary]',
                    'type'  => 'related_ckeditor',
                    'label' => 'Summary',
                    'value' => $translations->summary ?? '',
                    'tab'   => $language->name,
                ],
                [
                    'name'  => $language->name.'[content]',
                    'type'  => 'related_ckeditor',
                    'label' => 'Content',
                    'value' => $translations->content ?? '',
                    'tab'   => $language->name,
                ],
            ]);
        }
        CRUD::addField([
            'name'  => 'readtime',
            'label' => 'Read time',
            'type'  => 'text',
        ]);
        CRUD::addField([
            'name'  => 'tags',
            'label' => 'Tags',
            'type'  => 'select2_multiple',
        ]);

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
        CRUD::addColumn('user_id');
        CRUD::addColumn('category_id');
        CRUD::addColumn([
            'name'  => 'post_slug',
            'label' => "Slug",
        ]);
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Name',
            'type'      => 'relationship',
            'entity'    => 'translations',
            'attribute' => 'name',
        ]);
        CRUD::addColumn([
            'name'   => 'image',
            'label'  => 'Image',
            'type'   => 'image',
            'height' => '50px',
            'width'  => '50px',
        ]);
        CRUD::addColumn('readtime');
        CRUD::addColumn('tags');
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
        CRUD::addColumn([
            'name'      => 'translations',
            'label'     => 'Content',
            'type'      => 'relationship',
            'key'       => 'translations_content',
            'entity'    => 'translations',
            'attribute' => 'content',
        ]);
        CRUD::addColumn([
            'name'      => 'views',
            'label'     => 'Views',
            'type'      => 'relationship',
            'entity'    => 'views',
            'attribute' => 'post_id',
        ]);
    }

    public function store(PostRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $this->crud->validateRequest();

        $languages = Language::all();
        $translations = [];
        foreach ($languages as $language) {
            $translations[] = $this->crud->getRequest()->input($language->name);
            $request->request->remove($language->name);
        }

        $base64 = $request->input('image');
        if (!empty($base64)) {
            $request->request->set('image', $base64);
            $this->crud->setRequest($request);
        }

        /** @var Post $post */
        $post = $this->crud->create($this->crud->getStrippedSaveRequest());

        if (!empty($base64)) {
            $this->setImageAttribute($post, $base64);
        }

        $this->createTranslations($post, $translations, $languages);

        return redirect('admin/post');
    }

    public function setImageAttribute(Post $post, $base64)
    {
        $attribute_name = "image";
        $disk = config('backpack.base.root_disk_name');
        $destination_path = "storage/images/$post->id";

        if (Str::startsWith($base64, 'data:image')) {
            $image = \Intervention\Image\Facades\Image::make($base64)->encode('jpg', 90);
            $filename = 'main-image.jpg';

            Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            $public_destination_path = Str::replaceFirst('storage/', '', $destination_path);
            DB::table(Post::TABLE)->where('id',$post->id)->update([$attribute_name => ($public_destination_path.'/'.$filename)]);
        }
    }

    function createTranslations(Post $post, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $post;
        $this->crud->setSaveAction();

        foreach ($translations as $translation) {
            $model_translation = new Translation;

            foreach ($languages as $language) {
                if ($translation['language_id'] == $language->id) {
                    $model_translation->language_id = data_get($translation, 'language_id');
                    $model_translation->title       = data_get($translation, "title");
                    $model_translation->description = data_get($translation, "description");
                    $model_translation->name        = data_get($translation, "name");
                    $model_translation->summary     = data_get($translation, "summary");
                    $model_translation->content     = data_get($translation, "content");
                    $post->translations()->save($model_translation);
                }
            }
        }

        foreach ($translations as $translation) {
            foreach ($languages as $language) {
                if ($translation['language_id'] == 1) {
                    $post->post_slug = strtolower(Translit::ru(data_get($translation, "name")));
                    $post->save();
                }
            }
        }

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();
//        dd($request);

        $languages = Language::all();
        foreach ($languages as $language) {
            $translations[] = $this->crud->getRequest()->input($language->name);
            $request->request->remove($language->name);
        }

        if (Str::startsWith($request->input('image'), 'data:image')) {
            $base64 = $request->input('image');
            $request->request->set('image', null);
            $this->crud->setRequest($request);
        }

        $post = $this->crud->update($request->get($this->crud->model->getKeyName()), $this->crud->getStrippedSaveRequest());

        if (!empty($base64)) {
            $this->setImageAttribute($post, $base64);
        }

        $this->editTranslations($post, $translations, $languages);

        return $this->crud->performSaveAction($post->getKey());
    }

    function editTranslations($post, $translations, $languages)
    {
        $this->data['entry'] = $this->crud->entry = $post;
        $this->crud->setSaveAction();

        $all_translations = $post->translations()->get();
        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == $language->id && data_get($translation, 'language_id') == $language->id) {
                        $one_translation->title       = data_get($translation, "title");
                        $one_translation->description = data_get($translation, "description");
                        $one_translation->name        = data_get($translation, "name");
                        $one_translation->summary     = data_get($translation, "summary");
                        $one_translation->content     = data_get($translation, "content");
                        $one_translation->save();
                    }
                }
            }
        }

        foreach ($all_translations as $one_translation) {
            foreach ($translations as $translation) {
                foreach ($languages as $language) {
                    if ($one_translation->language_id == $language->id && data_get($translation, 'language_id') == $language->id) {
                        $post->post_slug = strtolower(Translit::ru(data_get($translation, "name")));
                        $post->save();
                    }
                }
            }
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
    }
}
