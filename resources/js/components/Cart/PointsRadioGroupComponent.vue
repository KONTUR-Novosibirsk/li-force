<template>
    <div class="radio-groups">
        <RadioGroup
            v-model="internalValue"
            name="options-group"
            :options="points"
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
        },
        points: Array
    },

    emits: ['update:modelValue'],

    computed: {
        internalValue: {
            get() {
                const found = this.points.find(opt => opt.label === this.modelValue)
                return found ? found.value : null
            },
            set(val) {
                const found = this.points.find(opt => opt.value === val)
                if (found) this.$emit('update:modelValue', found.label)
            }
        }
    },

    mounted() {

        if (!this.modelValue && this.points.length > 0) {
            this.$emit('update:modelValue', this.points[0].label)
        }
    }
}
</script>

<style scoped>
/* твои стили */
</style>
