@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-lg border border-red-200 bg-red-50 px-4 py-3']) }}>
        <ul class="list-disc list-inside text-base text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
