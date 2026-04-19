<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * Browse all public, active vouchers.
     */
    public function index()
    {
        $userId = Auth::id();

        $vouchers = Voucher::where('is_active', true)
            ->where('is_public', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereRaw('(SELECT COUNT(*) FROM user_vouchers WHERE voucher_id = vouchers.id AND used_at IS NOT NULL) < max_uses');
            })
            ->latest()
            ->get()
            ->map(function ($voucher) use ($userId) {
                $voucher->claimed    = $voucher->isClaimedBy($userId);
                $voucher->used       = $voucher->claimed && !$voucher->isAvailableFor($userId);
                return $voucher;
            });

        // User's collected (unused) count for badge
        $collectedCount = DB::table('user_vouchers')
            ->where('user_id', $userId)
            ->whereNull('used_at')
            ->count();

        return view('client.vouchers.index', compact('vouchers', 'collectedCount'));
    }

    /**
     * Claim a voucher.
     */
    public function claim(Voucher $voucher)
    {
        $userId = Auth::id();

        if (!$voucher->is_active || !$voucher->is_public) {
            return response()->json(['success' => false, 'message' => 'Voucher not available.'], 422);
        }

        if ($voucher->expires_at && $voucher->expires_at->isPast()) {
            return response()->json(['success' => false, 'message' => 'This voucher has expired.'], 422);
        }

        if ($voucher->max_uses !== null && $voucher->actualUsedCount() >= $voucher->max_uses) {
            return response()->json(['success' => false, 'message' => 'This voucher has run out.'], 422);
        }

        if ($voucher->isClaimedBy($userId)) {
            return response()->json(['success' => false, 'message' => 'You already collected this voucher.'], 422);
        }

        DB::table('user_vouchers')->insert([
            'user_id'    => $userId,
            'voucher_id' => $voucher->id,
            'claimed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher collected! Use it at checkout.',
        ]);
    }

    /**
     * Return ALL collected vouchers for the "My Collected" history tab —
     * includes used, expired, and still-active ones.
     */
    public function myCollectedHistory()
    {
        $userId = Auth::id();

        $rows = DB::table('user_vouchers as uv')
            ->join('vouchers as v', 'v.id', '=', 'uv.voucher_id')
            ->where('uv.user_id', $userId)
            ->select('v.*', 'uv.claimed_at', 'uv.used_at')
            ->orderByDesc('uv.claimed_at')
            ->get();

        $result = $rows->map(function ($v) {
            $isUsed    = !is_null($v->used_at);
            $isExpired = $v->expires_at && \Carbon\Carbon::parse($v->expires_at)->isPast();

            return [
                'id'               => $v->id,
                'code'             => $v->code,
                'description'      => $v->description,
                'type'             => $v->type,
                'value'            => $v->value,
                'min_order_amount' => $v->min_order_amount,
                'expires_at'       => $v->expires_at ? \Carbon\Carbon::parse($v->expires_at)->format('M d, Y') : null,
                'claimed_at'       => \Carbon\Carbon::parse($v->claimed_at)->format('M d, Y'),
                'used_at'          => $v->used_at ? \Carbon\Carbon::parse($v->used_at)->format('M d, Y') : null,
                'is_used'          => $isUsed,
                'is_expired'       => $isExpired,
                'status'           => $isUsed ? 'used' : ($isExpired ? 'expired' : 'active'),
            ];
        });

        return response()->json($result);
    }

    /**
     * Return the user's collected (unused) vouchers as JSON — used by checkout.
     */
    public function myVouchers(Request $request)
    {
        $userId   = Auth::id();
        $subtotal = (float) $request->query('subtotal', 0);

        // Factor in any active promo so the "Saves ₱X" shown per voucher is accurate
        $promoSession  = session('checkout_promo');
        $promoDiscount = 0;
        if ($promoSession) {
            $promo = Promotion::find($promoSession['promo_id'] ?? null);
            if ($promo && $promo->isRunning()) {
                $promoDiscount = round($subtotal * ($promo->discount_percentage / 100), 2);
            }
        }
        $postPromoSub = max(0, $subtotal - $promoDiscount);

        $rows = DB::table('user_vouchers as uv')
            ->join('vouchers as v', 'v.id', '=', 'uv.voucher_id')
            ->where('uv.user_id', $userId)
            ->whereNull('uv.used_at')
            ->where('v.is_active', true)
            ->where(function ($q) {
                $q->whereNull('v.expires_at')->orWhere('v.expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('v.max_uses')
                  ->orWhereRaw('(SELECT COUNT(*) FROM user_vouchers uv2 WHERE uv2.voucher_id = v.id AND uv2.used_at IS NOT NULL) < v.max_uses');
            })
            ->select('v.*', 'uv.claimed_at')
            ->get();

        $result = $rows->map(function ($v) use ($subtotal, $postPromoSub) {
            $voucher     = new Voucher((array) $v);
            $voucher->id = $v->id;
            $eligible    = $subtotal <= 0 || !$v->min_order_amount || $subtotal >= $v->min_order_amount;
            $discount    = $eligible ? $voucher->calculateDiscount($postPromoSub) : null;

            return [
                'id'               => $v->id,
                'code'             => $v->code,
                'description'      => $v->description,
                'type'             => $v->type,
                'value'            => $v->value,
                'min_order_amount' => $v->min_order_amount,
                'expires_at'       => $v->expires_at ? \Carbon\Carbon::parse($v->expires_at)->format('M d, Y') : null,
                'label'            => $voucher->label(),
                'eligible'         => $eligible,
                'discount_amount'  => $discount,
            ];
        });

        return response()->json($result);
    }
}
