<?php

use App\Livewire\Admin\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

function profileAdmin(array $overrides = []): User
{
    return User::factory()->create(array_merge([
        'role' => 'admin',
        'status' => 'active',
    ], $overrides));
}

test('only administrators can open the admin profile page', function () {
    $admin = profileAdmin();

    $this->actingAs($admin)
        ->get(route('admin.profile'))
        ->assertOk()
        ->assertSee('Admin profile');

    $customer = User::factory()->create(['role' => 'user']);

    $this->actingAs($customer)
        ->get(route('admin.profile'))
        ->assertForbidden();
});

test('an administrator can update profile information', function () {
    $admin = profileAdmin();

    Livewire::actingAs($admin)
        ->test(Profile::class)
        ->set('name', 'Ticks Administrator')
        ->set('email', 'admin@ticks.test')
        ->set('phone', '+95 9 123 456 789')
        ->call('saveProfile')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.profile'));

    $admin->refresh();

    expect($admin->name)->toBe('Ticks Administrator')
        ->and($admin->email)->toBe('admin@ticks.test')
        ->and($admin->phone)->toBe('+95 9 123 456 789')
        ->and($admin->email_verified_at)->toBeNull();
});

test('an administrator can update their password', function () {
    $admin = profileAdmin();

    Livewire::actingAs($admin)
        ->test(Profile::class)
        ->set('current_password', 'password')
        ->set('password', 'A-secure-new-password-2026')
        ->set('password_confirmation', 'A-secure-new-password-2026')
        ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.profile'));

    expect(Hash::check('A-secure-new-password-2026', $admin->fresh()->password))->toBeTrue();
});

test('an administrator can upload and remove a profile photo', function () {
    Storage::fake('public');

    $admin = profileAdmin();
    $photo = UploadedFile::fake()->createWithContent(
        'profile.png',
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
    );

    Livewire::actingAs($admin)
        ->test(Profile::class)
        ->set('avatar', $photo)
        ->call('saveAvatar')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.profile'));

    $avatar = $admin->fresh()->avatar;

    expect($avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($avatar);

    Livewire::actingAs($admin->fresh())
        ->test(Profile::class)
        ->call('removeAvatar')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.profile'));

    expect($admin->fresh()->avatar)->toBeNull();
    Storage::disk('public')->assertMissing($avatar);
});

test('an administrator can securely log out other active sessions', function () {
    config(['session.driver' => 'database']);

    $admin = profileAdmin();

    DB::table('sessions')->insert([
        'id' => 'another-admin-session',
        'user_id' => $admin->id,
        'ip_address' => '192.0.2.10',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0) Chrome/126.0',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);

    Livewire::actingAs($admin)
        ->test(Profile::class)
        ->set('session_password', 'password')
        ->call('logoutOtherSessions')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.profile'));

    expect(DB::table('sessions')->where('id', 'another-admin-session')->exists())->toBeFalse();
});
