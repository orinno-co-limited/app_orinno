@if (count($tenants) > 0)
    <div>
        <div class="bg-light fw-bold px-2 py-1 text-end text-muted">{{ __('Tenants') }}</div>
        <ul class="list-group list-group-flush">
            @foreach ($tenants as $tenant)
                <li class="list-group-item">
                    <a
                        href="{{ route('owner.tenant.details', [$tenant->id, 'tab' => 'profile']) }}">{{ $tenant->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

@if (count($properties) > 0)
    <div>
        <div class="bg-light fw-bold px-2 py-1 text-end text-muted">{{ __('Properties') }}</div>
        <ul class="list-group list-group-flush">
            @foreach ($properties as $property)
                <li class="list-group-item">
                    <a href="{{ route('owner.property.show', $property->id) }}">{{ $property->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

@if (count($invoices) > 0)
    <div>
        <div class="bg-light fw-bold px-2 py-1 text-end text-muted">{{ __('Invoices') }}</div>
        <ul class="list-group list-group-flush">
            @foreach ($invoices as $invoice)
                <li class="list-group-item">
                    <a
                        href="{{ route('owner.invoice.index', ['id' => $invoice->id, 'tab' => 'view']) }}">{{ $invoice->invoice_no }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
