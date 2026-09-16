<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentDetail;
use App\Models\ParentDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountAndSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_when_accessing_account_or_settings()
    {
        $response = $this->get(route('account.index'));
        $response->assertRedirect('/login');

        $response = $this->get(route('settings.index'));
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_account_page()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'name' => 'Alice Smith',
            'email' => 'alice@test.com',
            'username' => 'alicesmith',
        ]);

        StudentDetail::create([
            'user_id' => $user->id,
            'student_phone' => '07123456789',
            'gender' => 'female',
        ]);

        $response = $this->actingAs($user)->get(route('account.index'));

        $response->assertStatus(200);
        $response->assertSee('Alice Smith');
        $response->assertSee('alicesmith');
        $response->assertSee('alice@test.com');
        $response->assertSee('07123456789');
    }

    public function test_edit_profile_alias_route_works()
    {
        $user = User::factory()->create([
            'role' => 'tutor',
            'name' => 'Tutor Dave',
        ]);

        $response = $this->actingAs($user)->get(route('edit-profile'));
        $response->assertStatus(200);
        $response->assertSee('Tutor Dave');
    }

    public function test_user_can_update_profile_information()
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'username' => 'originaluser',
            'email' => 'original@test.com',
            'role' => 'student',
        ]);

        $detail = StudentDetail::create([
            'user_id' => $user->id,
            'student_phone' => '1111111',
            'gender' => 'male',
        ]);

        $response = $this->actingAs($user)->put(route('account.updateProfile'), [
            'name' => 'Updated Name',
            'username' => 'updateduser',
            'email' => 'updated@test.com',
            'student_phone' => '9999999',
            'gender' => 'female',
            'date_of_birth' => '2010-05-15',
        ]);

        $response->assertRedirect(route('account.index'));
        $response->assertSessionHas('success', 'Profile information updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'username' => 'updateduser',
            'email' => 'updated@test.com',
        ]);

        $this->assertDatabaseHas('student_details', [
            'user_id' => $user->id,
            'student_phone' => '9999999',
            'gender' => 'female',
            'date_of_birth' => '2010-05-15',
        ]);
    }

    public function test_profile_update_validates_unique_email_and_username()
    {
        User::factory()->create([
            'username' => 'takenusername',
            'email' => 'taken@test.com',
        ]);

        $user = User::factory()->create([
            'username' => 'myuser',
            'email' => 'me@test.com',
        ]);

        $response = $this->actingAs($user)->put(route('account.updateProfile'), [
            'name' => 'New Name',
            'username' => 'takenusername',
            'email' => 'taken@test.com',
        ]);

        $response->assertSessionHasErrors(['username', 'email']);
    }

    public function test_user_can_update_password_with_correct_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->put(route('account.updatePassword'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewSecretPassword456!',
            'password_confirmation' => 'NewSecretPassword456!',
        ]);

        $response->assertRedirect(route('account.index'));
        $response->assertSessionHas('success', 'Password changed successfully.');

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecretPassword456!', $user->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('CorrectPassword123!'),
        ]);

        $response = $this->actingAs($user)->put(route('account.updatePassword'), [
            'current_password' => 'WrongPassword!',
            'password' => 'NewSecretPassword456!',
            'password_confirmation' => 'NewSecretPassword456!',
        ]);

        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue(Hash::check('CorrectPassword123!', $user->password));
    }

    public function test_user_can_view_settings_page()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Appearance & Theme');
        $response->assertSee('Notification Preferences');
    }

    public function test_admin_can_view_system_settings_tab()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertSee('System & Backup Configuration');
        $response->assertSee('Storage Configuration');
    }

    public function test_user_can_update_settings_and_theme()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('settings.update'), [
            'theme' => 'dark',
            'pref_announcements_email' => '1',
            'pref_sound_alerts' => '0',
        ]);

        $response->assertSessionHas('success', 'Settings updated successfully.');
        $response->assertSessionHas('theme', 'dark');
    }

    public function test_header_contains_account_and_settings_links()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('account.index'));

        $response->assertStatus(200);
        $response->assertSee(route('account.index'));
        $response->assertSee(route('settings.index'));
    }
}
