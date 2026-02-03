@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#BFB07C] focus:ring-[#BFB07C] rounded-md shadow-sm']) }}>
