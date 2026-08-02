<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $guestSessionId = session()->getId();

        $request->session()->regenerate();

        $user = auth()->user();

        if ($user->status === 'banned') {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account has been banned.',
            ]);
        }

        $this->mergeGuestWishlist($guestSessionId, $user->id);
        $this->mergeGuestCart($guestSessionId, $user->id);

        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        return redirect('/');
    }

    private function mergeGuestWishlist(string $guestSessionId, int $userId): void
    {
        $guestProductIds = WishlistItem::where('session_id', $guestSessionId)->pluck('product_id');

        if ($guestProductIds->isEmpty()) {
            return;
        }

        $alreadyOwned = WishlistItem::where('user_id', $userId)
            ->whereIn('product_id', $guestProductIds)
            ->pluck('product_id');

        // Drop guest rows that would duplicate what the user already has saved.
        WishlistItem::where('session_id', $guestSessionId)
            ->whereIn('product_id', $alreadyOwned)
            ->delete();

        // Claim the rest under the now-authenticated account.
        WishlistItem::where('session_id', $guestSessionId)
            ->update(['user_id' => $userId, 'session_id' => null]);
    }

    private function mergeGuestCart(string $guestSessionId, int $userId): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)->whereNull('expired_at')->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId, 'session_id' => null, 'expired_at' => null]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()->where('variant_id', $guestItem->variant_id)->first();

            if ($existing) {
                $existing->update(['quantity' => min($existing->quantity + $guestItem->quantity, $guestItem->variant->stock_quantity)]);
            } else {
                $userCart->items()->create([
                    'variant_id' => $guestItem->variant_id,
                    'quantity' => $guestItem->quantity,
                    'unit_price' => $guestItem->unit_price,
                ]);
            }
        }

        $guestCart->delete();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
