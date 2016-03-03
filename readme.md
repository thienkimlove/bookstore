#Adding Packages
## List packages adding more to basic laravel 5.2
 - barryvdh/laravel-ide-helper : using for generate helper for IDE coding (https://github.com/barryvdh/laravel-ide-helper)
 - laracasts/flash : using for Flash message (https://github.com/laracasts/flash)
 - intervention/image && intervention/imagecache : using for Image (http://image.intervention.io/getting_started/installation#laravel)
 - cviebrock/eloquent-sluggable : using for slug URL (https://github.com/cviebrock/eloquent-sluggable)
 - laravelcollective/html : using for HTML && Form (https://laravelcollective.com/docs/5.2/html)

##Javascript Packages :
###ckeditor and kcfinder
  Using for editor and upload image in editor to `public/upload`


#install
  ```
     git clone git@github.com:thienkimlove/vtv3.git && cd vtv3 && sh install.sh
  ```
  
## Go to /admin to start  
# Usually Case.

## Validation Form
- First we create `app/Http/PostRequest` by using artisan command `php artisan make:request PostRequest` and change as below :

```php
<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'tag_list' => 'required'
        ];
    }
}

```
- Now we can using `PostRequest` instead of `Request` in `store` and `update` function in `PostsController` :

```php
public function store(PostRequest $request)
{
  ...
}
```
## Sharing Data With All Views
You may need to share a piece of data with all views that are rendered by your application.

```php
  

<?php

namespace App\Providers;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share('key', 'value');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```
## ViewComposer
If you have data that you want to be bound to a view each time that view is rendered, a view composer can help you organize that logic into a single location.
[https://laravel.com/docs/5.2/views#view-composers] : https://laravel.com/docs/5.2/views#view-composers

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(
            'profile', 'App\Http\ViewComposers\ProfileComposer'
        );

        // Using Closure based composers...
        view()->composer('dashboard', function ($view) {
            //
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```
Go to ```example/composer``` for testing example.

## Upload And Browser Images.
We are using ```intervention/image``` and ```intervention/imagecache``` for working with images.

- First we add Html block below to ```resources/views/admin/posts/form.blade.php``` :
```php
<div class="form-group">
    {!! Form::label('image', 'Image') !!}
    @if ($post->image)
        <img src="{{url('img/cache/120x129/' . $post->image)}}" />
        <hr>
    @endif
    {!! Form::file('image', null, ['class' => 'form-control']) !!}
</div>
```
Please note that we using ```intervention/imagecache``` for image thumb url. To do that we config in  ```config\imagecache.php``` :

```php
 //prefix in url.
 'route' => 'img/cache',
 //path which we stored images.
 'paths' => array(
        public_path('files')
    ),
 //prefix which specific image size.   
 'templates' => array(
        ...
        '120x120' => function($image) {
            return $image->fit(120, 120);
        },
    ),
```
- Create AdminController with ```php artisan make:controller AdminController``` as below :

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Save images
     * @param $file
     * @param null $old
     * @return string
     */
    public function saveImage($file, $old = null)
    {
        $filename = md5(time()) . '.' . $file->getClientOriginalExtension();
        Image::make($file->getRealPath())->save(public_path('files/'. $filename));

        if ($old) {
            @unlink(public_path('files/' .$old));
        }
        return $filename;
    }
}
```
- change ```PostsController``` to extend `AdminController`
- In `PostsController@store` , we change the code as below :

```php
public function store(PostRequest $request)
{
    $data = $request->all();
    $data['image'] =  ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
    Post::create($data);
    flash('Create post success!', 'success');
    return redirect('admin/posts');
}
```
- In `PostsController@update`, we change as below :

```php
public function update($id, PostRequest $request)
{
    $data = $request->all();
    $post = Post::find($id);
    if ($request->file('image') && $request->file('image')->isValid()) {
        $data['image'] = $this->saveImage($request->file('image'), $post->image);
    }
    $post->update($data);
    flash('Update post success!', 'success');
    return redirect('admin/posts');
}
```
- In `PostsController@destroy` , we change code as below :

```php
$post = Post::find($id);          
if (file_exists(public_path('files/' . $post->image))) {
    @unlink(public_path('files/' . $post->image));
}
$post->delete();
```

## Implement Slug URL
We will using `cviebrock/eloquent-sluggable` package to make `slug` field auto update when `store` or `update` content.

For example, in table `posts`, we add a field `slug` in migration like below :

```php
$table->string('slug', env('POST_SLUG_URL_LENGTH'));
```

In `.env.stage` we already defined  `POST_SLUG_URL_LENGTH=200`.

After that, edit the `protected $fillable` In `app/Post.php` to :

```php
 protected $fillable = [
        ...
        'slug',
        ..
    ];
```

Next, in `app/Post.php` change as below :

```php
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = array(
        'build_from' => 'title',
        'save_to'    => 'slug',
        'unique'          => true,
        'on_update'       => true,
    );
```
Now the `slug` field will be slug-url of `title` field every post update or create.

## Implement Post Tags
-  Create Migration `php artisan make:migration create_tags_table`
- Edit the migration file as below :

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('tags', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug', 32)->unique();
            $table->timestamps();
        });
        Schema::create('post_tag', function(Blueprint $tale)
        {
            $tale->integer('post_id')->unsigned()->index();
            $tale->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $tale->integer('tag_id')->unsigned()->index();
            $tale->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('post_tag');
		Schema::drop('tags');
	}

}

```
- Run the migration and add those functions below to `App\Post` :

```php
    /**
     * like tags.
     * @param $query
     * @param $tag
     * @return mixed
     */
    public function scopeTagged($query, $tag)
    {
        if (strlen($tag) > 2) {
            $query->where('title', 'LIKE', '%'.$tag.'%');
        }
    }

    /**
     * get the tags that associated with given post
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * get the list tags of current post.
     * @return mixed
     */
    public function getTagListAttribute()
    {
        return $this->tags->lists('name')->all();
    }
```
- Run `php artisan make:model PostTag` and change as below :

```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model  {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_tag';

    /**
     * The timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

}
```
- `php artisan make:model Tag` and change as below :

```php
class Tag extends Model implements  SluggableInterface {
    use SluggableTrait;
    
        protected $sluggable = array(
            'build_from' => 'name',
            'save_to'    => 'slug',
            'unique'          => true,
            'on_update'       => true,
        );
        
    protected $fillable = ['name', 'slug'];

    /**
     * get the posts associated with tag
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post')           
           ->latest('updated_at')
           ->paginate(10);
    }
}    
```

We also using `SluggableTrait` in `Tag` Model to implement Slug URL:

```php
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model implements  SluggableInterface {

    use SluggableTrait;

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
        'unique'          => true,
        'on_update'       => true,
    ); 
```

- Change in `PostsController` :

```php
    public $tags;
    
    /**
     * PostsController constructor.    
     */
    public function __construct()
    {
        parent::__construct();
        $this->tags = Tag::lists('name', 'name')->all(); 
    }

    protected function syncTags($request, Post $post)
    {
        $tagIds = [];
        foreach ($request->input('tag_list') as $tag) {
            $tagIds[] = Tag::firstOrCreate(['name' => $tag])->id;
        }
        $post->tags()->sync($tagIds);
    }
    public function create()
    {
        $tags = $this->tags;
        return view('admin.post.form', compact('tags'));
    }
    
    public funtion store(PostRequest $request)
    {
      ...
      $this->syncTags($request, $post);
    }
    
    public function edit($id)
    {
        $post = Post::find($id);
        $tags = $this->tags;           
        return view('admin.post.form', compact('tags', 'post'));
    }
    public function update($id, PostRequest $request)
    {
       ...
       $this->syncTags($request, $post);
    }
       
    
```
- in `resources/views/admin/posts/form.blade.php` add :

```php
 <div class="form-group">
    {!! Form::label('tag_list', 'Tags') !!}
    {!! Form::select('tag_list[]', $tags, null, ['id' => 'tag_list', 'class' => 'form-control', 'multiple']) !!}
</div>

...

@section('footer')
    <script>
        $('#tag_list').select2({
            placeholder : 'choose or add new tag',
            tags : true //allow to add new tag which not in list.
        });
    </script>
@endsection
```

## Implement Categories
First we create category migration in `database/migrations` :

```php
class CreateCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('slug', env('CATEGORY_SLUG_URL_LENGTH'))->unique();
            $table->integer('parent_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }

}
``` 
Please note that `env('CATEGORY_SLUG_URL_LENGTH')` refer to  `CATEGORY_SLUG_URL_LENGTH` in `.env` file.

`Post` have a relationship with `Category` so we add in `Post` migration script :

```php
  $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
```
and in `app/Post.php` model :

```php
 /**
     * post belong to one category.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
```

Create the `Category` model with `php artisan make:model Category` and change to :

```php
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
        'unique'          => true,
        'on_update'       => true,
    );

    protected $fillable = [
        'name',
        'parent_id',
        'slug',
    ];

    /**
     * parent of this category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id', 'id');
    }

    /**
     * sub of this category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id');

    }

    /**
     * category have many posts.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post')->where('status', true);
    }
}

```

Next we add a route in `app/Http/routes.php` :

```php
Route::group(['middleware' => 'web'], function () {
    ...
    Route::resource('admin/categories', 'CategoriesController');
});
```
and make a controller with `php artisan make:controller CategoriesController`.

Please note that we using `app/Http/Requests/CategoryRequest` to define validation rules when create or update category :

```php
class CategoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
```

## Implement Pagination
- Link https://laravel.com/docs/5.2/pagination
- Using code below in `app/Http/routes.php` :

```php
Route::get('example/paginator', function(){
    $posts = \App\Post::paginate(1);
    //$posts->setPath('custom/url');
    return view('example.paginator', compact('posts'));
});
```
- View `resources/views/example/paginator` :
```php
@extends('layouts.app')
@section('content')
    <h1>
        Example View Composer
    </h1>
    @foreach ($posts as $post)
        <p>
            {{$post->title}}
        </p>
    @endforeach
    {!! $posts->links() !!}
@endsection
```

## Implement Settings

```php
php artisan make:migration create_table_settings --create=settings
php artisan make:model Setting
php artisan make:controller SettingsController
php artisan make:request SettingRequest
```
In `routes.php` add `Route::resource('admin/settings', 'SettingsController');`

Share `settings` with all views :

```php
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         //remove comment after install.
        //view()->share('site_settings', Setting::lists('value', 'name')->all());
    }
```

Test with add code below in `resource/views/example/composer.blade.php` and `paginator.blade.php` :

```html
 @if ($site_settings)
        @foreach ($site_settings as $key => $value)
            Seting key : {{$key}} --- Setting value : {{$value}}
        @endforeach
    @endif
```

Browser `<host>/example/paginator` && `<host>/example/composer` to see.

* Please note that when enter setting value is HTML code in form, click `Source` button in CKEditor to enter value, otherwise it will be encrypted.