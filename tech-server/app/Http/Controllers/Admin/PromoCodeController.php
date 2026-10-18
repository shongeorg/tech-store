<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoCodeController extends Controller
{
    public function index(): View
    {
        return view('admin.promos.index', [
            'promos' => PromoCode::query()->orderBy('id')->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'unique:promo_codes,code'],
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        PromoCode::create($data + ['is_active' => true]);

        return back()->with('toast', 'Промокод створено');
    }

    public function toggle(PromoCode $promoCode)
    {
        $promoCode->update(['is_active' => ! $promoCode->is_active]);

        return back()->with('toast', $promoCode->is_active ? 'Промокод активовано' : 'Промокод вимкнено');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return back()->with('toast', 'Промокод видалено');
    }
}
