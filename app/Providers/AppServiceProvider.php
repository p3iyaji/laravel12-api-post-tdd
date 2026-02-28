<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use OpenApi\Analysers\AttributeAnnotationFactory;
use OpenApi\Analysers\DocBlockAnnotationFactory;
use OpenApi\Analysers\ReflectionAnalyser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use both Attribute and DocBlock annotation factories so @OA\... docblocks are scanned
        Config::set('l5-swagger.defaults.scanOptions.analyser', new ReflectionAnalyser([
            new AttributeAnnotationFactory,
            new DocBlockAnnotationFactory,
        ]));
    }
}
