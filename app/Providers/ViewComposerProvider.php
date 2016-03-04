<?php

namespace App\Providers;

use App\Category;
use App\Post;
use Illuminate\Support\ServiceProvider;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
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

        view()->composer('example.composer', function ($view) {
            $view->with('latestPosts',  Post::latest()->limit(6)->get());
        });
        view()->composer('frontend.nav', function ($view) {
            $view->with('categories',  Category::all());
        });

        view()->composer('frontend.aside', function ($view) {
            $view->with('recentPosts',  Post::all()->random(20));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
