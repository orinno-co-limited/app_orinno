<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $data['pageTitle'] = __('Currency List');
        $data['subCurrencyActiveClass'] = 'active';
        $data['currencies'] = Currency::all();
        return view('owner.setting.currency', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code',
            'symbol' => 'required',
            'currency_placement' => 'required',
        ]);

        $currency = new Currency();
        $currency->currency_code = $request->currency_code;
        $currency->symbol = $request->symbol;
        $currency->currency_placement = $request->currency_placement;
        $currency->save();
        if ($request->current_currency == ACTIVE) {
            Currency::where('id', $currency->id)->update(['current_currency' => ACTIVE]);
            Currency::where('id', '!=', $currency->id)->update(['current_currency' => DEACTIVATE]);
        }
        return redirect()->route('owner.setting.currency.index')->with('success', __('Created Successfully.'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code,' . $id,
            'symbol' => 'required',
            'currency_placement' => 'required',
        ]);

        $currency = Currency::findOrfail($id);
        $currency->currency_code = $request->currency_code;
        $currency->symbol = $request->symbol;
        $currency->currency_placement = $request->currency_placement;
        $currency->save();
        if ($request->current_currency) {
            Currency::where('id', $currency->id)->update(['current_currency' => ACTIVE]);
            Currency::where('id', '!=', $currency->id)->update(['current_currency' => DEACTIVATE]);
        }
        return redirect()->route('owner.setting.currency.index')->with('success', __('Updated Successfully.'));
    }

    public function delete($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return redirect()->back()->with('success', __('Deleted Successfully.'));
    }
}
