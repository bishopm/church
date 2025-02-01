<?php namespace Bishopm\Church\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Bishopm\Church\Church;
use Bishopm\Church\Http\Middleware\AdminRoute;
use Bishopm\Church\Livewire\BarcodeScanner;
use Bishopm\Church\Livewire\BookReview;
use Bishopm\Church\Livewire\LoginForm;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
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
        $router = $this->app['router'];
        $router->aliasMiddleware('adminonly', AdminRoute::class);
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
        Config::set('filesystems.disks.google.driver','google');
        Config::set('filesystems.disks.google.clientId',setting('services.drive_clientid'));
        Config::set('filesystems.disks.google.clientSecret',setting('services.drive_clientsecret'));
        Config::set('filesystems.disks.google.refreshToken',setting('services.drive_refreshtoken'));
        Config::set('filesystems.disks.google.folder','');
        Livewire::component('login', LoginForm::class);
        Livewire::component('bookreview', BookReview::class);
        Livewire::component('barcodescanner', BarcodeScanner::class);
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
            $pastor = Pastor::where('individual_id',$member['id'])->first();
            if ($pastor){
                $member['pastor_id']=$pastor->id;
            }
            Config::set('member',$member);
        }
        View::share('member',$member);
        if (env('APP_ENV')=="local"){
            $this->publishes([
                __DIR__.'/../Resources/pwa/local_manifest.json' => public_path('manifest.json'),
                __DIR__.'/../Resources/pwa/local_serviceworker.js' => public_path('serviceworker.js'),
            ]);
        } else {
            $this->publishes([
                __DIR__.'/../Resources/pwa/manifest.json' => public_path('manifest.json'),
                __DIR__.'/../Resources/pwa/serviceworker.js' => public_path('serviceworker.js'),
            ]);
        }
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('queue:work --tries=3 --stop-when-empty')->withoutOverlapping();
            $schedule->command('church:birthdayemail')->weeklyOn(intval(setting('automation.birthday_day')), '6:30');
            $schedule->command('church:maintenanceemail')->weeklyOn(intval(setting('automation.maintenance_day')), '6:00');
            $schedule->command('church:monthlymeasures')->monthlyOn(1, '5:30');
            // $schedule->command('church:givingemail')->dailyAt('9:00');
            $schedule->command('church:recurringtasks')->dailyAt('5:00');
        });
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
            'Bishopm\Church\Console\Commands\BirthdayEmail',
            'Bishopm\Church\Console\Commands\InstallChurch',
            'Bishopm\Church\Console\Commands\MaintenanceEmail',
            'Bishopm\Church\Console\Commands\MonthlyMeasures',
            'Bishopm\Church\Console\Commands\RecurringTasks'
        ]);
    }
}
