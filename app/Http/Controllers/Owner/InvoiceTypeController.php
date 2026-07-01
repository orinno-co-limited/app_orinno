<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceTypeRequest;
use App\Services\InvoiceTypeService;

class InvoiceTypeController extends Controller
{
    public $invoiceTypeService;

    public function __construct()
    {
        $this->invoiceTypeService = new InvoiceTypeService;
    }

    public function index()
    {
        $data['pageTitle'] = __("Invoice Type");
        $data['subInvoiceTypeActiveClass'] = 'active';
        $data['invoiceTypes'] = $this->invoiceTypeService->getAll();
        return view('owner.setting.invoice-type')->with($data);
    }

    public function store(InvoiceTypeRequest $request)
    {
        return $this->invoiceTypeService->store($request);
    }

    public function destroy($id)
    {
        $this->invoiceTypeService->delete($id);
        return redirect()->back()->with('success', __(DELETED_SUCCESSFULLY));
    }
}
