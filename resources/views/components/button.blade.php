<button {{ $attributes->class(['px-4 py-2 text-base rounded cursor-pointer border-[1px] border-blue-500 border-solid transition duration-300 disabled:cursor-not-allowed disabled:opacity-50','bg-blue-500 text-white hover:bg-transparent hover:text-blue-500'=>$variant==='primary','bg-transparent text-blue-500 hover:bg-blue-500 hover:text-white'=>$variant==='primary-outline']) }}>
    {{ $slot }}
</button>
