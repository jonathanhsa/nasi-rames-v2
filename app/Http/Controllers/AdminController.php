<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalUsers = User::where('is_admin', false)->count();
        $orders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        return view('admin.dashboard', compact('totalOrders', 'totalRevenue', 'totalUsers', 'orders'));
    }

    public function menus()
    {
        $menus = Menu::all();
        return view('admin.menus', compact('menus'));
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/menus');
            $data['image_url'] = str_replace('public/', 'storage/', $path);
        }

        Menu::create($data);
        return back()->with('success', 'Menu added');
    }

    public function editMenu($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menus_edit', compact('menu'));
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/menus');
            $data['image_url'] = str_replace('public/', 'storage/', $path);
        }

        $menu->update($data);
        return redirect()->route('admin.menus')->with('success', 'Menu updated successfully');
    }

    public function deleteMenu($id)
    {
        Menu::destroy($id);
        return back()->with('success', 'Menu deleted');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_admin = $request->has('is_admin');
        $user->save();
        return back()->with('success', 'User updated');
    }

    public function deleteUser($id)
    {
        User::destroy($id);
        return back()->with('success', 'User deleted');
    }

    public function orders()
    {
        $orders = Order::with('user', 'items.menu')->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return back()->with('success', 'Order status updated');
    }
}
