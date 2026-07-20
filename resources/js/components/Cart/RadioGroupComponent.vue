<template>
    <div class="radio-groups">
        <RadioGroup
            v-model="internalValue"
            name="options-group"
            :options="radioOptions"
        />
    </div>
</template>

<script>
import RadioGroup from '../Ui/Form/RadioGroup.vue'

export default {
    name: 'RadioGroupComponent',
    components: { RadioGroup },

    props: {
        modelValue: {
            type: String,
            default: ''
        }
    },

    emits: ['update:modelValue'],

    data() {
        return {
            radioOptions: [
                { value: 'option1', label: 'Физическое лицо' },
                { value: 'option2', label: 'Юридическое лицо' }
            ]
        }
    },

    computed: {
        internalValue: {
            get() {
                const found = this.radioOptions.find(opt => opt.label === this.modelValue)
                return found ? found.value : null
            },
            set(val) {
                const found = this.radioOptions.find(opt => opt.value === val)
                if (found) this.$emit('update:modelValue', found.label)
            }
        }
    },

    mounted() {

        if (!this.modelValue && this.radioOptions.length > 0) {
            this.$emit('update:modelValue', this.radioOptions[0].label)
        }
    }
}
</script>

<style scoped>
/* твои стили */
</style>
