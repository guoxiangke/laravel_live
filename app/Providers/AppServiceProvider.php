<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Providers\TelescopeServiceProvider;
use App\User;
use App\Models\Live;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            // $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->isLocal()) {
            URL::forceScheme('https');
        }
        // $this->bootEloquentMorphs();
    }



    // https://learnku.com/articles/5375/describes-the-polymorphic-association-polymorphic-relations-in-the-eloquent-association
    /**
     * 自定义多态关联的类型字段
     */
    private function bootEloquentMorphs()
    {
        // with(new User)->getTable(); //users
        // https://laravel.com/docs/6.x/eloquent-relationships#custom-polymorphic-types
        $map = [
            with(new User)->getTable() => User::class,
            with(new Live)->getTable() => Live::class,
            with(new Message)->getTable() => Message::class,
        ];
        Relation::morphMap($map);
    }
}
