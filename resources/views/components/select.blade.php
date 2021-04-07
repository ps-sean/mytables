<select {{ $attributes->merge(["class" => "px-3 py-2 border-b"]) }} {{ $attributes['disabled'] ? 'disabled' : '' }}>
    {{ $slot }}
</select>
