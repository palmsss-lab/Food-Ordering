<?php
// app/Http/Controllers/Client/CartController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'special_instructions' => $item->special_instructions,
            ];
        }
        
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        
        // Store selected items in session for place order
        session(['checkout_items_ids' => $selectedItemIds]);
        
        return view('client.cart.checkout.index', compact('cartItemsForDisplay', 'subtotal', 'tax', 'total'));
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
        
        // Validate the request
        $request->validate([
            'payment_method' => 'required|in:cash,gcash,card',
            'notes' => 'nullable|string',
            'gcash_number' => 'required_if:payment_method,gcash|nullable|string',
            'card_number' => 'required_if:payment_method,card|nullable|string',
            'card_name' => 'required_if:payment_method,card|nullable|string',
            'card_expiry' => 'required_if:payment_method,card|nullable|string',
            'card_cvv' => 'required_if:payment_method,card|nullable|string',
        ]);
        
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
            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->price * $item->quantity;
            }
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;
            
            // IMPORTANT: Set payment_status based on payment method
            $paymentStatus = ($request->payment_method === 'cash') ? 'cash on pickup' : 'paid';
            
            // Create order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '',
                'order_type' => 'pickup',
                'payment_status' => $paymentStatus, // 'cash on pickup' for cash, 'paid' for GCash/Card
                'order_status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => 0,
                'total' => $total,
                'notes' => $request->notes,
                'ordered_at' => now(),
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
                    'special_instructions' => $cartItem->special_instructions,
                ]);
                
                // Decrease stock
                $cartItem->menuItem->decrement('stock', $cartItem->quantity);
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
            
            DB::commit();
            
            // Clear checkout session
            session()->forget('checkout_items_ids');
            
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
            Log::error('Place order error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
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
                    'special_instructions' => $item->special_instructions,
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