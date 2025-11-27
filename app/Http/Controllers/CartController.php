<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart=session()->get('cart',[]);
        return view('cart.index',compact('cart'));
    }

    public function add(Game $game)
    {
        $cart=session()->get('cart',[]);
        if(isset($cart[$game->id])){
            $cart[$game->id]['quantity']++;
        } else{
            $cart[$game->id]=[
                'title'=>$game->title,
                'price'=>$game->price,
                'quantity'=>1,
                'image'=>$game->getFirstMediaUrl('images', 'thumb')
            ];
        }
        session()->put('cart',$cart);
        return back()->with('success', 'Game Added To The Cart!');
    }

    public function remove(Game $game)
    {
        $cart=session()->get('cart',[]);
        if(isset($cart[$game->id])){
            unset($cart[$game->id]);
            session()->put('cart',$cart);
        }
        return back()->with('success','Item Removed');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart Cleared!');
    }
}
