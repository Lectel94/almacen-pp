@props(['selected', 'dishId', 'options'])

<div class="w-full max-w-sm mx-auto">

    <select id="status-select-{{ $dishId }}" wire:change="statusChanged($event.target.value, {{ $dishId }})"
        class="block w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @foreach ($options as $item)
        <option value="{{ $item->id }}" @if ($item->id==$selected) selected @endif>
            {{ $item->description }}
        </option>
        @endforeach
    </select>
</div>