# embryoFresh
A tailored version of Laravel + Backpack intended to be a starter for projects.

## Installation

1. Clone or download the repo
2. Install dependencies: `composer install`
3. ENV management
    1. Copy the `.env.example` file to `.env`
    2. `php artisan key:generate`
    3. Adapt the database information in the file
4. Install backpack's dependencies, including AdminLTE public dir
    1. `php artisan backpack:base:install`
    2. `php artisan backpack:crud:install`
5. Optional: if you don't want to use the public authentication files of Laravel, you can delete this directory :
`app/Http/Controllers/Auth`
    
## Usage

### Create a backpack CRUD

1. Create the migration on your own or with the following
    ```
    php artisan make:migration:schema create_tags_table --model=0 --schema="name:string:unique"
    php artisan migrate
    ```
2. Create the model, the route and the sidebar item (Use the singular for the model's name)
    ```
    php artisan make:crud modelName
    ```
3. Edit the sidebar to update the icon and the title, if needed

   (The file is located here: `resources/views/vendor/backpack/base/inc/sidebar_content.blade.php`)

### Managing images on admin panel

1. Into the model file:
    1. The model should already use the CRUD trait
    2. add the ImageTrait and UploadImageTrait, like this:
        ```php
            class YourClass extends Model
            {
                use CrudTrait, ImageTrait, UploadImageTrait;
        ```
    3. Add these two constants to the model :
        1. `const ADMIN_PATH_ATTRIBUTE = 'path';` (It's the database field where the image information is stored)
        2. `const IMAGE_DIR            = 'directory';` (It's the directory in the `storage/app` folder where the files will be uploaded)
    3. Add this method to the model's mutator, matching the field's name:
        ```php
            public function setPathAttribute($value)
            {
                $this->uploadImage($value, self::ADMIN_PATH_ATTRIBUTE);
            }
        ```
        The `setXXXAttribute` should be adapted to your database field's name.
2. Into the admin CRUD file:
    1. For the column view (list page):
        ```php
                $this->crud->addColumn(
                    [
                        'name'          => 'your_database_field',
                        'label'         => 'whatever you want the column to be displayed as',
                        'type'          => 'model_function',
                        'function_name' => 'getAdminThumbnail',
                        'limit'         => -1,
                    ]
                );
        ```
        The `model_function` type allows the usage of any method of the model, this method being `getAdminThumbnail`,
    defined in `UploadImageTrait`.
    The limit set at `-1` allows to display all the characters from the function without trimming.
    2. For the field view (create/edit page):
        ```php
           $this->crud->addField([
               'name'   => 'path',
               'type'   => 'image',
               'label'  => 'Image',
               'prefix' => 'adminImage/your_model_name/preview/',
           ]);
        ```
        
        The prefix is the one defined on the route file (`routes/web.php`), its path should be updated so 
        `your_model_name` matches your model's name, in snake_case (`FooBar` becomes `foo_bar`).
        
        The display of the image is now managed by the ImageController.

### Transformers and Collections

In order to generate displayable data for the templates, Objects need to be transformed to arrays. EmbryoFresh provides
a simple way to generate them for objects or even collections.

1. Create the transformer file : `php artisan make:transformer MyObjectTransformer`
2. Adapt its contents :
    1. The `transform()` method must have one of your models as an argument
    2. Adapt the return so it displays only what is needed
3. The call depends on whether you use one single object or an eloquent collection
    1. For a single "MyObject" model, here's an example:
        ```php
            $transformer   = new \App\Http\Transformers\MyObjectTransformer();
            var_dump($transformer->transform($myObject));
        ```
    2. For an eloquent collection, here's the walkthrough:
        1. use the `\App\Http\Traits\CollectionTrait` on your controller
        2. here's an example of code:
            ```php
               var_dump(self::getCollectionAsArrayWithoutData($myObjectsCollection, new \App\Http\Transformers\MyObjectTransformer()));
            ```
        3. The default behaviour of [Fractal](https://fractal.thephpleague.com/) adds a `data` key in order to
        potentially add metadata to the array. The `getCollectionAsArray` method returns the objects contents in the
        `data` key in the array.
        To return only the results, you should use the `getCollectionAsArrayWithoutData` method.