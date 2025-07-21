@props(['name', 'placeholder' => 'Sélectionnez des options...', 'searchPlaceholder' => 'Rechercher...', 'options' => [], 'selected' => []])

@php
    $uniqueId = 'multiselect_' . uniqid();
@endphp

<div x-data="multiselect(@js($options), @js($selected), '{{ $name }}', '{{ $uniqueId }}')" 
     x-init="init()" 
     class="relative">
    
    <!-- Selected items / Input trigger -->
    <div @click="toggle()" 
         class="w-full min-h-[48px] px-4 py-3 bg-white border border-neutral-300 rounded-xl cursor-pointer focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-transparent transition-all duration-200 hover:border-neutral-400 shadow-sm">
        
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <!-- Selected tags -->
                <div class="flex flex-wrap gap-2 mb-2" x-show="selectedItems.length > 0">
                    <template x-for="item in selectedItems" :key="item.value">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-primary-100 to-accent-100 text-primary-800 border border-primary-200/50">
                            <span x-text="item.label"></span>
                            <button @click.stop="remove(item)" type="button" class="ml-2 text-primary-600 hover:text-primary-800 transition-colors">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    </template>
                </div>
                
                <!-- Placeholder / Search input -->
                <div class="flex items-center">
                    <span x-show="selectedItems.length === 0 && !isOpen" 
                          class="text-neutral-500 text-sm">{{ $placeholder }}</span>
                    <input x-show="isOpen"
                           x-ref="searchInput" 
                           x-model="search"
                           @keydown.escape="close()"
                           @keydown.arrow-down.prevent="highlightNext()"
                           @keydown.arrow-up.prevent="highlightPrevious()"
                           @keydown.enter.prevent="selectHighlighted()"
                           type="text" 
                           placeholder="{{ $searchPlaceholder }}"
                           class="w-full bg-transparent border-none outline-none text-sm">
                </div>
            </div>
            
            <!-- Arrow icon - toujours en haut à droite -->
            <div class="flex-shrink-0 ml-3 flex items-center">
                <svg class="w-5 h-5 text-neutral-400 transition-transform duration-200"
                     :class="{ 'rotate-180': isOpen }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Dropdown -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         @click.away="close()"
         class="absolute z-50 w-full mt-2 bg-white border border-neutral-300 rounded-xl shadow-lg max-h-60 overflow-auto">
        
        <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-sm text-neutral-500 text-center">
            Aucun résultat trouvé
        </div>
        
        <template x-for="(option, index) in filteredOptions" :key="option.value">
            <div @click="select(option)"
                 :class="{
                     'bg-primary-50': highlightedIndex === index,
                     'bg-primary-100': isSelected(option)
                 }"
                 class="px-4 py-3 cursor-pointer hover:bg-neutral-50 flex items-center justify-between transition-colors duration-150">
                <span x-text="option.label" class="text-sm"></span>
                <svg x-show="isSelected(option)" 
                     class="w-4 h-4 text-primary-600" 
                     fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </template>
    </div>
    
    <!-- Hidden inputs for form submission -->
    <template x-for="item in selectedItems" :key="item.value">
        <input type="hidden" :name="name + '[]'" :value="item.value">
    </template>
</div>

<script>
function multiselect(options, selected, name, uniqueId) {
    return {
        isOpen: false,
        search: '',
        selectedItems: [],
        highlightedIndex: -1,
        name: name,
        
        init() {
            // Initialize selected items
            this.selectedItems = options.filter(option => selected.includes(option.value.toString()));
        },
        
        get filteredOptions() {
            if (!this.search) return options;
            return options.filter(option => 
                option.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        
        toggle() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.$refs.searchInput && this.$refs.searchInput.focus();
                });
            }
        },
        
        close() {
            this.isOpen = false;
            this.search = '';
            this.highlightedIndex = -1;
        },
        
        select(option) {
            if (this.isSelected(option)) {
                this.remove(option);
            } else {
                this.selectedItems.push(option);
            }
        },
        
        remove(option) {
            this.selectedItems = this.selectedItems.filter(item => item.value !== option.value);
        },
        
        isSelected(option) {
            return this.selectedItems.some(item => item.value === option.value);
        },
        
        highlightNext() {
            this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.filteredOptions.length - 1);
        },
        
        highlightPrevious() {
            this.highlightedIndex = Math.max(this.highlightedIndex - 1, -1);
        },
        
        selectHighlighted() {
            if (this.highlightedIndex >= 0 && this.filteredOptions[this.highlightedIndex]) {
                this.select(this.filteredOptions[this.highlightedIndex]);
            }
        }
    }
}
</script>
