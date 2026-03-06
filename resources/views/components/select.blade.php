<div class="w-full">
    @if(isset($label))
        <label class="block mb-1 font-semibold text-gray-700">
            {{ $label }}
        </label>
    @endif

    <select
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select w-full rounded border-gray-300']) }}
    >
        @if(isset($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $option)
            <option
                value="{{ $option[$optionValue] }}"
                @selected($selected == $option[$optionValue])
            >
                {{ $option[$optionLabel] }}
            </option>
        @endforeach
    </select>
</div>
