<?php

namespace Tests\Feature\Imports;

use App\Constants\Permissions;
use App\Jobs\ImportInvoicesJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InvoiceImportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_returns_inertia_response()
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $createPermission = Permission::create(['name' => Permissions::IMPORTS_CREATE]);

        $adminRole->givePermissionTo($createPermission);
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($adminRole);

        $response = $this->actingAs($user)
            ->get(route('imports.create'));
        $response->assertInertia(fn ($page) => $page->component('Imports/Create'));
    }

    public function test_import_dispatched_successfully()
    {
        $user = User::factory()->create();
        Auth::login($user);

        Storage::fake('local');
        $file = UploadedFile::fake()->create('invoices.csv', 100);

        Queue::fake();
        $response = $this->post(route('import.invoices'), [
            'file' => $file,
        ]);

        Queue::assertPushed(ImportInvoicesJob::class);

        $response->assertRedirect()->with('success', 'Invoices import dispatched successfully!');
    }

    public function test_import_handles_error()
    {
        Queue::fake();
        $user = User::factory()->create();
        Auth::login($user);

        $file = UploadedFile::fake()->create('invoices.csv', 100);

        Storage::shouldReceive('putFileAs')->andThrow(new \Exception('File could not be stored'));

        $response = $this->post(route('import.invoices'), [
            'file' => $file,
        ]);

        Queue::assertNotPushed(ImportInvoicesJob::class);

        $response->assertRedirect()->withErrors(['file' => 'File could not be stored']);
    }
}
