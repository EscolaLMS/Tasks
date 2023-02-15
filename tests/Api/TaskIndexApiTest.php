<?php

namespace EscolaLms\Tasks\Tests\Api;

use EscolaLms\Tasks\Database\Seeders\TaskPermissionSeeder;
use EscolaLms\Tasks\Models\Task;
use EscolaLms\Tasks\Tests\CreatesUsers;
use EscolaLms\Tasks\Tests\TaskTesting;
use EscolaLms\Tasks\Tests\TestCase;

class TaskIndexApiTest extends TestCase
{
    use TaskTesting, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TaskPermissionSeeder::class);
    }

    /**
     * @dataProvider userFilterDataProvider
     */
    public function testUserTaskIndexFiltering(array $filters, callable $generator, int $filterCount): void
    {
        $user = $this->makeStudent();
        $generator($user->getKey())->each(fn($factory) => $factory->create());

        $this->actingAs($user, 'api')
            ->getJson($this->prepareUri('api/tasks', $filters))
            ->assertOk()
            ->assertJsonCount($filterCount, 'data')
            ->assertJsonStructure(['data' => [[
                'id',
                'title',
                'created_by_id',
                'completed_at',
                'related_type',
                'related_id',
            ]]]);
    }

    public function userFilterDataProvider(): array
    {
        return [
            [
                'filter' => [
                    'title' => 'Test',
                ],
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['title' => 'Test']));
                    $tasks->push(Task::factory()->state(['title' => 'Test', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['title' => 'Test 123', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['title' => 'Test 123 456', 'user_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 3
            ],
            [
                'filter' => [
                    'related_type' => 'EscolaLms\\Courses\\Models\\Topic',
                ],
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Course', 'user_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 2
            ],
            [
                'filter' => [
                    'related_type' => 'EscolaLms\\Courses\\Models\\Topic',
                    'related_id' => 123,
                ],
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'related_id' => 123, 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Course', 'user_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 1
            ]
        ];
    }

    /**
     * @dataProvider adminFilterDataProvider
     */
    public function testAdminTaskIndexFiltering(callable $filters, callable $generator, int $filterCount): void
    {
        $user = $this->makeUser();
        $admin = $this->makeAdmin();
        $generator($user->getKey())->each(fn($factory) => $factory->create());

        $this->actingAs($admin, 'api')
            ->getJson($this->prepareUri('api/admin/tasks', $filters($user->getKey())))
            ->assertOk()
            ->assertJsonCount($filterCount, 'data')
            ->assertJsonStructure(['data' => [[
                'id',
                'title',
                'created_by_id',
                'completed_at',
                'related_type',
                'related_id',
            ]]]);
    }

    public function adminFilterDataProvider(): array
    {
        return [
            [
                'filter' => (function($params) {
                    return [
                        'title' => 'Test',
                    ];
                }),
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['title' => 'Test']));
                    $tasks->push(Task::factory()->state(['title' => 'Test', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['title' => 'Test 123']));
                    $tasks->push(Task::factory()->state(['title' => 'Test 123 456', 'user_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 4
            ],
            [
                'filter' => (function($params) {
                    return [
                        'related_type' => 'EscolaLms\\Courses\\Models\\Topic',
                    ];
                }),
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic']));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Course', 'user_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 2
            ],
            [
                'filter' => (function($params) {
                    return [
                        'related_type' => 'EscolaLms\\Courses\\Models\\Topic',
                        'related_id' => 123,
                    ];
                }),
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'related_id' => 123]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'related_id' => 123, 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Topic', 'user_id' => $userId]));
                    $tasks->push(Task::factory()->state(['related_type' => 'EscolaLms\\Courses\\Models\\Course', 'user_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 2
            ],
            [
                'filter' => (function($params) {
                    return [
                        'user_id' => $params
                    ];
                }),
                'data' => (function(int $userId) {
                    $data = collect();

                    $data->push(Task::factory());
                    $data->push(Task::factory());
                    $data->push(Task::factory()->state(['user_id' => $userId]));
                    $data->push(Task::factory()->state(['user_id' => $userId]));
                    $data->push(Task::factory()->state(['user_id' => $userId]));

                    return $data;
                }),
                'filterCount' => 3
            ],
            [
                'filter' => (function($params) {
                    return [
                        'created_by_id' => $params
                    ];
                }),
                'data' => (function(int $userId) {
                    $tasks = collect();
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory());
                    $tasks->push(Task::factory()->state(['created_by_id' => $userId]));
                    $tasks->push(Task::factory()->state(['created_by_id' => $userId]));

                    return $tasks;
                }),
                'filterCount' => 2
            ]
        ];
    }

    public function testUserTaskIndexPagination(): void
    {
        $user = $this->makeStudent();
        Task::factory()->count(10)->create();
        Task::factory()->count(25)->create(['user_id' => $user->getKey()]);

        $this->actingAs($user, 'api')
            ->getJson('api/tasks?per_page=10')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 25
                ]
            ]);

        $this->actingAs($user, 'api')
            ->getJson('api/tasks?per_page=10&page=3')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 25
                ]
            ]);
    }

    public function testUserTaskIndexForbidden(): void
    {
        $this->actingAs($this->makeUser(), 'api')
            ->getJson('api/tasks')
            ->assertForbidden();
    }

    public function testUserTaskIndexUnauthorized(): void
    {
        $this->getJson('api/tasks')
            ->assertUnauthorized();
    }

    public function testAdminTaskIndexPagination(): void
    {
        $user = $this->makeAdmin();
        Task::factory()->count(20)->create();
        Task::factory()->count(15)->create(['user_id' => $user->getKey()]);

        $this->actingAs($user, 'api')
            ->getJson('api/admin/tasks?per_page=10')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 35
                ]
            ]);

        $this->actingAs($user, 'api')
            ->getJson('api/admin/tasks?per_page=10&page=3')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 35
                ]
            ]);
    }

    public function testAdminTaskIndexForbidden(): void
    {
        $this->actingAs($this->makeUser(), 'api')
            ->getJson('api/admin/tasks')
            ->assertForbidden();
    }

    public function testAdminTaskIndexUnauthorized(): void
    {
        $this->getJson('api/admin/tasks')
            ->assertUnauthorized();
    }
}
