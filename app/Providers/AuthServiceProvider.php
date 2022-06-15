<?php

namespace App\Providers;

use App\Models\Genre;
use App\Models\Product;
use App\Models\Developer;
use App\Models\Publisher;
use App\Policies\GenrePolicy;
use App\Policies\ProductPolicy;
use App\Policies\DeveloperPolicy;
use App\Policies\PublisherPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Developer::class => DeveloperPolicy::class,
        Product::class => ProductPolicy::class,
        Genre::class => GenrePolicy::class,
        Publisher::class => PublisherPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
