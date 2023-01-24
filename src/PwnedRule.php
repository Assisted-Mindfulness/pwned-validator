<?php

namespace AssistedMindfulness\Pwned;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class PwnedRule implements Rule
{
    /** @var int */
    private int $minimum;

    /**
     * @param int $minimum Minimum number of times the password was pwned before it is blocked
     */
    public function __construct(int $minimum = 1)
    {
        $this->minimum = $minimum;
    }

    public function validate($attribute, $value, $params): bool
    {
        $this->minimum = $params[0] ?? 1;

        return $this->passes($attribute, $value);
    }

    public function passes($attribute, $value): bool
    {
        [$prefix, $suffix] = $this->hashAndSplit($value);

        $count = $this->query($prefix)->get($suffix, 0);

        return $count < $this->minimum;
    }

    public function message()
    {
        return Lang::get('validation.pwned');
    }

    private function hashAndSplit(string $value): array
    {
        $hash = Str::of(sha1($value))->upper();

        return [
            $hash->substr(0, 5)->toString(), // prefix
            $hash->substr(5)->toString(), // suffix
        ];
    }

    /**
     * @param $prefix
     *
     * @return \Illuminate\Support\Collection
     */
    private function queryApi($prefix): Collection
    {
        $response = Http::withHeaders(['Add-Padding' => 'true'])
            ->get('https://api.pwnedpasswords.com/range/'.$prefix);

        if ($response->failed()) {
            return collect();
        }

        return Str::of($response->body())
            ->trim()
            ->explode("\r\n")
            ->mapWithKeys(function ($value) {
                $pair = Str::of($value)->trim()->explode(':');

                return [
                    $pair->first() => $pair->last(),
                ];
            });
    }

    /**
     * Cache results for a week, to avoid constant API calls for identical prefixes
     *
     * @param $prefix
     *
     * @return \Illuminate\Support\Collection
     */
    private function query($prefix): Collection
    {
        return Cache::remember('pwned:'.$prefix, now()->addWeek(), fn () => $this->queryApi($prefix));
    }
}
