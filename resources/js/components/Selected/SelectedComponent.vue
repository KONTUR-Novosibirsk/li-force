<template>
    <section class="selected">
        <div class="container">
            <div class="selected__heading">
                <h2>Избранное</h2>
                <div class="selected__delete-all" @click="removeAllFavorites" v-if="favoriteProducts.length">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19V7H6V19ZM8 9H16V19H8V9ZM15.5 4L14.5 3H9.5L8.5 4H5V6H19V4H15.5Z" fill="#808080"/>
                    </svg>
                    Удалить все
                </div>
            </div>

            <div v-if="favoriteProducts.length" class="selected__products__list">
                <div v-for="product in favoriteProducts" :key="product.id" class="products-item">
                    <span class="products-item__delete" @click="removeFromFavorites(product.id)">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.76738 10.634L2.23405 17.1674C2.11172 17.2889 1.96581 17.3842 1.80529 17.4472C1.64478 17.5103 1.47307 17.5398 1.30071 17.534C1.12836 17.5398 0.956639 17.5103 0.796129 17.4472C0.635618 17.3842 0.489708 17.2889 0.367378 17.1674C0.245824 17.045 0.150588 16.8991 0.08753 16.7386C0.0244722 16.5781 -0.00507327 16.4064 0.000711336 16.234C-0.00491571 16.0617 0.0247023 15.89 0.0877513 15.7295C0.1508 15.5691 0.245952 15.4231 0.367378 15.3007L6.90071 8.76738L0.367378 2.23404C0.245824 2.11171 0.150588 1.9658 0.08753 1.80529C0.0244722 1.64478 -0.00507327 1.47307 0.000711336 1.30071C-0.00491571 1.12837 0.0247023 0.956703 0.0877513 0.796215C0.1508 0.635727 0.245952 0.489801 0.367378 0.367377C0.489708 0.245823 0.635618 0.150588 0.796129 0.08753C0.956639 0.0244722 1.12836 -0.00507327 1.30071 0.000711336C1.47305 -0.00491571 1.64472 0.0247023 1.80521 0.0877513C1.9657 0.1508 2.11162 0.245952 2.23405 0.367377L8.76738 6.90071L15.3007 0.367377C15.423 0.245823 15.5689 0.150588 15.7295 0.08753C15.89 0.0244722 16.0617 -0.00507327 16.234 0.000711336C16.4064 -0.00491571 16.5781 0.0247023 16.7385 0.0877513C16.899 0.1508 17.045 0.245952 17.1674 0.367377C17.2889 0.489707 17.3842 0.635617 17.4472 0.796128C17.5103 0.956639 17.5398 1.12836 17.534 1.30071C17.5398 1.47307 17.5103 1.64478 17.4472 1.80529C17.3842 1.9658 17.2889 2.11171 17.1674 2.23404L10.634 8.76738L17.1674 15.3007C17.2889 15.4231 17.3841 15.569 17.4471 15.7295C17.5102 15.89 17.5397 16.0617 17.534 16.234C17.5398 16.4064 17.5103 16.5781 17.4472 16.7386C17.3842 16.8991 17.2889 17.045 17.1674 17.1674C17.045 17.2889 16.8991 17.3842 16.7386 17.4472C16.5781 17.5103 16.4064 17.5398 16.234 17.534C16.0617 17.5398 15.89 17.5103 15.7295 17.4472C15.5689 17.3842 15.423 17.2889 15.3007 17.1674L8.76738 10.634Z" fill="#333333"/>
                        </svg>
                    </span>
                    <a :href="product.url" class="products-item__img">
                        <img :src="product.images[0]?.preview_url" :alt="product.name">
                    </a>
                    <div class="products-item__content">
                        <div class="products-item__price">
                            {{ product.price }} ₽
                        </div>
                        <div class="products-item__title">
                            <a :href="product.url">
                                {{ product.name }}
                            </a>
                        </div>
                        <div class="products-item__buttons">
                            <a class="products-item__btn"
                               @click="addToCart(product, $event)"
                               :data-product="JSON.stringify(getCartProductData(product))">
                                В корзину
                            </a>
                            <div class="selected-btn active" @click="removeFromFavorites(product.id)">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.2246 4.98517C15.989 4.43961 15.6493 3.94523 15.2244 3.5297C14.7993 3.11293 14.298 2.78173 13.7479 2.55411C13.1774 2.31715 12.5656 2.19586 11.9479 2.19728C11.0813 2.19728 10.2357 2.43458 9.50098 2.88282C9.3252 2.99005 9.1582 3.10782 9 3.23615C8.8418 3.10782 8.6748 2.99005 8.49902 2.88282C7.76426 2.43458 6.91875 2.19728 6.05215 2.19728C5.42812 2.19728 4.82344 2.31681 4.25215 2.55411C3.7002 2.78263 3.20273 3.11134 2.77559 3.5297C2.35019 3.94476 2.01037 4.43926 1.77539 4.98517C1.53105 5.55294 1.40625 6.15587 1.40625 6.77638C1.40625 7.36173 1.52578 7.97169 1.76309 8.5922C1.96172 9.11075 2.24648 9.64864 2.61035 10.1918C3.18691 11.0514 3.97969 11.9479 4.96406 12.8567C6.59531 14.3631 8.21074 15.4037 8.2793 15.4459L8.6959 15.7131C8.88047 15.8309 9.11777 15.8309 9.30234 15.7131L9.71895 15.4459C9.7875 15.402 11.4012 14.3631 13.0342 12.8567C14.0186 11.9479 14.8113 11.0514 15.3879 10.1918C15.7518 9.64864 16.0383 9.11075 16.2352 8.5922C16.4725 7.97169 16.592 7.36173 16.592 6.77638C16.5938 6.15587 16.4689 5.55294 16.2246 4.98517ZM9 14.3227C9 14.3227 2.74219 10.3131 2.74219 6.77638C2.74219 4.98517 4.22402 3.53322 6.05215 3.53322C7.33711 3.53322 8.45156 4.2504 9 5.29806C9.54844 4.2504 10.6629 3.53322 11.9479 3.53322C13.776 3.53322 15.2578 4.98517 15.2578 6.77638C15.2578 10.3131 9 14.3227 9 14.3227Z" fill="black" fill-opacity="0.88"/>
                                </svg>
                            </div>
                            <div class="compared-btn"
                                 :class="{active: compareStates[product.id] }"
                                 @click="toggleCompare(product.id, $event)">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.4375 15.8906L14.4375 8.85938C14.4375 8.78203 14.3742 8.71875 14.2969 8.71875L13.1719 8.71875C13.0945 8.71875 13.0312 8.78203 13.0312 8.85937L13.0312 15.8906C13.0312 15.968 13.0945 16.0312 13.1719 16.0312L14.2969 16.0312C14.3742 16.0312 14.4375 15.968 14.4375 15.8906ZM4.96875 15.8906L4.96875 5.10937C4.96875 5.03203 4.90547 4.96875 4.82812 4.96875L3.70312 4.96875C3.62578 4.96875 3.5625 5.03203 3.5625 5.10937L3.5625 15.8906C3.5625 15.968 3.62578 16.0312 3.70312 16.0312L4.82812 16.0312C4.90547 16.0312 4.96875 15.968 4.96875 15.8906ZM9.70312 15.8906L9.70312 2.10937C9.70312 2.03203 9.63984 1.96875 9.5625 1.96875L8.4375 1.96875C8.36016 1.96875 8.29687 2.03203 8.29687 2.10937L8.29687 15.8906C8.29687 15.968 8.36016 16.0312 8.4375 16.0312L9.5625 16.0312C9.63984 16.0312 9.70312 15.968 9.70312 15.8906Z" fill="black" fill-opacity="0.88"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="selected__empty">
                <p>В избранном пока нет товаров</p>
            </div>
        </div>
    </section>
    <div v-if="showDeleteAllPopup" class="cart-popup__container" style="">
        <div class="cart-popup"><div class="cart-popup__heading">Удалить все товары из избранных?</div>
            <div class="cart-popup__content">
                <button class="cart-popup__submit" @click="confirmDeleteAll">Удалить все</button>
                <button class="cart-popup__close" @click="cancelDeleteAll">Отмена</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { storageManager } from '../../utils/storageManager.js'
import Cart from '../../cart/Cart.js'

const favoriteProducts = ref([])
const compareStates = reactive({})

onMounted(() => {
    loadFavorites()
})

const loadFavorites = () => {
    const favorites = storageManager.getFavorites()
    const compareProducts = storageManager.getCompare()

    favoriteProducts.value = favorites

    // Инициализируем состояния для каждого товара
    favorites.forEach(product => {
        compareStates[product.id] = compareProducts.some(p => p.id === product.id)
    })
}

// Подготавливаем данные для корзины
const getCartProductData = (product) => {
    return {
        id: product.id,
        name: product.name,
        price: product.price,
        // Добавьте другие необходимые поля
    }
}

const addToCart = (product, event) => {
    // Вызываем метод addToCart из класса Cart
    Cart.addToCart(event)
}

const toggleCompare = (productId, event) => {
    if (event) event.stopPropagation();

    const product = favoriteProducts.value.find(p => p.id === productId)
    if (!product) return

    // Сохраняем текущее состояние ДО изменения
    const currentState = compareStates[productId]

    if (currentState) {
        // Удаляем из сравнения
        storageManager.removeFromCompare(productId)
        compareStates[productId] = false
        showNotification('Товар удален из сравнения')
    } else {
        // Добавляем в сравнение
        storageManager.addToCompare(product)
        compareStates[productId] = true
        showNotification('Товар добавлен в сравнение')
    }
}

const removeFromFavorites = (productId) => {
    storageManager.removeFromFavorites(productId)
    favoriteProducts.value = favoriteProducts.value.filter(p => p.id !== productId)
    delete compareStates[productId]
}

const showDeleteAllPopup = ref(false)
const removeAllFavorites = () => {
    showDeleteAllPopup.value = true

}
const confirmDeleteAll = () => {
    storageManager.setFavorites([])
    favoriteProducts.value = []
    // Очищаем все состояния
    Object.keys(compareStates).forEach(key => {
        delete compareStates[key]
    })
    showDeleteAllPopup.value = false

}
const cancelDeleteAll = () => {
    showDeleteAllPopup.value = false
}
const showNotification = (message) => {
    console.log(message)
}
</script>

<style scoped lang="scss">
</style>
