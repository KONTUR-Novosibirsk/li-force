
<<template>
    <div class="form-mb checkbox-wrap" :class="{ shake: errors }" >
        <label>
            <input type="checkbox" :class="{ 'is-invalid': errors }" :name="name"
                   :placeholder="placeholder" :value="modelValue" @input="updateInput">
            <span>
               <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L5.33333 9L2 5.5M11.9999 2L5.33319 9" stroke="white" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round"/>
               </svg>
           </span>
        </label>
        <div v-for="error in errors" class="!text-[#E32525] text-[0.8rem] checkbox-error">
            {{ error }}
        </div>
    </div>
</template>

<script>
export default {
    name: "InputField",
    props: {
        placeholder: null,
        label: {
            type: String,
            default: 'text',
        },
        name: String,
        modelValue: [String, Number],
        errors: {
            type: Array,
            default: null,
        }
    },
    methods: {
        updateInput(event) {
            this.$emit('update:modelValue', event.target.value);
        }
    }
}
</script>

<style scoped>

</style>
