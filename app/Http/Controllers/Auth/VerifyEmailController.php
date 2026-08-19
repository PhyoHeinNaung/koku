<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, int $id, string $hash): RedirectResponse
    {
        $user = User::query()->findOrFail($id);

        abort_unless(
            hash_equals((string) $hash, sha1($user->getEmailForVerification())),
            403,
            'This email verification link is invalid.'
        );

        if (! $user->hasVerifiedEmail()) {
            DB::transaction(function () use ($user): void {
                $user->markEmailAsVerified();

                if ($user->status === 'pending') {
                    $user->forceFill(['status' => 'active'])->save();
                }
            });

            event(new Verified($user));
        }

        if ($request->user()?->is($user)) {
            return redirect()->intended($user->homeRoute().'?verified=1');
        }

        return redirect()->route('login')->with('status', 'Your email has been verified. You can now sign in.');
    }
}
