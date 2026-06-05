<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<header x-data="{ open: false }" class="sticky top-0 left-0 right-0 bg-white shadow-sm border-b z-[9999]"
    @keydown.escape.window="open = false">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-6">

        <div class="flex items-center justify-between h-16 sm:h-20">

            <x-layouts.logo />
            <x-layouts.desktop-navigation />

            <x-layouts.desktop-actions />
            <x-layouts.menu-button />
        </div>


        <x-layouts.mobile-menu />
    </div>

</header>
