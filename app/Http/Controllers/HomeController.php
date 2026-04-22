<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function menu()
    {
        $menus = Menu::all();
        return view('menu', compact('menus'));
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('items.menu')->orderBy('created_at', 'desc')->get();
        return view('profile', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->name = $request->name;
        $user->phone = $request->phone;
        if ($request->password) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        $user->save();
        
        return back()->with('success', 'Profile updated successfully!');
    }

    public function order(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = Menu::find($request->menu_id);
        
        $order = Order::where('user_id', Auth::id())->where('status', 'pending')->first();
        if (!$order) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total_price' => 0,
            ]);
        }

        $existingItem = OrderItem::where('order_id', $order->id)->where('menu_id', $menu->id)->first();
        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $request->quantity,
                'price' => $menu->price
            ]);
        }

        $this->recalculateOrder($order);

        if (request()->wantsJson()) {
            $cartCount = OrderItem::whereHas('order', function($q) {
                $q->where('user_id', Auth::id())->where('status', 'pending');
            })->sum('quantity');
            return response()->json(['success' => true, 'cartCount' => $cartCount]);
        }

        return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function cart()
    {
        $order = Order::where('user_id', Auth::id())->where('status', 'pending')->with('items.menu')->first();
        return view('cart', compact('order'));
    }

    public function updateCartItem(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:increase,decrease']);
        $item = OrderItem::where('id', $id)->whereHas('order', function($q) {
            $q->where('user_id', Auth::id())->where('status', 'pending');
        })->firstOrFail();

        if ($request->action == 'increase') {
            $item->quantity += 1;
            $item->save();
        } else {
            if ($item->quantity > 1) {
                $item->quantity -= 1;
                $item->save();
            } else {
                $item->delete();
            }
        }

        $this->recalculateOrder($item->order);

        if (request()->wantsJson()) {
            $order = Order::where('id', $item->order_id)->with('items.menu')->first();
            $cartCount = OrderItem::whereHas('order', function($q) {
                $q->where('user_id', Auth::id())->where('status', 'pending');
            })->sum('quantity');
            return response()->json([
                'success' => true, 
                'cartCount' => $cartCount,
                'totalPrice' => number_format($order->total_price, 0, ',', '.'),
                'itemQuantity' => $item->exists ? $item->quantity : 0,
                'itemId' => $id
            ]);
        }

        return redirect()->back();
    }

    public function removeCartItem($id)
    {
        $item = OrderItem::where('id', $id)->whereHas('order', function($q) {
            $q->where('user_id', Auth::id())->where('status', 'pending');
        })->firstOrFail();
        
        $order = $item->order;
        $item->delete();
        
        $this->recalculateOrder($order);

        if (request()->wantsJson()) {
            $cartCount = OrderItem::whereHas('order', function($q) {
                $q->where('user_id', Auth::id())->where('status', 'pending');
            })->sum('quantity');
            return response()->json([
                'success' => true, 
                'cartCount' => $cartCount,
                'totalPrice' => number_format($order->total_price, 0, ',', '.')
            ]);
        }

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    private function recalculateOrder($order)
    {
        $total = 0;
        foreach ($order->items as $item) {
            $total += ($item->price * $item->quantity);
        }
        $order->total_price = $total;
        $order->save();
    }

    public function checkout(Request $request)
    {
        $order = Order::where('user_id', Auth::id())->where('status', 'pending')->with('items.menu')->first();
        
        if (!$order || $order->items->count() == 0) {
            return request()->wantsJson() 
                ? response()->json(['success' => false, 'message' => 'Keranjang kosong!']) 
                : redirect()->back()->withErrors(['cart' => 'Keranjang kosong!']);
        }

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->menu_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $item->menu->name
            ];
        }

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id . '-' . time(), // append time to avoid duplicate order_id
                'gross_amount' => $order->total_price,
            ),
            'item_details' => $itemDetails,
            'customer_details' => array(
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email ?? 'customer@example.com',
                'phone' => Auth::user()->phone ?? '08111222333',
            ),
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Mark order as processing (or we can keep it pending until webhook, but for simplicity we'll just return token)
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $order = Order::where('user_id', Auth::id())->where('status', 'pending')->first();
        if ($order) {
            $order->status = 'completed';
            $order->save();
        }
        return redirect(route('home'))->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
    }
}
