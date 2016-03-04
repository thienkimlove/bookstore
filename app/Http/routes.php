<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function (\Illuminate\Http\Request $request) {

    if ($request->input('s')) {
        $page_title = 'Search with keyword : '.$request->input('s');
        $posts = \App\Post::latest()->where('title', 'LIKE', '%'.$request->input('s').'%')->paginate(20);
    } else {
        $page_title = 'New Books';
        $posts = \App\Post::latest()->limit(20)->get();
    }
    $randomPosts = \App\Post::all()->random(20);
    return view('frontend.index', compact('posts', 'randomPosts', 'page_title'))->with([
        'meta_title' => ($request->input('s'))? 'Search with keyword : '.$request->input('s') .' | '.env('SITE_NAME') : 'Free Download Medical Books'.' | '.env('SITE_NAME'),
        'meta_desc' => 'download free ebook, download ebooks, download free, ebook Pathology, ebook Pathophysiology, ebook, Physiology, ebook Histology, ebook Immunology, ebook Microbiology, download ebook anatomy, ebook Biochemistry, ebook Genetics,',
        'meta_url' => ($request->input('s')) ? url('/s='.$request->input('s')) : url('/')
    ]);
});    

// sitemap index
Route::get('feed', function()
{
    // create sitemap
    $sitemap = App::make("sitemap");

    // set cache
    $sitemap->setCache('laravel.sitemap-index', 3600);

    // add sitemaps (loc, lastmod (optional))
    $sitemap->addSitemap(url('sitemap-posts'));
    $sitemap->addSitemap(url('sitemap-categories'));

    // show sitemap
    return $sitemap->render('sitemapindex');
});        

// sitemap with posts
Route::get('sitemap-posts', function()
{
    // create sitemap
    $sitemap_posts = App::make("sitemap");

    // set cache
    $sitemap_posts->setCache('laravel.sitemap-posts', 3600);

    // add items
    $posts = \App\Post::all();

     foreach ($posts as $post)
     {
        $sitemap_posts->add(url($post->slug.'.html'), $post->updated_at, '0.9', 'daily');
     }

    // show sitemap
    return $sitemap_posts->render('xml');
});

// sitemap with posts
Route::get('sitemap-categories', function()
{
    // create sitemap
    $sitemap_categories = App::make("sitemap");

    // set cache
    $sitemap_categories->setCache('laravel.sitemap-categories', 3600);

    // add items
    $categories = \App\Category::all();

     foreach ($categories as $category)
     {
        $sitemap_categories->add(url($category->slug), $category->updated_at, '0.9', 'weekly');
     }

    // show sitemap
    return $sitemap_categories->render('xml');
});


Route::get('example/composer', function(){
    return view('example.composer');
});

Route::get('example/paginator', function(){
    $posts = \App\Post::paginate(1);
    //$posts->setPath('custom/url');
    return view('example.paginator', compact('posts'));
});

Route::get('restricted', function(){
    return view('errors.restricted');
});



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/admin', 'HomeController@index');
    Route::resource('admin/posts', 'PostsController');
    Route::resource('admin/categories', 'CategoriesController');
    Route::resource('admin/settings', 'SettingsController');
});


Route::get('{value}', function ($value) {
    if (preg_match('/([a-z0-9\-]+)\.html/', $value, $matches)) {

        $post = \App\Post::where('slug', $matches[1])->first();
        $relatedPosts =  \App\Post::where('status', true)
            ->where('category_id', $post->category->id)
            ->limit(20)->get();

        return view('frontend.detail', compact('post', 'relatedPosts'))->with([
            'meta_title' => $post->title . ' | '.env('SITE_NAME'),
            'meta_desc' => str_limit($post->desc, 155),
            'meta_url' => url($post->slug.'.html')
        ]);
    } else {

        $category = \App\Category::where('slug', $value)->first();

        $posts = \App\Post::where('status', true)
            ->where('category_id', $category->id)
            ->paginate(20);

        return view('frontend.category', compact(
            'category', 'page', 'topPosts', 'posts'
        ))->with([
            'meta_title' => $category->name.' | '.env('SITE_NAME'),
            'meta_desc' => $category->name,
            'meta_url' => url($category->slug)
        ]);
    }
});

