<template>
    <div class="container configurator">
        <h2>Конфигуратор аккумуляторной сборки</h2>

        <!-- Предпросмотр -->
        <div class="preview">
            <h3>Предпросмотр</h3>
            <div class="product-preview" :style="previewStyle">
                <img :src="selectedProduct.image" :alt="selectedProduct.name">
            </div>
            <div class="price">Цена: {{ totalPrice }} ₽</div>
        </div>

        <!-- Опции конфигурации -->
        <div class="options">
            <div class="option-group" v-for="option in options" :key="option.id">
                <h4>{{ option.name }}</h4>
                <div class="option-items">
                    <button
                        v-for="item in option.items"
                        :key="item.id"
                        :class="['option-btn', { active: selectedOptions[option.id] === item.id }]"
                        @click="selectOption(option.id, item.id)"
                    >
                        {{ item.name }} (+{{ item.price }} ₽)
                    </button>
                </div>
            </div>
        </div>

        <!-- Итоговая конфигурация -->
        <div class="summary">
            <h3>Ваша конфигурация:</h3>
            <ul>
                <li v-for="option in options" :key="option.id">
                    <strong>{{ option.name }}:</strong> {{ getSelectedOptionName(option.id) }}
                </li>
            </ul>
            <button class="buy-btn" @click="addToCart">Добавить в корзину</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Configurator',
    data() {
        return {
            basePrice: 1000,
            selectedOptions: {
                color: 'black',
                size: 'medium',
                material: 'leather'
            },
            options: [
                {
                    id: 'color',
                    name: 'Цвет',
                    items: [
                        { id: 'black', name: 'Черный', price: 0, image: '/images/black.jpg' },
                        { id: 'white', name: 'Белый', price: 200, image: '/images/white.jpg' },
                        { id: 'red', name: 'Красный', price: 300, image: '/images/red.jpg' }
                    ]
                },
                {
                    id: 'size',
                    name: 'Размер',
                    items: [
                        { id: 'small', name: 'Маленький', price: 0 },
                        { id: 'medium', name: 'Средний', price: 500 },
                        { id: 'large', name: 'Большой', price: 1000 }
                    ]
                },
                {
                    id: 'material',
                    name: 'Материал',
                    items: [
                        { id: 'leather', name: 'Кожа', price: 0 },
                        { id: 'fabric', name: 'Ткань', price: -200 },
                        { id: 'plastic', name: 'Пластик', price: -500 }
                    ]
                }
            ]
        }
    },
    computed: {
        totalPrice() {
            let total = this.basePrice
            this.options.forEach(option => {
                const selectedItem = option.items.find(item =>
                    item.id === this.selectedOptions[option.id]
                )
                if (selectedItem) {
                    total += selectedItem.price
                }
            })
            return total
        },
        selectedProduct() {
            const colorOption = this.options.find(opt => opt.id === 'color')
            return colorOption.items.find(item => item.id === this.selectedOptions.color)
        },
        previewStyle() {
            return {
                border: '2px solid #ddd',
                padding: '20px',
                borderRadius: '8px'
            }
        }
    },
    methods: {
        selectOption(optionId, itemId) {
            this.selectedOptions[optionId] = itemId
        },
        getSelectedOptionName(optionId) {
            const option = this.options.find(opt => opt.id === optionId)
            const selectedItem = option.items.find(item =>
                item.id === this.selectedOptions[optionId]
            )
            return selectedItem ? selectedItem.name : ''
        },
        addToCart() {
            const configuration = {
                basePrice: this.basePrice,
                totalPrice: this.totalPrice,
                options: { ...this.selectedOptions },
                selectedOptions: this.options.map(option => ({
                    name: option.name,
                    value: this.getSelectedOptionName(option.id)
                }))
            }

            alert(`Товар добавлен в корзину! Итоговая цена: ${this.totalPrice} ₽`)
        }
    }
}
</script>

<style scoped>

</style>
