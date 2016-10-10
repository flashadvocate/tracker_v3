<?php

namespace App\Providers;

use App\Squad;
use App\Member;
use App\Platoon;
use App\Division;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /**
         * Show division by abbreviation
         */
        \Route::bind('division', function ($division) {
            $model = Division::whereAbbreviation(strtolower($division))->first();
            if ($model instanceof Division) {
                return $model;
            }
        });

        \Route::bind('username', function ($username) {
            return User::whereName($username)->firstOrFail();
            ;
        });

        /**
         * Show platoon by division abbrev, platoon number (1st, 2nd, etc)
         */
        \Route::bind('platoon', function ($platoon) {
            $model = Platoon::whereId($platoon)->first();
            if ($model instanceof Platoon) {
                return $model;
            }
        });


        \Route::bind('squad', function ($squad) {
            $model = Squad::whereId($squad)->first();
            if ($model instanceof Squad) {
                return $model;
            }
        });

        /**
         * Show member by clan member id (forum id)
         */
        \Route::bind('member', function ($member) {
            $model = Member::whereClanId($member)->first();
            if ($model instanceof Member) {
                return $model;
            }
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapApiRoutes()
    {
        Route::group([
           'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }
}
