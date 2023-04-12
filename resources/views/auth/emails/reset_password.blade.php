<x-mail::message>
PIN Code message

here is your pin code



<x-mail::button :url="''">
{{ $code }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
