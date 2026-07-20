<template>
    <div class="select" v-click-outside="hideOptions">
        <div class="filter-label">
            {{ label }}
        </div>
        <div class="select-item">
            <div class="select-head" @click="showOptions">
                <div v-if="selectedItem">
                    <span class="selected__item">
                        {{ selectedItem.name }}
                    </span>
                </div>
                <template v-else>
                    Ничего не выбрано
                </template>
            </div>
            <ul class="select-body" v-show="show">
                <li class="select-option"
                    v-for="item in filteredData"
                    :class="{selected: selected(item)}"
                    @click="select(item)">{{ item.name }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "SelectInputField",
    props: {
        label: null,
        required: {
            type: Boolean,
            default: false,
        },
        name: String,
        modelValue: [Number, String, null],
        data: {
            type: Array,
            default: () => []
        },
        errors: {
            type: Array,
            default: null,
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
        selectedItem() {
            if (!this.modelValue) return null;

            return this.data.find(item => item.key === this.modelValue) || null;

        },
        filteredData() {
            return this.data.filter((element) => {
                return element.name.toLowerCase().indexOf(this.search) > -1;
            });
        }
    },
    methods: {
        selected(item) {
            return this.modelValue === item.key;
        },
        showOptions() {
            this.show = !this.show;
        },
        hideOptions() {
            this.show = false
        },
        select(item) {
            if (this.modelValue === item.key) {
                this.$emit('update:modelValue', null);
            } else {
                this.$emit('update:modelValue', item.key);
            }

            this.$emit('change');
            this.show = false;
        }
    }
}
</script>

<style scoped>

</style>
