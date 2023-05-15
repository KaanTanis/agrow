<?php

namespace App\Providers;

use App\Models\Post;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Health::checks([
            ScheduleCheck::new(),
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            CacheCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            OptimizedAppCheck::new(),
            CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
            DatabaseConnectionCountCheck::new()
                ->failWhenMoreConnectionsThan(100),
            DatabaseTableSizeCheck::new()
                ->table('posts', maxSizeInMb: 1_000),
            PingCheck::new()->url('https://google.com'),
            QueueCheck::new(),
        ]);

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Developer')
                    ->collapsible()
                    ->collapsed()
            ]);

            Filament::registerNavigationItems([
                NavigationItem::make(__('Ana Sayfa Alanları'))
                    ->icon('heroicon-o-adjustments')
                    ->url(route('filament.resources.home-field.edit', 1))
                    ->isActiveWhen(fn () => request()->routeIs('filament.resources.home-field.edit')),

                NavigationItem::make(__('Site Ayarları'))
                    ->icon('heroicon-o-cog')
                    ->url(route('filament.resources.settings.edit', 2))
                    ->isActiveWhen(fn () => request()->routeIs('filament.resources.settings.edit'))
            ]);

            Filament::registerStyles([
                asset('filament/assets/css/leaflet.css'),
                asset('filament/assets/css/geosearch.css'),
            ]);

            Filament::registerScripts([
                asset('filament/assets/js/leaflet.js'),
                asset('filament/assets/js/geosearch.umd.js'),
            ], true);
        });


        if (Schema::hasTable('posts')) {
            view()->share('settings', Post::query()->where('id', 2)->first());
            view()->share('homeField', Post::query()->where('id', 1)->first());
        }
    }
}
