@props(['active'])

<div x-data="{
        activeTab: '{{ $active }}',
        tabs: [],
        tabHeadings: [],
        toggleTabs() {
            this.tabs.forEach(
                tab => tab.__x.$data.showIfActive(this.activeTab)
            );
        }
     }"
     x-init="() => {
        tabs = [...$refs.tabs.children];
        tabHeadings = tabs.map((tab, index) => {
            tab.__x.$data.id = (index + 1);
            return tab.__x.$data.name;
        });
        toggleTabs();
     }"
>
    <div class="flex h-16 space-x-8 mb-5"
         role="tablist"
    >
        <template x-for="(tab, index) in tabHeadings"
                  :key="index"
        >
            <button x-text="tab"
                    @click.prevent="activeTab = tab; toggleTabs();"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none"
                    :class="tab === activeTab ? 'border-red-800 text-gray-900 focus:border-red-900 text-gray-500 hover:text-gray-700 hover:border-gray-300' : 'border-transparent focus:text-gray-700 focus:border-gray-300'"
                    :id="`tab-${index + 1}`"
                    role="tab"
                    :aria-selected="(tab === activeTab).toString()"
                    :aria-controls="`tab-panel-${index + 1}`"
            ></button>
        </template>
    </div>

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>
