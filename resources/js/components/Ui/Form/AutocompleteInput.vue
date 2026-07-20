<template>
    <div class="autocomplete">
        <input
            type="text"
            :value="inputValue"
            @input="handleInput"
            @focus="showSuggestions = true"
            @blur="onBlur"
            @keydown.down="highlightNext"
            @keydown.up="highlightPrev"
            @keydown.enter="selectHighlighted"
            :placeholder="placeholder"
            class="autocomplete-input"
            ref="inputRef"
        >

        <div v-if="showSuggestions && filteredOptions.length > 0" class="suggestions-list">
            <div
                v-for="(option, index) in filteredOptions"
                :key="option.value"
                @mousedown="selectOption(option)"
                @mouseenter="highlightIndex = index"
                :class="[
          'suggestion-item',
          { 'highlighted': index === highlightIndex }
        ]"
            >
                <div class="suggestion-content">
                    <img
                        v-if="option.image"
                        :src="option.image"
                        :alt="option.label"
                        class="suggestion-image"
                    >
                    <div class="suggestion-icon" v-if="option.svg" v-html="option.svg"></div>
                    <span class="suggestion-label">{{ option.label }}</span>
                </div>
            </div>
        </div>

        <div v-if="showSuggestions && filteredOptions.length === 0" class="no-suggestions">
            Ничего не найдено
        </div>
    </div>
</template>

<script>
export default {
    name: 'AutocompleteInput',
    props: {
        modelValue: {
            type: [String, Number, Boolean],
            default: null
        },
        options: {
            type: Array,
            required: true,
            validator: (options) => options.every(opt =>
                typeof opt === 'object' && 'value' in opt && 'label' in opt
            )
        },
        placeholder: {
            type: String,
            default: 'Начните вводить...'
        }
    },
    emits: ['update:modelValue', 'select'],
    data() {
        return {
            inputValue: '',
            showSuggestions: false,
            highlightIndex: -1,
            selectedOption: null
        }
    },
    computed: {
        filteredOptions() {
            if (!this.inputValue) return this.options;

            const queryWords = this.inputValue
                .toLowerCase()
                .trim()
                .split(/\s+/)
                .filter(Boolean);

            return this.options.filter(option => {
                const label = option.label.toLowerCase();

                return queryWords.every(word => label.includes(word));
            });
        }
    },
    watch: {
        modelValue(newVal) {
            if (newVal !== this.selectedOption?.value) {
                this.findOptionByValue(newVal);
            }
        }
    },
    mounted() {
        this.findOptionByValue(this.modelValue);
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    methods: {
        handleInput(event) {
            this.inputValue = event.target.value;
            this.showSuggestions = true;
            this.highlightIndex = -1;

            this.$emit('update:modelValue', this.inputValue);

            if (this.selectedOption && this.inputValue !== this.selectedOption.label) {
                this.selectedOption = null;
                this.$emit('update:modelValue', null);
            }
        },

        selectOption(option) {
            this.selectedOption = option;
            this.inputValue = option.label;
            this.showSuggestions = false;
            this.$emit('update:modelValue', option.value);
            this.$emit('select', option);
        },

        highlightNext() {
            if (this.highlightIndex < this.filteredOptions.length - 1) {
                this.highlightIndex++;
            }
        },

        highlightPrev() {
            if (this.highlightIndex > 0) {
                this.highlightIndex--;
            }
        },

        selectHighlighted() {
            if (this.highlightIndex >= 0 && this.filteredOptions[this.highlightIndex]) {
                this.selectOption(this.filteredOptions[this.highlightIndex]);
            }
        },

        onBlur() {
            setTimeout(() => {
                this.showSuggestions = false;
            }, 200);
        },

        findOptionByValue(value) {
            if (value) {
                const option = this.options.find(opt => opt.value === value);
                if (option) {
                    this.selectedOption = option;
                    this.inputValue = option.label;
                }
            }
        },

        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showSuggestions = false;
            }
        }

        // Метод highlightText удален полностью
    }
}
</script>

<style scoped>
.autocomplete {
    position: relative;
    width: 100%;
}

.suggestions-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #e0e0e0;
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.suggestion-item {
    padding: 12px 0px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-item:hover,
.suggestion-item.highlighted {
    background-color: #f5f5f5;
}

.suggestion-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.suggestion-image {
    width: 30px;
    height: 30px;
    object-fit: cover;
    border-radius: 4px;
}

.suggestion-icon {
    width: 20px;
    height: 20px;
    color: #666;
}

.suggestion-label {
    flex: 1;
}

.no-suggestions {
    padding: 12px 16px;
    color: #666;
    font-style: italic;
    background: white;
    border: 2px solid #e0e0e0;
    border-top: none;
    border-radius: 0 0 8px 8px;
}
</style>
