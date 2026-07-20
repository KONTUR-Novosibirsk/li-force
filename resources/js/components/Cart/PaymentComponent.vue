<template>
    <div class="payment-radiogroups">
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
            selectedOption: '',
            radioOptions: [
                {
                    value: 'option1',
                    label: 'Банковской картой',
                    svg:'<svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9766 0.501196C21.7313 0.496751 22.3078 0.501196 22.5938 0.576767C22.7625 0.62122 22.9922 0.705682 23.1094 0.767917C23.2266 0.834597 23.4234 0.99463 23.5453 1.12354C23.6719 1.25691 23.8172 1.47473 23.8734 1.60809C23.9672 1.83035 23.9766 1.94149 24 3.96412H0L0.0140625 2.91057C0.0234375 1.94593 0.0328125 1.83035 0.126563 1.60809C0.182813 1.47473 0.328125 1.25691 0.454687 1.12354C0.576562 0.99463 0.773438 0.834597 0.890625 0.767917C1.00781 0.705682 1.22812 0.62122 1.38281 0.576767C1.64531 0.510087 2.40469 0.501196 11.9766 0.501196ZM0 6.23125H23.9859L23.9766 15.1442L23.8734 15.3887C23.8172 15.522 23.6719 15.7398 23.5453 15.8732C23.4234 16.0021 23.2266 16.1622 23.1094 16.2288C22.9922 16.2911 22.7625 16.3755 22.5938 16.42C22.3078 16.4956 21.7266 16.5 12 16.5C2.27344 16.5 1.69219 16.4956 1.40625 16.42C1.2375 16.3755 1.00781 16.2911 0.890625 16.2288C0.773438 16.1622 0.576562 16.0021 0.454687 15.8732C0.328125 15.7398 0.182813 15.522 0.0234375 15.1442L0 6.23125ZM2.39062 10.7655H8.39062V8.49838H2.39062V10.7655Z" fill="#44A300"/>\n' +
                        '</svg>\n'
                },
                {
                    value: 'option2',
                    label: 'Перевод на р/с ООО "ЛиФорс" (ОСН, с НДС)',
                },
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
        // если ничего не выбрано, выбираем первый элемент
        if (!this.modelValue && this.radioOptions.length > 0) {
            this.$emit('update:modelValue', this.radioOptions[0].label)
        }
    }
}

</script>

<style scoped>
</style>
