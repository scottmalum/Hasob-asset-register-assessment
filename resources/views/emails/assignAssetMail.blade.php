@component('mail::message')

Dear {{$data['name']}}

You have been assigned {{$data['quantity']}} quantity of  {{$data['asset_name']}}.

You now have a total quantity of: {{$data['assignAsset']->quantity}} {{$data['asset_name']}} assigned to you.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
