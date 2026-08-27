<?php

namespace Tests\Feature;

use App\Livewire\Auth\ForgotPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_renders_successfully(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Lupa Password?');
    }

    public function test_user_can_request_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'pelanggan@indoroster.com',
        ]);

        Livewire::test(ForgotPassword::class)
            ->set('email', 'pelanggan@indoroster.com')
            ->call('sendResetLink')
            ->assertHasNoErrors()
            ->assertSee('Tautan pemulihan kata sandi telah dikirim');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_page_renders_with_token(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]));

        $response->assertStatus(200);
        $response->assertSee('Atur Ulang Kata Sandi');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'pelanggan@indoroster.com',
            'password' => Hash::make('password_lama_123'),
        ]);

        $token = Password::broker()->createToken($user);

        Livewire::test(\App\Livewire\Auth\ResetPassword::class, ['token' => $token])
            ->set('email', 'pelanggan@indoroster.com')
            ->set('password', 'password_baru_456')
            ->set('password_confirmation', 'password_baru_456')
            ->call('resetPassword')
            ->assertHasNoErrors()
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('password_baru_456', $user->fresh()->password));
    }

    public function test_reset_password_fails_if_passwords_do_not_match(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        Livewire::test(\App\Livewire\Auth\ResetPassword::class, ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'password_baru_123')
            ->set('password_confirmation', 'tidak_cocok_789')
            ->call('resetPassword')
            ->assertHasErrors(['password']);
    }
}
