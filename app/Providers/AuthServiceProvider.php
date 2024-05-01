<?php

namespace App\Providers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionParty;
use App\Models\File;
use App\Models\Vote;
use App\Policies\CandidatePolicy;
use App\Policies\ElectionPartyPolicy;
use App\Policies\ElectionPolicy;
use App\Policies\FilePolicy;
use App\Policies\VotePolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Election::class => ElectionPolicy::class,
        ElectionParty::class => ElectionPartyPolicy::class,
        Candidate::class => CandidatePolicy::class,
        File::class => FilePolicy::class,
        Vote::class => VotePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
