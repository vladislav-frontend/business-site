<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use App\Models\User;
use App\Models\Profile;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
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
            'name'     => 'image',
            'label'    => 'Image',
            'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->profile) return '<img style="width: 50px; border-radius: 50%;" src="' . url('/') . '/' . $entry->profile->image . '">';
            }
        ]);
        CRUD::column('name');
        CRUD::column('email');
        CRUD::addColumn([
            'name'      => 'profile',
            'label'     => 'Position',
            'type'      => 'relationship',
            'key'       => 'profile_position',
            'entity'    => 'profile',
            'attribute' => 'position',
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
        CRUD::setValidation(UserRequest::class);

        if (request()->id) $profile = User::find(request()->id)->profile;

        CRUD::field('name');
        CRUD::field('email');
        CRUD::field('password');
        CRUD::addField([
            'label'        => 'Image',
            'name'         => 'image',
            'type'         => 'image',
            'upload'       => true,
            'crop'         => true,
            'aspect_ratio' => 1,
            'value'        => $profile->image ?? ''
        ]);
        CRUD::addField([
            'name'          => 'position',
            'label'         => 'Position',
            'type'          => 'select_from_array',
            'options'       => [
                'Backend Developer'  => 'Backend Developer',
                'Frontend Developer' => 'Frontend Developer',
                'SEO'                => 'SEO'
            ],
            'allows_null'   => false,
            'value'         => $profile->position ?? ''
        ]);
        CRUD::addField([
            'name'    => 'contacts',
            'label'   => 'Contacts',
            'type'    => 'textarea',
            'value'   => $profile->contacts ?? ''
        ]);
        CRUD::addField([
            'name'    => 'description',
            'label'   => 'Description',
            'type'    => 'textarea',
            'value'   => $profile->description ?? ''
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
        CRUD::column('name');
        CRUD::column('email');
        CRUD::addColumn([
            'name'     => 'image',
            'label'    => 'Image',
            'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->profile) return '<img width="50px" src="' . url('/') . '/' . $entry->profile->image . '">';
            }
        ]);
        CRUD::addColumn([
            'name'      => 'profile',
            'label'     => 'Position',
            'type'      => 'relationship',
            'key'       => 'profile_position',
            'entity'    => 'profile',
            'attribute' => 'position',
        ]);
        CRUD::addColumn([
            'name'      => 'profile',
            'label'     => 'Contacts',
            'type'      => 'relationship',
            'key'       => 'profile_contacts',
            'entity'    => 'profile',
            'attribute' => 'contacts',
        ]);
        CRUD::addColumn([
            'name'      => 'profile',
            'label'     => 'Description',
            'type'      => 'relationship',
            'key'       => 'profile_description',
            'entity'    => 'profile',
            'attribute' => 'description',
        ]);
    }

    public function store(UserRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $this->crud->validateRequest();

        $base64 = $request->input('image');
        if (!empty($base64)) {
            $request->request->set('image', $base64);
            $this->crud->setRequest($request);
        }

        /** @var User $user */
        $user = $this->crud->create($this->crud->getStrippedSaveRequest());

        $this->data['entry'] = $this->crud->entry = $user;
        $this->crud->setSaveAction();

        $user->password = bcrypt($request->password);
        $user->save();

        $profile = new Profile;
        $profile->user_id      = $user->id;
        $profile->position     = $this->crud->getRequest()->input('position');
        $profile->contacts     = $this->crud->getRequest()->input('contacts');
        $profile->description  = $this->crud->getRequest()->input('description');
        $profile->save();

        if (!empty($base64)) {
            $this->setImageAttribute($user, $base64);
        }

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        return redirect('admin/user');
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();
        $data = $this->crud->getStrippedSaveRequest();

        if (empty($data['password'])) $data['password'] = User::query()->find($request->get($this->crud->model->getKeyName()))->first()->password;
        $user = $this->crud->update($request->get($this->crud->model->getKeyName()), $data);

        $this->data['entry'] = $this->crud->entry = $user;
        $this->crud->setSaveAction();

        if (Str::startsWith($request->input('image'), 'data:image')) {
            $base64 = $request->input('image');
            $request->request->set('image', null);
            $this->crud->setRequest($request);
        }

        if (!empty($base64)) {
            $this->setImageAttribute($user, $base64);
        }

        $user->profile ? $profile = $user->profile : $profile = new Profile;
        $profile->user_id      = $user->id;
        $profile->position     = $this->crud->getRequest()->input('position');
        $profile->contacts     = $this->crud->getRequest()->input('contacts');
        $profile->description  = $this->crud->getRequest()->input('description');
        $profile->save();

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->performSaveAction($user->getKey());
        return redirect('admin/user');
    }

    public function setImageAttribute(User $user, $base64)
    {
        $attribute_name = "image";
        $disk = config('backpack.base.root_disk_name');
        $destination_path = "storage/images/users/$user->id";

        if (Str::startsWith($base64, 'data:image')) {
            $image = \Intervention\Image\Facades\Image::make($base64)->encode('jpg', 90);
            $filename = 'avatar.jpg';

            Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            $public_destination_path = Str::replaceFirst('storage/', '', $destination_path);
            DB::table(Profile::TABLE)->where('user_id', $user->id)->update([$attribute_name => ($public_destination_path.'/'.$filename)]);
        }
    }
}
