# embryoFresh
A tailored version of Laravel + Backpack intended to be a starter for projects.

## Installation

1. Clone or download the repo
2. Install dependencies: `composer install`
3. ENV management
    1. Copy the `.env.example` file to `.env`
    2. `php artisan key:generate`
    3. Adapt the database information in the file
3. Install backpack's dependencies, including AdminLTE public dir
    1. `php artisan backpack:base:install`
    2. `php artisan backpack:crud:install`
    
## Usage

### Create a backpack CRUD

1. Create the migration on your own or with the following
    ```
    php artisan make:migration:schema create_tags_table --model=0 --schema="name:string:unique"
    php artisan migrate
    ```
2. Create the model: (Use the singular version)
    ```
    php artisan backpack:crud tag
    ```
3. Create the route: 
    ```
    php artisan backpack:base:add-custom-route "CRUD::resource('tag', 'TagCrudController');"
    ```
4. Create the sidebar item: 
    ```
    php artisan backpack:base:add-sidebar-content "<li><a href='{{ backpack_url('tag') }}'><i class='fa fa-tag'></i> <span>Tags</span></a></li>"
    ```
    
### Managing images on admin panel

1. Into the model file:
    1. The model should already use the CRUD trait
    2. add the ImageTrait and UploadImageTrait, like this:
        ```php
            class YourClass extends Model
            {
                use CrudTrait, ImageTrait, UploadImageTrait;
        ```
    3. Add this method to the model's mutator, matching the field's name:
        ```php
            public function setPathAttribute($value)
            {
                $this->uploadImage($value, 'path');
            }
        ```
        The `setXXXAttribute` and the methods second argument should be adapted to your database field's name.
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
