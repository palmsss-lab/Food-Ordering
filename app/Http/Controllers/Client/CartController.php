<?php
// app/Http/Controllers/Client/CartController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Voucher;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display cart.
     */
    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $total = $this->cartService->getTotal();
        
        return view('client.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'instructions' => 'nullable|string|max:255'
        ]);

        try {
            $this->cartService->addItem(
                $menuItem,
                $request->quantity,
                $request->instructions
            );

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'count' => $this->cartService->getItemCount(),
                    'message' => 'Item added to cart successfully!'
                ]);
            }

            return redirect()->route('client.cart.index')
                ->with('success', 'Item added to cart successfully!');
                
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        try {
            $result = $this->cartService->updateQuantity($itemId, $request->quantity);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'count' => $this->cartService->getItemCount(),
                    'total' => $this->cartService->getTotal(),
                    'message' => 'Cart updated successfully.'
                ]);
            }

            return redirect()->route('client.cart.index')
                ->with('success', 'Cart updated successfully.');
                
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request, $itemId)
    {
        try {
            $this->cartService->removeItem($itemId);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'count' => $this->cartService->getItemCount(),
                    'message' => 'Item removed from cart.'
                ]);
            }
            
            return redirect()->route('client.cart.index')
                ->with('success', 'Item removed from cart.');
                
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Clear cart.
     */
    public function clear(Request $request)
    {
        try {
            $this->cartService->clearCart();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart cleared successfully.'
                ]);
            }
            
            return redirect()->route('client.cart.index')
                ->with('success', 'Cart cleared successfully.');
                
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Show checkout page (combined with payment)
     */
    public function showCheckout(Request $request)
    {

    
        // Debug: Log what we received
        Log::info('Checkout request received', [
            'selected_items' => $request->selected_items,
            'all_input' => $request->all()
        ]);
        
        // Get selected items from the request (NOT from session)
        $selectedItemIds = json_decode($request->selected_items, true);
        
        // If it's not JSON, maybe it's a comma-separated string
        if (empty($selectedItemIds) && !empty($request->selected_items)) {
            // Try to parse as comma-separated
            $selectedItemIds = explode(',', $request->selected_items);
        }
        
        if (empty($selectedItemIds) || !is_array($selectedItemIds)) {
            Log::error('No items selected for checkout');
            return redirect()->route('client.cart.index')
                ->with('error', 'No items selected for checkout.');
        }
        
        // Get cart items
        $cart = $this->cartService->getCart();
        if (!$cart) {
            Log::error('Cart not found');
            return redirect()->route('client.cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        $cartItems = CartItem::with('menuItem')
            ->whereIn('id', $selectedItemIds)
            ->where('cart_id', $cart->id)
            ->get();
        
        Log::info('Found cart items', ['count' => $cartItems->count()]);
        
        if ($cartItems->isEmpty()) {
            Log::error('Selected items not found in cart');
            return redirect()->route('client.cart.index')
                ->with('error', 'Selected items not found.');
        }
        
        // Verify stock for all items
        foreach ($cartItems as $item) {
            if ($item->menuItem->stock < $item->quantity) {
                Log::warning('Out of stock item', [
                    'item' => $item->menuItem->name,
                    'stock' => $item->menuItem->stock,
                    'requested' => $item->quantity
                ]);
                return redirect()->route('client.cart.index')
                    ->with('error', "Sorry, {$item->menuItem->name} only has {$item->menuItem->stock} left.");
            }
        }
        
        // Prepare cart items for display
        $cartItemsForDisplay = [];
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $itemSubtotal = $item->price * $item->quantity;
            $subtotal += $itemSubtotal;
            
            $cartItemsForDisplay[] = [
                'id' => $item->menu_item_id,
                'name' => $item->menuItem->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $itemSubtotal,
            ];
        }
        
        // Clear any stale discount sessions from a previous checkout attempt
        session()->forget(['checkout_discount', 'checkout_promo']);

        // Store selected items in session for place order
        session(['checkout_items_ids' => $selectedItemIds]);

        // Auto-apply active promotion (stored separately so voucher/pwd never wipes it)
        $activePromo = Promotion::todayPromo();
        if ($activePromo) {
            $promoDiscount = round($subtotal * ($activePromo->discount_percentage / 100), 2);
            session(['checkout_promo' => [
                'promo_id'       => $activePromo->id,
                'discountAmount' => $promoDiscount,
                'label'          => $activePromo->title . ' (' . number_format($activePromo->discount_percentage, 0) . '% off)',
            ]]);
            $tax   = round(($subtotal - $promoDiscount) * 0.12, 2);
            $total = round(($subtotal - $promoDiscount) + $tax, 2);
        } else {
            $tax   = round($subtotal * 0.12, 2);
            $total = round($subtotal + $tax, 2);
        }

        return view('client.cart.checkout.index', compact('cartItemsForDisplay', 'subtotal', 'tax', 'total', 'activePromo'));
    }

    /**
     * Place order - CREATES ORDER IN DATABASE
     */

    public function placeOrder(Request $request)
    {
        // Get selected items from session
        $selectedItemIds = session('checkout_items_ids');
        
        Log::info('Place order request', [
            'selected_items_ids' => $selectedItemIds,
            'payment_method' => $request->payment_method
        ]);
        
        if (empty($selectedItemIds)) {
            return redirect()->route('client.cart.index')
                ->with('error', 'No items selected for checkout. Please try again.');
        }
        
        // Validate the request — redirect to cart (not back) to avoid GET /checkout
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:cash,gcash,card',
            'notes'          => 'nullable|string|max:500',
            'gcash_number'   => ['required_if:payment_method,gcash', 'nullable', 'string', 'regex:/^0?9\d{9}$/'],
            'card_number'    => ['required_if:payment_method,card', 'nullable', 'regex:/^\d[\d\s]{11,17}\d$/'],
            'card_name'      => 'required_if:payment_method,card|nullable|string|max:100',
            'card_expiry'    => ['required_if:payment_method,card', 'nullable', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvv'       => 'required_if:payment_method,card|nullable|digits_between:3,4',
        ]);

        if ($validator->fails()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Payment details are invalid. Please try again.')
                ->withErrors($validator);
        }
        
        // Get authenticated user
        $user = Auth::user();
        
        // Get cart items
        $cart = $this->cartService->getCart();
        if (!$cart) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        $cartItems = CartItem::with('menuItem')
            ->whereIn('id', $selectedItemIds)
            ->where('cart_id', $cart->id)
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Selected items not found.');
        }
        
        DB::beginTransaction();

        try {
            // Re-validate stock inside the transaction with a pessimistic lock
            // to prevent race conditions when multiple users order the same item.
            $lockedMenuItems = [];
            foreach ($cartItems as $cartItem) {
                $menuItem = MenuItem::lockForUpdate()->find($cartItem->menu_item_id);
                if (!$menuItem || $menuItem->stock < $cartItem->quantity) {
                    $available = $menuItem ? $menuItem->stock : 0;
                    throw new \Exception("Sorry, '{$cartItem->menuItem->name}' only has {$available} left in stock.");
                }
                $lockedMenuItems[$cartItem->menu_item_id] = $menuItem;
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->price * $item->quantity;
            }

            // --- Step 1: Apply promotion discount (always stacks, stored separately) ---
            $promoSessionData = session('checkout_promo');
            $promoDiscount    = 0;
            $promoLabel       = null;
            $promotionId      = null;

            if ($promoSessionData) {
                $promo = Promotion::find($promoSessionData['promo_id'] ?? null);
                if ($promo && $promo->isRunning()) {
                    $promoDiscount = round($subtotal * ($promo->discount_percentage / 100), 2);
                    $promoLabel    = $promo->title . ' (' . number_format($promo->discount_percentage, 0) . '% off)';
                    $promotionId   = $promo->id;
                }
            }

            $postPromoSubtotal = $subtotal - $promoDiscount;

            // --- Step 2: Apply extra discount (voucher / pwd / senior) on top of promo ---
            $discountData  = session('checkout_discount');
            $discount      = 0;
            $voucherId     = null;
            $discountType  = null;
            $discountLabel = null;

            if ($discountData) {
                $dtype = $discountData['type'] ?? 'none';

                if ($dtype === 'pwd' || $dtype === 'senior') {
                    // Applied to post-promo amount; VAT exempt
                    $discount      = round($postPromoSubtotal * 0.20, 2);
                    $tax           = 0;
                    $total         = round($postPromoSubtotal - $discount, 2);
                    $discountType  = $dtype;
                    $discountLabel = $discountData['label'];
                } elseif ($dtype === 'voucher') {
                    $voucher = Voucher::find($discountData['voucher_id'] ?? null);
                    if ($voucher && $voucher->isValid($subtotal)) {
                        $discount      = $voucher->calculateDiscount($postPromoSubtotal);
                        $taxBase       = max(0, $postPromoSubtotal - $discount);
                        $tax           = round($taxBase * 0.12, 2);
                        $total         = round($taxBase + $tax, 2);
                        $voucherId     = $voucher->id;
                        $discountType  = 'voucher';
                        $discountLabel = $voucher->label();
                    } else {
                        // Voucher became invalid — fall back to promo-only
                        $tax   = round($postPromoSubtotal * 0.12, 2);
                        $total = round($postPromoSubtotal + $tax, 2);
                    }
                } else {
                    $tax   = round($postPromoSubtotal * 0.12, 2);
                    $total = round($postPromoSubtotal + $tax, 2);
                }
            } else {
                $tax   = round($postPromoSubtotal * 0.12, 2);
                $total = round($postPromoSubtotal + $tax, 2);
            }

            // IMPORTANT: Set payment_status based on payment method
            $paymentStatus = ($request->payment_method === 'cash') ? 'cash on pickup' : 'paid';

            // Create order
            $order = Order::create([
                'order_number'   => $this->generateOrderNumber(),
                'user_id'        => Auth::id(),
                'customer_name'  => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '',
                'order_type'     => 'pickup',
                'payment_status' => $paymentStatus,
                'order_status'   => 'pending',
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => $discount,
                'promo_discount' => $promoDiscount,
                'promo_label'    => $promoLabel,
                'promotion_id'   => $promotionId,
                'voucher_id'     => $voucherId,
                'discount_type'  => $discountType,
                'discount_label' => $discountLabel,
                'total'          => $total,
                'notes'          => $request->notes,
                'ordered_at'     => now(),
            ]);
            
            Log::info('Order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $order->payment_status,
                'payment_method' => $request->payment_method
            ]);
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'item_name' => $cartItem->menuItem->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->price * $cartItem->quantity,
                ]);
                
                // Decrease stock using the locked instance
                $lockedMenuItems[$cartItem->menu_item_id]->decrement('stock', $cartItem->quantity);
            }
            
            // Handle payment based on method
            if ($request->payment_method === 'cash') {
                // Cash on pickup - create pending payment record
                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cash',
                    'payment_status' => 'pending',
                    'amount' => $total,
                    'payment_number' => Payment::generatePaymentNumber(),
                    'reference_number' => 'CASH-' . strtoupper(uniqid()),
                ]);
                
            } else {
                // GCash or Card - create COMPLETED payment record
                $paymentData = [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'completed', // This will show as "Paid" in admin
                    'amount' => $total,
                    'payment_number' => Payment::generatePaymentNumber(),
                    'reference_number' => strtoupper($request->payment_method) . '-' . uniqid(),
                    'paid_at' => now(),
                ];
                
                if ($request->payment_method === 'gcash') {
                    $paymentData['gcash_number'] = $request->gcash_number;
                    $paymentData['gcash_reference'] = 'GCASH-' . uniqid();
                } else {
                    $paymentData['card_last_four'] = substr($request->card_number, -4);
                    $paymentData['card_type'] = $this->detectCardType($request->card_number);
                }
                
                Payment::create($paymentData);
                
                Log::info('Payment created for GCash/Card', [
                    'order_id' => $order->id,
                    'payment_status' => 'completed',
                    'payment_method' => $request->payment_method
                ]);
            }
            
            // Delete the checked out cart items
            CartItem::whereIn('id', $selectedItemIds)->delete();

            // Mark user's voucher claim as used (used_count is derived from this table, not cached)
            if ($voucherId) {
                DB::table('user_vouchers')
                    ->where('user_id', Auth::id())
                    ->where('voucher_id', $voucherId)
                    ->whereNull('used_at')
                    ->update(['used_at' => now(), 'order_id' => $order->id]);
            }

            DB::commit();

            // Clear checkout session
            session()->forget(['checkout_items_ids', 'checkout_discount', 'checkout_promo']);
            
            // Redirect based on payment method
            if ($request->payment_method === 'cash') {
                return redirect()->route('client.payments.pending', $order->order_number)
                    ->with('success', 'Order placed! Please wait for admin confirmation.');
            } else {
                return redirect()->route('client.payments.gcash-waiting', $order)
                    ->with('success', 'Payment successful! Your order is pending admin confirmation.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Place order error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Show stock/availability messages directly; hide all other internal details
            $userMessage = str_starts_with($e->getMessage(), "Sorry,")
                ? $e->getMessage()
                : 'Failed to place your order. Please try again.';
            // Redirect to cart instead of back() — back() would try GET /client/checkout which is POST-only
            return redirect()->route('client.cart.index')->with('error', $userMessage);
        }
    }
    
    /**
     * AJAX: Validate and apply a discount (voucher / pwd / senior).
     * Returns discount_amount, new tax, new total, and a label.
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_type' => 'required|in:voucher,pwd,senior,none',
            'voucher_code'  => 'required_if:discount_type,voucher|nullable|string',
            'subtotal'      => 'required|numeric|min:0',
        ]);

        if ($request->discount_type === 'none') {
            // Promo lives in its own session key; just clear the extra discount.
            session()->forget('checkout_discount');
            return response()->json(['success' => true]);
        }

        $subtotal = (float) $request->subtotal;
        $type     = $request->discount_type;

        // Recalculate promo from DB each time so a stale session never silently drops it
        $promoSession  = session('checkout_promo');
        $promoDiscount = 0;
        if ($promoSession) {
            $promo = \App\Models\Promotion::find($promoSession['promo_id'] ?? null);
            if ($promo && $promo->isRunning()) {
                $promoDiscount = round($subtotal * ($promo->discount_percentage / 100), 2);
                session(['checkout_promo' => array_merge($promoSession, ['discountAmount' => $promoDiscount])]);
            }
        }
        $postPromoSub = max(0, $subtotal - $promoDiscount);

        if ($type === 'pwd' || $type === 'senior') {
            // PH law: 20% off post-promo amount + VAT exempt
            $discountAmount = round($postPromoSub * 0.20, 2);
            $tax            = 0;
            $total          = round($postPromoSub - $discountAmount, 2);
            $label          = $type === 'pwd' ? 'PWD Discount (20%)' : 'Senior Citizen Discount (20%)';

            session(['checkout_discount' => compact('type', 'discountAmount', 'tax', 'total', 'label')]);

            return response()->json([
                'success'         => true,
                'discount_amount' => $discountAmount,
                'tax'             => $tax,
                'total'           => $total,
                'label'           => $label,
            ]);
        }

        // Voucher
        $code    = strtoupper(trim($request->voucher_code));
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher not found.'], 422);
        }

        // Must have claimed this voucher
        if (!$voucher->isAvailableFor(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'You have not collected this voucher.'], 422);
        }

        if (!$voucher->isValid($subtotal)) {
            $reason = 'This voucher is no longer valid.';
            if (!$voucher->is_active)                              $reason = 'This voucher is inactive.';
            elseif ($voucher->expires_at?->isPast())               $reason = 'This voucher has expired.';
            elseif ($voucher->max_uses && $voucher->actualUsedCount() >= $voucher->max_uses) $reason = 'This voucher has reached its maximum number of redemptions.';
            elseif ($voucher->min_order_amount && $subtotal < (float) $voucher->min_order_amount)
                $reason = 'Minimum order amount of ₱' . number_format($voucher->min_order_amount, 2) . ' required.';

            return response()->json(['success' => false, 'message' => $reason], 422);
        }

        $discountAmount = $voucher->calculateDiscount($postPromoSub);
        $taxBase        = max(0, $postPromoSub - $discountAmount);
        $tax            = round($taxBase * 0.12, 2);
        $total          = round($taxBase + $tax, 2);
        $label          = $voucher->label();

        session(['checkout_discount' => [
            'type'           => 'voucher',
            'voucher_id'     => $voucher->id,
            'voucher_code'   => $voucher->code,
            'discountAmount' => $discountAmount,
            'tax'            => $tax,
            'total'          => $total,
            'label'          => $label,
        ]]);

        return response()->json([
            'success'         => true,
            'discount_amount' => $discountAmount,
            'tax'             => $tax,
            'total'           => $total,
            'label'           => $label,
        ]);
    }

    /**
     * Get cart items for checkout (without creating order)
     */
    public function getCartItemsForCheckout()
    {
        $cart = $this->cartService->getCart();
        
        if (!$cart) {
            return collect();
        }
        
        return CartItem::with('menuItem')
            ->where('cart_id', $cart->id)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->menu_item_id,
                    'name' => $item->menuItem->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ];
            });
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'ORD-' . $date . '-' . $newNumber;
    }

    /**
     * Detect card type from number
     */
    private function detectCardType($number)
    {
        $number = preg_replace('/\D/', '', $number);
        
        if (preg_match('/^4/', $number)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $number)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]/', $number)) {
            return 'American Express';
        } elseif (preg_match('/^6(?:011|5)/', $number)) {
            return 'Discover';
        }
        
        return 'Unknown';
    }
}