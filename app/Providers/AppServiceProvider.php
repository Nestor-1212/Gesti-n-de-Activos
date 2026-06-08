<?php

namespace App\Providers;

use App\Events\EquipmentAssigned;
use App\Listeners\NotifyAssignment;
use App\Models\Assignment;
use App\Models\Collaborator;
use App\Models\Equipment;
use App\Models\User;
use App\Policies\AssignmentPolicy;
use App\Policies\CollaboratorPolicy;
use App\Policies\EquipmentPolicy;
use App\Policies\UserPolicy;
use App\Services\AssignmentService;
use App\Services\EquipmentService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind services as singletons — one instance per request lifecycle
        $this->app->singleton(EquipmentService::class);
        $this->app->singleton(AssignmentService::class);
    }

    public function boot(): void
    {
        $this->configureModels();
        $this->configurePolicies();
        $this->configureEvents();
        $this->configureRateLimiting();
    }

    private function configureModels(): void
    {
        // Prevent lazy loading (N+1) in non-production environments
        Model::preventLazyLoading(! app()->isProduction());

        // Prevent silently discarding un-fillable attributes
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
    }

    private function configurePolicies(): void
    {
        Gate::policy(Equipment::class, EquipmentPolicy::class);
        Gate::policy(Assignment::class, AssignmentPolicy::class);
        Gate::policy(Collaborator::class, CollaboratorPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }

    private function configureEvents(): void
    {
        Event::listen(EquipmentAssigned::class, NotifyAssignment::class);
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
        });

        RateLimiter::for('scanner', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?? $request->ip());
        });
    }
}
