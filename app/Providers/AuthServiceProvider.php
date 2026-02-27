<?php

namespace App\Providers;

use App\Models\Member;
use App\Policies\MemberPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [Member::class => MemberPolicy::class];

    public function boot(): void
    {
    }
}
