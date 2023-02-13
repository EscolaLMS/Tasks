<?php

namespace EscolaLms\Tasks\Tests;

use Carbon\Carbon;
use EscolaLms\Core\Models\User;
use Faker\Factory;
use Illuminate\Support\Str;

trait TaskTesting
{
    public function userCreationPayload(?array $data = []): array
    {
        $faker = Factory::create();
        $type = Str::ucfirst($faker->word) . $faker->numberBetween();

        $payload = [
            'title' => $faker->word,
            'due_date' => Carbon::now()->addDay(),
            'related_type' => 'EscolaLms\\' . $type . '\\Models\\' . $type,
            'related_id' => $faker->randomNumber(),
        ];

        return array_merge($payload, $data);
    }

    public function adminCreationPayload(?array $data = []): array
    {
        $faker = Factory::create();
        $type = Str::ucfirst($faker->word) . $faker->numberBetween();

        $payload = [
            'title' => $faker->word,
            'note' => $faker->word,
            'user_id' => User::factory()->create()->getKey(),
            'due_date' => Carbon::now()->addDay(),
            'related_type' => 'EscolaLms\\' . $type . '\\Models\\' . $type,
            'related_id' => $faker->randomNumber(),
        ];

        return array_merge($payload, $data);
    }

    private function prepareUri(string $prefix, array $filters): string {
        $uri = $prefix . '?';

        foreach ($filters as $key => $value) {
            $uri .= $key . '=' . $value . '&';
        }

        return $uri;
    }
}
