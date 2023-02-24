<?php

namespace AssistedMindfulness\Pwned;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class PwnedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('pwned', PwnedRule::class);

        Validator::replacer('pwned', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':min', array_shift($parameters) ?? 1, $message);
        });
    }
}
