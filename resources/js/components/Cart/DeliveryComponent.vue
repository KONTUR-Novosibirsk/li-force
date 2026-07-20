<template>
    <div class="delivery-radiogroups">
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
    name: 'DeliveryOptions',
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
                { value: 'option1', label: 'DPD classic', image: '/images/delivery-dpd.svg' },
                { value: 'option2', label: 'CDEK - до пункта выдачи', image: '/images/delivery-cdek.svg' },
                { value: 'option3', label: 'Почта России', image: '/images/delivery-post.svg' },
                { value: 'option4', label: 'Деловые линии', image: '/images/delivery-delovye-linii.svg' },
                { value: 'option5', label: 'Самовывоз' }
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
                if (found) {
                    this.$emit('update:modelValue', found.label)
                }
            }
        }
    }
}
</script>
