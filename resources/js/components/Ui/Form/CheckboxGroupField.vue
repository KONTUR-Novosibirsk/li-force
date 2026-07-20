<template>
    <div class="">
        <div class="filter-label">
            {{ label }}
            <span class="filter-label_arrow" @click.stop="show = !show">
                <svg :class="{ 'active': show }" style="transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                    <path d="M15.7044 3.77785C15.5067 3.59311 15.2727 3.5 15.0016 3.5C14.7328 3.5 14.498 3.59237 14.3004 3.77711L8.5099 9.18999L2.69964 3.75864C2.51545 3.58646 2.28462 3.5 2.00795 3.5C1.73127 3.5 1.49411 3.59237 1.29649 3.77711C1.20166 3.86186 1.12644 3.96387 1.07545 4.07687C1.02446 4.18986 0.998798 4.31142 1.00004 4.43404C1.00004 4.68529 1.09886 4.90476 1.29649 5.0895L7.95655 11.2968C8.0356 11.3707 8.12176 11.4224 8.21346 11.4542C8.30874 11.4852 8.40901 11.5007 8.5099 11.5C8.61583 11.5 8.71465 11.4845 8.80634 11.4542C8.90342 11.4202 8.99119 11.3664 9.06326 11.2968L15.7233 5.07103C15.9083 4.89811 16 4.68603 16 4.43331C16 4.18132 15.902 3.96259 15.7044 3.77785Z" fill="#2D2D2D"/>
                </svg>
            </span>
        </div>
        <div class="categories-container" :style="{maxHeight: show ? (filteredData.length * 40 + 20) + 'px' : '0',opacity: show ? '1' : '0'}">
            <div class="products-filter__category" v-for="item in filteredData" @click="select(item)">
                <input type="checkbox" :checked="selected(item)">
                <label>{{ item.value }}</label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CheckboxGroupField",
    props: {
        label: null,
        required: {
            type: Boolean,
            default: false,
        },
        name: String,
        modelValue: [Array],
        data: Object,
        errors: {
            type: Array,
            default: null,
        }
    },
    watch: {
        modelValue() {
            if (typeof this.modelValue === 'string') {
                this.$emit('update:modelValue', null);
            }
        }
    },
    data() {
        return {
            defaultSelected: {
                key: 0, value: 'Не выбрано',
            },
            search: '',
            show: false,
        }
    },
    computed: {
        selectedItems() {
            let selected = []
            if (this.modelValue) {
                if (this.modelValue.length === 0) {
                    return null
                }
                for (let item in this.data) {
                    if (this.modelValue.includes(this.data[item].key)) {
                        selected.push(this.data[item])
                    }
                }
                return selected
            }
            return null

        },
        filteredData() {
            return this.data.filter((element) => {
                return element.value.toLowerCase().indexOf(this.search) > -1;
            });
        }
    },
    methods: {
        selected(item) {
            if (this.modelValue) {
                return this.modelValue.includes(item.key)
            }
            return false
        },
        showOptions() {
            this.show = !this.show;
        },
        hideOptions() {
            this.show = false
        },
        async select(item) {
            let modelValue
            if (this.modelValue) {
                modelValue = this.modelValue
            } else {
                modelValue = []
            }
            for (let itemValue in modelValue) {
                if (modelValue[itemValue] === item.key) {
                    modelValue.splice(itemValue, 1)
                    if (modelValue.length === 0) {
                        modelValue = null
                    }
                    this.$emit('update:modelValue', modelValue);
                    this.$emit('change');
                    return true
                }
            }
            modelValue.push(item.key)
            this.$emit('update:modelValue', modelValue);
            this.$emit('change');
        }
    }
}
</script>

<style scoped>
.filter-label_arrow svg {
    transition: transform 0.3s ease;
}

.filter-label_arrow svg.active {
    transform: rotate(180deg);
}

/* Анимация контейнера */
.categories-container {
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
