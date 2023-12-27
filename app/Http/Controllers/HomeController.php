<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;


use App\Models\User;

use App\Models\Product;

use App\Models\Cart;

use App\Models\Order;


use Stripe;

class HomeController extends Controller
{
    public function redirect()
    {
        $usertype=Auth::user()->usertype;

        if($usertype=='1')
        {
            return view('admin.home');
        }

        else
        {
            return view('user.home');
        }
    }


    public function index()
    {
        if(Auth::id())
        {
            return redirect('/redirect');
        }

        else
        {
            $data = Product::all();
            return view('user.home', compact('data'));
        }

    }

    public function products()
    {
        $product=Product::all();
        return view('user.products', compact('product'));
    }

    public function single($id)
    {
        $product=product::find($id);
        return view('user.single', compact('product'));
    }

    public function contact()
    {
        return view('user.contact');
    }


    public function shoulder()
    {
        $product=Product::all();
        return view('user.shoulder', compact('product'));
    }

    public function tote()
    {
        $product=Product::all();
        return view('user.tote', compact('product'));
    }

    public function sling()
    {
        $product=Product::all();
        return view('user.sling', compact('product'));
    }

    public function add_cart(Request $request, $id)
    {
        if(Auth::check())
        {
            $user=Auth::user();
            $product=product::find($id);

            $cart=new cart;

            $cart->name=$user->name;
            $cart->email=$user->email;
            $cart->phone=$user->phone;
            $cart->address=$user->address;
            $cart->user_id=$user->id;

            $cart->product_title=$product->title;
            $cart->price=$product->price;
            $cart->image=$product->image;
            $cart->product_id=$product->id;
            $cart->quantity=$request->quantity;

            $cart->save();
            return redirect('cart')->with('info', 'Success added to cart.');

        }

        else
        {
            return redirect('login')->with('info', 'Please login to add the product to your cart.');
        }
    }

    public function cart()
    {
        if(Auth::id())
        {
            $id=Auth::user()->id;

            $cart=cart::where('user_id', '=', $id)->get();

            return view('user.cart', compact('cart'));
        }

        else
        {
            return redirect('login');
        }
        
    }

    public function remove_cart($id)
    {
        $cart=cart::find($id);

        $cart->delete();

        return redirect()->back();
    }

    public function add_order(Request $request)
{
    if(Auth::check())
    {
        $user = Auth::user();
        $userid = $user->id;

        $data = Cart::where('user_id', '=', $userid)->get();

        foreach ($data as $item)
        {
            $order = new Order;
            // sesuaikan dengan kolom-kolom yang sesuai dalam tabel order
            $order->name = $item->name;
            $order->email = $item->email;
            $order->phone = $item->phone;
            $order->address = $item->address;
            $order->user_id = $item->user_id;

            $order->product_title = $item->product_title;
            $order->price = $item->price;
            $order->quantity = $item->quantity;
            $order->image = $item->image;
            $order->product_id = $item->product_id;

            $order->payment_status = 'unpaid';
            $order->delivery_status = 'processing';

            $order->save();

        }

        // hapus item dari keranjang setelah ditambahkan ke pesanan
        Cart::where('user_id', '=', $userid)->delete();
        return view('user.add_order');
    }
}

        public function search_allproduct(Request $request)
        {
            $searchProduct = $request->input('search_allproduct');

            $products = Product::where('title', 'like', '%' . $searchProduct . '%')
                ->orWhere('description', 'like', '%' . $searchProduct . '%')
                ->get();

            return view('User.products', compact('products'));
        }
    }
    
    
















