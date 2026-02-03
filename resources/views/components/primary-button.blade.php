<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#BFB07C] border border-transparent rounded-md font-semibold text-xs text-[#272125] hover:text-[#BFB07C] uppercase tracking-widest hover:bg-[#272125] focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
