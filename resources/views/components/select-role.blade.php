@props(['selected', 'dishId', 'options'])

<div class="w-full max-w-sm mx-auto">

    <select id="dish-select-{{ $dishId }}" wire:change="roleChanged($event.target.value, {{ $dishId }})"
        class="block w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @foreach ($options as $id => $name)
        <option value="{{ $id }}" @if ($id==$selected) selected @endif>
            {{ $name }}
        </option>
        @endforeach
    </select>
</div>