<?php

namespace EscolaLms\Tasks\Tests\Api;

use Carbon\Carbon;
use EscolaLms\Tasks\Database\Seeders\TaskPermissionSeeder;
use EscolaLms\Tasks\Events\TaskAssignedEvent;
use EscolaLms\Tasks\Tests\CreatesUsers;
use EscolaLms\Tasks\Tests\TaskTesting;
use EscolaLms\Tasks\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class TaskCreateApiTest extends TestCase
{
    use TaskTesting, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TaskPermissionSeeder::class);

        Event::fake();
    }

    public function testUserCreateTask(): void
    {
        $user = $this->makeStudent();
        $payload = $this->userCreationPayload();

        $this->actingAs($user, 'api')
            ->postJson('api/tasks', $payload)
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'user_id' => $user->id,
            'created_by_id' => $user->id,
            'due_date' => $payload['due_date'],
            'related_type' => $payload['related_type'],
            'related_id' => $payload['related_id'],
        ]);

        Event::assertNotDispatched(TaskAssignedEvent::class);
    }

    public function testUserCreateTaskExceptUserIdAndNote(): void
    {
        $user = $this->makeStudent();
        $payload = $this->userCreationPayload([
            'user_id' => -123
        ]);

        $this->actingAs($user, 'api')
            ->postJson('api/tasks', $payload)
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'note' => null,
            'user_id' => $user->id,
            'created_by_id' => $user->id,
            'due_date' => $payload['due_date'],
            'related_type' => $payload['related_type'],
            'related_id' => $payload['related_id'],
        ]);

        $this->assertDatabaseMissing('tasks', [
            'title' => $payload['title'],
            'note' => null,
            'user_id' => $payload['user_id'],
            'created_by_id' => $user->id,
            'due_date' => $payload['due_date'],
            'related_type' => $payload['related_type'],
            'related_id' => $payload['related_id'],
        ]);

        Event::assertNotDispatched(TaskAssignedEvent::class);
    }

    /**
     * @dataProvider userInvalidDataProvider
     */
    public function testUserCreateTaskInvalidData(string $key, array $data): void
    {
        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/tasks', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$key]);
    }

    public function testUserCreateTaskUnauthorized(): void
    {
        $this->postJson('api/tasks', $this->userCreationPayload())
            ->assertUnauthorized();
    }

    public function testUserCreateTaskForbidden(): void
    {
        $this->actingAs($this->makeUser(), 'api')
            ->postJson('api/tasks')
            ->assertForbidden();
    }

    public function testAdminCreateTask(): void
    {
        $user = $this->makeAdmin();
        $payload = $this->adminCreationPayload();

        $this->actingAs($user, 'api')
            ->postJson('api/admin/tasks', $payload)
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'note' => $payload['note'],
            'user_id' => $payload['user_id'],
            'created_by_id' => $user->id,
            'due_date' => $payload['due_date'],
            'related_type' => $payload['related_type'],
            'related_id' => $payload['related_id'],
        ]);

        Event::assertDispatched(TaskAssignedEvent::class);
    }

    /**
     * @dataProvider adminInvalidDataProvider
     */
    public function testAdminCreateTaskInvalidData(string $key, array $data): void
    {
        $this->actingAs($this->makeAdmin(), 'api')
            ->postJson('api/admin/tasks', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$key]);

        Event::assertNotDispatched(TaskAssignedEvent::class);
    }

    public function testAdminCreateTaskUnauthorized(): void
    {
        $this->postJson('api/admin/tasks', $this->userCreationPayload())
            ->assertUnauthorized();
    }

    public function testAdminCreateTaskForbidden(): void
    {
        $this->actingAs($this->makeUser(), 'api')
            ->postJson('api/admin/tasks', $this->userCreationPayload())
            ->assertForbidden();
    }

    public function userInvalidDataProvider(): array
    {
        return [
            ['field' => 'title', 'data' => $this->userCreationPayload(['title' => null])],
            ['field' => 'due_date', 'data' => $this->userCreationPayload(['due_date' => Carbon::now()->subDay()])],
            ['field' => 'related_type', 'data' => $this->userCreationPayload(['related_type' => 123])],
            ['field' => 'related_id', 'data' => $this->userCreationPayload(['related_id' => 'String'])],
        ];
    }


    public function adminInvalidDataProvider(): array
    {
        return [
            ['field' => 'title', 'data' => $this->adminCreationPayload(['title' => null])],
            ['field' => 'due_date', 'data' => $this->adminCreationPayload(['due_date' => Carbon::now()->subDay()])],
            ['field' => 'user_id', 'data' => $this->adminCreationPayload(['user_id' => -123])],
            ['field' => 'user_id', 'data' => $this->adminCreationPayload(['user_id' => null])],
            ['field' => 'related_type', 'data' => $this->adminCreationPayload(['related_type' => 123])],
            ['field' => 'related_id', 'data' => $this->adminCreationPayload(['related_id' => 'String'])],
        ];
    }
}
