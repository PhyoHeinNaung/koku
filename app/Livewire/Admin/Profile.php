<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public $avatar = null;

    public ?string $existingAvatar = null;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $session_password = '';

    public function mount(): void
    {
        $user = $this->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->existingAvatar = $user->avatar;
    }

    public function saveProfile(): void
    {
        $user = $this->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s().-]+$/'],
        ]);

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        session()->flash('success', 'Your profile information was updated.');
        $this->redirectRoute('admin.profile');
    }

    public function saveAvatar(): void
    {
        $this->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $this->user();
        $previousAvatar = $user->avatar;
        $path = $this->avatar->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        if ($previousAvatar && ! str_starts_with($previousAvatar, 'http')) {
            Storage::disk('public')->delete($previousAvatar);
        }

        session()->flash('success', 'Your profile photo was updated.');
        $this->redirectRoute('admin.profile');
    }

    public function removeAvatar(): void
    {
        $user = $this->user();

        if ($user->avatar && ! str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        session()->flash('success', 'Your profile photo was removed.');
        $this->redirectRoute('admin.profile');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $this->user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('success', 'Your password was updated securely.');
        $this->redirectRoute('admin.profile');
    }

    public function sendVerification(): void
    {
        $user = $this->user();

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();
        $this->dispatch('admin-notify', type: 'success', message: 'A new verification link was sent to your email.');
    }

    public function logoutOtherSessions(): void
    {
        $this->validate([
            'session_password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($this->session_password);

        $deleted = 0;

        if ($this->usesDatabaseSessions()) {
            $deleted = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $this->user()->id)
                ->where('id', '!=', session()->getId())
                ->delete();
        }

        $this->reset('session_password');

        session()->flash(
            'success',
            $deleted > 0 ? 'All other administrator sessions were signed out.' : 'There were no other active sessions.'
        );
        $this->redirectRoute('admin.profile');
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => $this->user(),
            'activeSessions' => $this->activeSessions(),
        ])->layout('layouts.admin');
    }

    /**
     * @return array<int, array{id: string, device: string, ip_address: string, is_current: bool, is_mobile: bool, last_active: string}>
     */
    private function activeSessions(): array
    {
        if (! $this->usesDatabaseSessions()) {
            return [$this->currentSession()];
        }

        $sessions = DB::table(config('session.table', 'sessions'))
            ->where('user_id', $this->user()->id)
            ->where('last_activity', '>=', now()->subMinutes((int) config('session.lifetime', 120))->timestamp)
            ->orderByDesc('last_activity')
            ->get();

        if ($sessions->isEmpty()) {
            return [$this->currentSession()];
        }

        return $sessions->map(function ($session): array {
            $agent = (string) ($session->user_agent ?? '');

            return [
                'id' => (string) $session->id,
                'device' => $this->deviceName($agent),
                'ip_address' => (string) ($session->ip_address ?: 'Unknown IP'),
                'is_current' => hash_equals(session()->getId(), (string) $session->id),
                'is_mobile' => $this->isMobileAgent($agent),
                'last_active' => now()->setTimestamp((int) $session->last_activity)->diffForHumans(),
            ];
        })->all();
    }

    /**
     * @return array{id: string, device: string, ip_address: string, is_current: bool, is_mobile: bool, last_active: string}
     */
    private function currentSession(): array
    {
        $agent = (string) request()->userAgent();

        return [
            'id' => session()->getId(),
            'device' => $this->deviceName($agent),
            'ip_address' => (string) (request()->ip() ?: 'Unknown IP'),
            'is_current' => true,
            'is_mobile' => $this->isMobileAgent($agent),
            'last_active' => 'Active now',
        ];
    }

    private function deviceName(string $agent): string
    {
        $browser = match (true) {
            str_contains($agent, 'Edg/') => 'Microsoft Edge',
            str_contains($agent, 'OPR/'), str_contains($agent, 'Opera') => 'Opera',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Web browser',
        };

        $platform = match (true) {
            str_contains($agent, 'Windows') => 'Windows',
            str_contains($agent, 'Android') => 'Android',
            str_contains($agent, 'iPhone'), str_contains($agent, 'iPad') => 'iOS',
            str_contains($agent, 'Macintosh') => 'macOS',
            str_contains($agent, 'Linux') => 'Linux',
            default => 'Unknown device',
        };

        return "{$browser} on {$platform}";
    }

    private function isMobileAgent(string $agent): bool
    {
        return str_contains($agent, 'Mobile')
            || str_contains($agent, 'Android')
            || str_contains($agent, 'iPhone')
            || str_contains($agent, 'iPad');
    }

    private function usesDatabaseSessions(): bool
    {
        return config('session.driver') === 'database'
            && Schema::hasTable(config('session.table', 'sessions'));
    }

    private function user(): User
    {
        $user = auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }
}
