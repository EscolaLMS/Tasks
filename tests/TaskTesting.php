<?php

namespace EscolaLms\Tasks\Tests;

use Carbon\Carbon;
use EscolaLms\Core\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

trait TaskTesting
{
    use WithFaker;

    public function userCreationPayload(?array $data = []): array
    {
        $type = Str::ucfirst($this->faker->word) . $this->faker->numberBetween();

        $payload = [
            'title' => $this->faker->word,
            'due_date' => Carbon::now()->addDay(),
            'related_type' => 'EscolaLms\\' . $type . '\\Models\\' . $type,
            'related_id' => $this->faker->randomNumber(),
        ];

        return array_merge($payload, $data);
    }

    public function adminCreationPayload(?array $data = []): array
    {
        $type = Str::ucfirst($this->faker->word) . $this->faker->numberBetween();

        $payload = [
            'title' => $this->faker->word,
            'note' => $this->faker->word,
            'user_id' => User::factory()->create(['email' => $this->faker->email . Carbon::now()->getTimestamp()])->getKey(),
            'due_date' => Carbon::now()->addDay(),
            'related_type' => 'EscolaLms\\' . $type . '\\Models\\' . $type,
            'related_id' => $this->faker->randomNumber(),
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
