<?php

namespace Bishopm\Church\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Bishopm\Church\Church;
use Bishopm\Church\Livewire\BookReview;
use Bishopm\Church\Livewire\LoginForm;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ChurchServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'church');
        Paginator::useBootstrapFive();
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
        Config::set('app.name',setting('general.church_abbreviation'));
        Config::set('auth.providers.users.model','Bishopm\Church\Models\User');
        Config::set('mail.default',setting('email.mailer'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.host',setting('email.mail_host'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.port',setting('email.mail_port'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.username',setting('email.mail_username'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.password',setting('email.mail_password'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.encryption',setting('email.mail_encryption'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.from_address',setting('email.mail_from_address'));
        Config::set('mail.mailers.' . setting('email.mailer') . '.from_name',setting('email.mail_from_name'));
        Config::set('filament-spatie-roles-permissions.clusters.permissions',\Bishopm\Church\Filament\Clusters\Settings::class);
        Config::set('filament-spatie-roles-permissions.clusters.roles',\Bishopm\Church\Filament\Clusters\Settings::class);
        Config::set('filament-spatie-roles-permissions.scope_to_tenant',false);
        Config::set('filament-spatie-roles-permissions.should_redirect_to_index.roles.after_edit',true);
        Config::set('filament-spatie-roles-permissions.should_redirect_to_index.roles.after_create',true);
        Config::set('filament-spatie-roles-permissions.should_redirect_to_index.permissions.after_edit',true);
        Config::set('filament-spatie-roles-permissions.should_redirect_to_index.permissions.after_create',true);
        Config::set('filament-spatie-roles-permissions.guard_names',['web'=>'web']);
        Config::set('filament-spatie-roles-permissions.default_guard_name','web');
        Config::set('filament-spatie-roles-permissions.generator.guard_names',['web'=>'web']);
        Config::set('filament-spatie-roles-permissions.generator.model_directories',[base_path('vendor/bishopm/church/src/Models')]);
        Config::set('filament-spatie-roles-permissions.generator.user_model', \Bishopm\Church\Models\User::class);
        Config::set('filament-spatie-roles-permissions.generator.policies_namespace','Bishopm\Church\Filament\Policies');
        Livewire::component('login', LoginForm::class);
        Livewire::component('bookreview', BookReview::class);
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        Blade::componentNamespace('Bishopm\\Church\\Resources\\Views\\Components', 'church');
        Relation::morphMap([
            'book' => 'Bishopm\Church\Models\Book',
            'sermon' => 'Bishopm\Church\Models\Sermon',
            'prayer' => 'Bishopm\Church\Models\Prayer',
            'post' => 'Bishopm\Church\Models\Post',
            'song' => 'Bishopm\Church\Models\Song',
            'group' => 'Bishopm\Church\Models\Group',
            'event' => 'Bishopm\Church\Models\Event',
            'tenant' => 'Bishopm\Church\Models\Tenant',
            'household' => 'Bishopm\Church\Models\Household',
            'individual' => 'Bishopm\Church\Models\Individual',
            'pastoralcase' => 'Bishopm\Church\Models\Pastoralcase'
        ]);
        Gate::policy(Role::class, \Bishopm\Church\Filament\Policies\RolePolicy::class);
        Gate::policy(Permission::class, \Bishopm\Church\Filament\Policies\PermissionPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Individual::class, \Bishopm\Church\Filament\Policies\IndividualPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Household::class, \Bishopm\Church\Filament\Policies\HouseholdPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Group::class, \Bishopm\Church\Filament\Policies\GroupPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Roster::class, \Bishopm\Church\Filament\Policies\RosterPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Person::class, \Bishopm\Church\Filament\Policies\PersonPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Statistic::class, \Bishopm\Church\Filament\Policies\StatisticPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Meeting::class, \Bishopm\Church\Filament\Policies\MeetingPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Task::class, \Bishopm\Church\Filament\Policies\TaskPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Gift::class, \Bishopm\Church\Filament\Policies\GiftPolicy::class);
        Gate::policy(\Bishopm\Church\Models\Employee::class, \Bishopm\Church\Filament\Policies\EmployeePolicy::class);
        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true: null;     
        });
        $member=array();
        if (isset($_COOKIE['wmc-mobile']) and (isset($_COOKIE['wmc-access']))){
            $phone=$_COOKIE['wmc-mobile'];
            $uid=$_COOKIE['wmc-access'];
            $indiv=Individual::where('cellphone',$phone)->where('uid',$uid)->first();
            if ($indiv){
                $member['id']=$indiv->id;
                $member['firstname']=$indiv->firstname;
                $member['fullname']=$indiv->fullname;
            }
            Config::set('member',$member);
        }
        View::share('member',$member);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/church.php', 'church');
        $this->app->singleton('church', function ($app) {
            return new Church;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['church'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../../config/church.php' => config_path('church.php'),
        ], 'church.config');

        // Publishing the views.
        // $this->publishes([
        //    __DIR__.'/../Resources' => public_path('vendor/bishopm'),
        // ], 'church.views');

        // Publishes assets.
        $this->publishes([
            __DIR__.'/../Resources/assets' => public_path('church'),
          ], 'assets');
        

        // Registering package commands.
        $this->commands([
            'Bishopm\Church\Console\Commands\InstallChurch'
        ]);
    }
}
