<template>
    <div class="radio-group">
        <h4 v-if="title" class="group-title">{{ title }}</h4>
        <label
            v-for="option in options"
            :key="option.value"
            class="radio-option"
            :class="{ 'selected': modelValue === option.value }"
        >
            <input
                type="radio"
                :name="name"
                :value="option.value"
                :checked="modelValue === option.value"
                @change="$emit('update:modelValue', option.value)"
            >
            <span class="radio-custom"></span>
            <div class="option-content">
                <img
                    v-if="option.image"
                    :src="option.image"
                    :alt="option.label"
                    class="option-image"
                >
                <div class="option-icon" v-if="option.svg" v-html="option.svg"></div>
                <span class="option-label">{{ option.label }}</span>
            </div>
        </label>
    </div>
</template>

<script>
export default {
    name: 'RadioGroup',
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
        name: {
            type: String,
            required: true
        },
        title: {
            type: String,
            default: ''
        }
    },
    emits: ['update:modelValue']
}
</script>

<style scoped>
</style>
