<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $data['pageTitle'] = __('Currency List');
        $data['subCurrencyActiveClass'] = 'active';
        $data['currencies'] = Currency::all();
        return view('admin.setting.currency', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code',
            'symbol' => 'required',
            'currency_placement' => 'required',
        ]);
        DB::beginTransaction();
        try {
        $currency = new Currency();
        $currency->currency_code = $request->currency_code;
        $currency->symbol = $request->symbol;
        $currency->currency_placement = $request->currency_placement;
        $currency->current_currency = $request->current_currency ?? DEACTIVATE;
        $currency->save();
        if ($request->current_currency == ACTIVE) {
            Currency::where('id', $currency->id)->update(['current_currency' => ACTIVE]);
            Currency::where('id', '!=', $currency->id)->update(['current_currency' => DEACTIVATE]);
        }
            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies,currency_code,' . $id,
            'symbol' => 'required',
            'currency_placement' => 'required',
        ]);
        DB::beginTransaction();
        try {
        $currency = Currency::findOrFail($id);
        $currency->currency_code = $request->currency_code;
        $currency->symbol = $request->symbol;
        $currency->currency_placement = $request->currency_placement ?? DEACTIVATE;
        $currency->save();
        if ($request->current_currency) {
            Currency::where('id', $currency->id)->update(['current_currency' => ACTIVE]);
            Currency::where('id', '!=', $currency->id)->update(['current_currency' => DEACTIVATE]);
        }
            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function delete($id)
    {
        $currency = Currency::findOrFail($id);

        if ($currency->current_currency == ACTIVE) {
            return redirect()->back()->with('error', __('You Cannot delete current currency.'));
        } else if(Currency::count() == 1){
            return redirect()->back()->with('error', __('You need minimum one currency to running this application.'));
        }

        $currency->delete();
        return redirect()->back()->with('success', __('Deleted Successfully.'));
    }
}
