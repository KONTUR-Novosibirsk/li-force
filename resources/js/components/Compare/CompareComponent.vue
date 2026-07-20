<template>
    <section class="compared">
        <div class="container">
            <div class="compared__heading">
                <h2>Сравнение товаров</h2>
                <div v-if="compareList.length" class="selected__delete-all" @click="deleteAll">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19V7H6V19ZM8 9H16V19H8V9ZM15.5 4L14.5 3H9.5L8.5 4H5V6H19V4H15.5Z"
                            fill="#808080"/>
                    </svg>
                    Удалить все
                </div>
            </div>

            <div class="compared-products" :class="{ 'fixed': isFixed }">
                <div class="compared-slider">
                    <swiper
                        ref="productsSwiper"
                        :spaceBetween="25"
                        :slidesPerView="4"
                        :navigation="{
              prevEl: '.compared-btn-prev',
              nextEl: '.compared-btn-next'
            }"
                        :breakpoints="breakpoints"
                        :modules="modules"
                        @slideChange="onProductsSlideChange"
                        class="compared-swiper">

                        <swiper-slide v-for="(product, index) in compareList" :key="product.id">
                            <div class="products-item">
                                <a :href="product.url" class="products-item__img">
                                    <img :src="product.images[0]?.preview_url" :alt="product.name">
                                </a>
                                <div class="products-item__content">
                                    <div class="products-item__price">
                                        {{ product.price }} ₽
                                    </div>
                                    <div class="products-item__title">
                                        <a :href="product.url">{{ product.name }}</a>
                                    </div>

                                    <div class="products-item__buttons">
                                        <a class="products-item__btn"
                                           @click="addToCart(product, $event)"
                                           :data-product="JSON.stringify(getCartProductData(product))">
                                            В корзину
                                        </a>
                                        <div class="selected-btn" :class="{ active: isInFavorites(product.id) }"
                                             @click="toggleFavorite(product)">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.2246 4.98419C15.989 4.43863 15.6493 3.94425 15.2244 3.52872C14.7993 3.11196 14.298 2.78076 13.7479 2.55314C13.1774 2.31617 12.5656 2.19488 11.9479 2.1963C11.0813 2.1963 10.2357 2.43361 9.50098 2.88185C9.3252 2.98907 9.1582 3.10685 9 3.23517C8.8418 3.10685 8.6748 2.98907 8.49902 2.88185C7.76426 2.43361 6.91875 2.1963 6.05215 2.1963C5.42812 2.1963 4.82344 2.31583 4.25215 2.55314C3.7002 2.78165 3.20273 3.11036 2.77559 3.52872C2.35019 3.94378 2.01037 4.43828 1.77539 4.98419C1.53105 5.55197 1.40625 6.15489 1.40625 6.7754C1.40625 7.36075 1.52578 7.97071 1.76309 8.59122C1.96172 9.10978 2.24648 9.64767 2.61035 10.1908C3.18691 11.0504 3.97969 11.9469 4.96406 12.8557C6.59531 14.3621 8.21074 15.4027 8.2793 15.4449L8.6959 15.7121C8.88047 15.8299 9.11777 15.8299 9.30234 15.7121L9.71895 15.4449C9.7875 15.401 11.4012 14.3621 13.0342 12.8557C14.0186 11.9469 14.8113 11.0504 15.3879 10.1908C15.7518 9.64767 16.0383 9.10978 16.2352 8.59122C16.4725 7.97071 16.592 7.36075 16.592 6.7754C16.5938 6.15489 16.4689 5.55197 16.2246 4.98419ZM9 14.3217C9 14.3217 2.74219 10.3121 2.74219 6.7754C2.74219 4.98419 4.22402 3.53224 6.05215 3.53224C7.33711 3.53224 8.45156 4.24943 9 5.29708C9.54844 4.24943 10.6629 3.53224 11.9479 3.53224C13.776 3.53224 15.2578 4.98419 15.2578 6.7754C15.2578 10.3121 9 14.3217 9 14.3217Z" fill="black" fill-opacity="0.88"/>
                                            </svg>
                                        </div>
                                        <div class="compared__delete-item" @click="removeFromCompare(product.id)">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.4375 15.8906L14.4375 8.85938C14.4375 8.78203 14.3742 8.71875 14.2969 8.71875L13.1719 8.71875C13.0945 8.71875 13.0312 8.78203 13.0312 8.85937L13.0312 15.8906C13.0312 15.968 13.0945 16.0312 13.1719 16.0312L14.2969 16.0312C14.3742 16.0312 14.4375 15.968 14.4375 15.8906ZM4.96875 15.8906L4.96875 5.10937C4.96875 5.03203 4.90547 4.96875 4.82812 4.96875L3.70312 4.96875C3.62578 4.96875 3.5625 5.03203 3.5625 5.10937L3.5625 15.8906C3.5625 15.968 3.62578 16.0312 3.70312 16.0312L4.82812 16.0312C4.90547 16.0312 4.96875 15.968 4.96875 15.8906ZM9.70312 15.8906L9.70312 2.10937C9.70312 2.03203 9.63984 1.96875 9.5625 1.96875L8.4375 1.96875C8.36016 1.96875 8.29687 2.03203 8.29687 2.10937L8.29687 15.8906C8.29687 15.968 8.36016 16.0312 8.4375 16.0312L9.5625 16.0312C9.63984 16.0312 9.70312 15.968 9.70312 15.8906Z" fill="black" fill-opacity="0.88"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>

                        <swiper-slide>
                            <div class="products-item__add">
                                <a href="/shop">
                                    <svg width="77" height="77" viewBox="0 0 77 77" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="38.5" cy="38.5" r="38.5" fill="#D3FFB3"/>
                                        <circle cx="38.5" cy="38.5" r="24.5" fill="#44A300"/>
                                        <path d="M37.7165 47V40.2835H31V37.6989H37.7165V31H40.3011V37.6989H47V40.2835H40.3011V47H37.7165Z"
                                              fill="white"/>
                                    </svg>
                                </a>
                                Добавить товар к сравнению
                            </div>
                        </swiper-slide>
                    </swiper>

                    <button class="compared-btn-prev">
                        <svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.59961 15.6001L2.02106 9.24862C1.6068 8.84866 1.39961 8.64862 1.39961 8.4001C1.39961 8.15158 1.6068 7.95154 2.02106 7.55158L8.59961 1.2001"
                                stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="compared-btn-next">
                        <svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.40039 1.2002L7.97894 7.55168C8.3932 7.95164 8.60039 8.15167 8.60039 8.40019C8.60039 8.64871 8.3932 8.84875 7.97894 9.24871L1.40039 15.6002"
                                stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="compared-characteristics" v-if="compareList.length > 0">
                <h3 class="compared-characteristics__title">Сравнение характеристик</h3>

                <div v-for="(section, sectionIndex) in optimizedCharacteristicsSections" :key="sectionIndex"
                     class="compared-characteristics__section">
                    <div class="compared-characteristics__heading" @click="toggleSection(sectionIndex)">
                        {{ section.title }}
                        <svg width="17" height="10" viewBox="0 0 17 10" fill="none" xmlns="http://www.w3.org/2000/svg"
                             :class="{ 'active': section.isOpen }">
                            <path d="M15.9932 8.75L8.4965 2.25L0.999831 8.75" stroke="#2D2D2D" stroke-width="2"/>
                        </svg>
                    </div>

                    <div class="compared-characteristics__body" v-show="section.isOpen">
                        <div v-for="(char, charIndex) in section.characteristics" :key="charIndex"
                             class="characteristics-table">
                            <span>{{ char.name }}</span>
                            <swiper
                                :ref="el => setCharSwiperRef(el, sectionIndex, charIndex)"
                                :spaceBetween="20"
                                :slidesPerView="4"
                                :allowTouchMove="false"
                                :breakpoints="breakpoints"
                                :modules="modules"
                                @slideChange="onCharSlideChange"
                                class="characteristic-row">

                                <swiper-slide v-for="(value, productIndex) in char.values" :key="productIndex">
                                    <div class="characteristic-value">{{ value }}</div>
                                </swiper-slide>
                                <swiper-slide></swiper-slide>
                            </swiper>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div v-if="showDeleteAllPopup" class="cart-popup__container" style="">
        <div class="cart-popup"><div class="cart-popup__heading">Удалить все товары из сравнения?</div>
            <div class="cart-popup__content">
                <button class="cart-popup__submit" @click="confirmDeleteAll">Удалить все</button>
                <button class="cart-popup__close" @click="cancelDeleteAll">Отмена</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import Cart from '../../cart/Cart.js'
import {storageManager} from "@/utils/storageManager.js";


const modules = [Navigation]

// Рефы для свайперов
const productsSwiper = ref(null)

// Состояние
const isFixed = ref(false)
const compareProducts = ref([])
const sectionsState = ref({})
const favoriteProducts = ref([])

// Брейкпоинты
const breakpoints = {
    0: { slidesPerView: 2 },
    1000: { slidesPerView: 3 },
    1400: { slidesPerView: 4 }
}

// Вычисляемые свойства
const compareList = computed(() => compareProducts.value)

// Вычисляем структуру характеристик только при изменении compareProducts
const characteristicsStructure = computed(() => {
    const sections = []

    // Собираем все категории
    const categories = new Set()
    compareProducts.value.forEach(product => {
        if (product.shopAttributeCategory && product.shopAttributeCategory.attributes) {
            Object.keys(product.shopAttributeCategory.attributes).forEach(category => {
                categories.add(category)
            })
        }
    })

    // Создаем секции для каждой категории
    Array.from(categories).forEach(category => {
        const section = {
            title: category,
            characteristics: []
        }

        // Находим все атрибуты этой категории
        const categoryAttrs = new Set()
        compareProducts.value.forEach(product => {
            if (product.shopAttributeCategory &&
                product.shopAttributeCategory.attributes[category]) {
                product.shopAttributeCategory.attributes[category].forEach(attr => {
                    categoryAttrs.add(attr.name)
                })
            }
        })

        // Заполняем значения для каждого атрибута
        Array.from(categoryAttrs).forEach(attrName => {
            const char = {
                name: attrName,
                values: []
            }

            compareProducts.value.forEach(product => {
                let value = '—'
                if (product.shopAttributeCategory &&
                    product.shopAttributeCategory.attributes[category]) {
                    const foundAttr = product.shopAttributeCategory.attributes[category]
                        .find(a => a.name === attrName)
                    if (foundAttr) {
                        value = foundAttr.value
                    }
                }
                char.values.push(value)
            })

            section.characteristics.push(char)
        })

        sections.push(section)
    })

    return sections
})

// const characteristicsStructure = computed(() => {
//     if (!compareProducts.value.length) return []
//
//     const allAttributes = new Map()
//
//     // собираем все уникальные характеристики
//     compareProducts.value.forEach(product => {
//         const attrs = product.attributes || []
//
//         attrs.forEach(attr => {
//             if (!allAttributes.has(attr.name)) {
//                 allAttributes.set(attr.name, [])
//             }
//         })
//     })
//
//     // заполняем значения
//     allAttributes.forEach((_, attrName) => {
//         const values = compareProducts.value.map(product => {
//             const attrs = product.attributes || []
//             const found = attrs.find(a => a.name === attrName)
//             return found ? found.value : '—'
//         })
//
//         allAttributes.set(attrName, values)
//     })
//
//     return [
//         {
//             title: 'Характеристики',
//             characteristics: Array.from(allAttributes.entries()).map(([name, values]) => ({
//                 name,
//                 values
//             }))
//         }
//     ]
// })

// Оптимизированная версия секций с состоянием
const optimizedCharacteristicsSections = computed(() => {
    return characteristicsStructure.value.map((section, index) => {
        return {
            ...section,
            isOpen: sectionsState.value[index] !== false // По умолчанию открыто
        }
    })
})

// Метод для установки ссылок на свайперы характеристик
const setCharSwiperRef = (el, sectionIndex, charIndex) => {
    if (el) {

    }
}

// Загрузка данных
onMounted(() => {
    loadCompareProducts()
    loadFavoriteProducts()
    initScrollHandler()

    const savedSectionsState = localStorage.getItem('compareSectionsState')
    if (savedSectionsState) {
        sectionsState.value = JSON.parse(savedSectionsState)
    }

    window.addEventListener('favoritesUpdated', handleFavoritesUpdate)
    // Добавляем слушатель для обновления сравнения
    window.addEventListener('compareUpdated', handleCompareUpdate)
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
    window.removeEventListener('favoritesUpdated', handleFavoritesUpdate)
    // Убираем слушатель
    window.removeEventListener('compareUpdated', handleCompareUpdate)
})
// Обработчик событий обновления сравнения
const handleCompareUpdate = () => {
    loadCompareProducts() // Перезагружаем список при изменениях извне
    storageManager.updateHeaderCounters() // Обновляем счетчики
}
// Синхронизация свайперов
const syncSwipers = (targetIndex, sourceSwiper) => {
    // Синхронизируем основной слайдер
    const mainSwiper = productsSwiper.value?.swiper
    if (sourceSwiper !== mainSwiper && mainSwiper && mainSwiper.activeIndex !== targetIndex) {
        mainSwiper.slideTo(targetIndex)
    }

    // Синхронизируем характеристики через поиск по DOM
    const charSwiperElements = document.querySelectorAll('.characteristic-row')
    charSwiperElements.forEach(element => {
        if (element.swiper && element.swiper !== sourceSwiper && element.swiper.activeIndex !== targetIndex) {
            element.swiper.slideTo(targetIndex)
        }
    })
}

const onProductsSlideChange = (swiper) => {
    syncSwipers(swiper.activeIndex, swiper)
}

const onCharSlideChange = (swiper) => {
    syncSwipers(swiper.activeIndex, swiper)
}

const loadCompareProducts = () => {
    const stored = localStorage.getItem('compareProducts')
    if (stored) {
        compareProducts.value = JSON.parse(stored)
        nextTick(() => {
            setTimeout(() => {
                const mainSwiper = productsSwiper.value?.swiper
                if (mainSwiper) {
                    syncSwipers(mainSwiper.activeIndex, mainSwiper)
                }
            }, 300)
        })
    }
}
// Загрузка избранного
const loadFavoriteProducts = () => {
    const stored = localStorage.getItem('selectProducts')
    if (stored) {
        favoriteProducts.value = JSON.parse(stored)
    }
}

// Проверка, находится ли товар в избранном
const isInFavorites = (productId) => {
    return favoriteProducts.value.some(item => item.id == productId)
}

// Переключение избранного
const toggleFavorite = (product) => {
    if (isInFavorites(product.id)) {
        removeFromFavorites(product.id)
        showNotification('Товар удален из избранного')
    } else {
        addToFavorites(product)
        showNotification('Товар добавлен в избранное')
    }
}

// Добавление в избранное
const addToFavorites = (product) => {
    const favorites = [...favoriteProducts.value]
    if (!favorites.some(item => item.id === product.id)) {
        favorites.push(product)
        favoriteProducts.value = favorites
        localStorage.setItem('selectProducts', JSON.stringify(favorites))

        // Отправляем событие для обновления других компонентов
        window.dispatchEvent(new CustomEvent('favoritesUpdated'))
    }
}

// Удаление из избранного
const removeFromFavorites = (productId) => {
    const favorites = favoriteProducts.value.filter(item => item.id !== productId)
    favoriteProducts.value = favorites
    localStorage.setItem('selectProducts', JSON.stringify(favorites))

    // Отправляем событие для обновления других компонентов
    window.dispatchEvent(new CustomEvent('favoritesUpdated'))
}

// Функция уведомлений
const showNotification = (message) => {
    console.log(message)
}

// Обработчик событий обновления избранного
const handleFavoritesUpdate = () => {
    loadFavoriteProducts()
}

// Функция для обработки скролла
const initScrollHandler = () => {
    window.addEventListener('scroll', handleScroll)
    handleScroll()
}

const handleScroll = () => {
    const scrollThreshold = 500

    // Защита от ошибки - проверяем существование элемента
    const comparedProductsElement = document.querySelector('.compared-products')
    if (!comparedProductsElement) return

    isFixed.value = window.scrollY > scrollThreshold
}

const toggleSection = (index) => {
    // Инвертируем состояние конкретной секции
    sectionsState.value = {
        ...sectionsState.value,
        [index]: !sectionsState.value[index]
    }

    // Сохраняем состояние в localStorage
    localStorage.setItem('compareSectionsState', JSON.stringify(sectionsState.value))
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

const removeFromCompare = (productId) => {
    storageManager.removeFromCompare(productId)
    compareList.value = compareList.value.filter(p => p.id !== productId)
    delete compareStates[productId]
}


const showDeleteAllPopup = ref(false)
const deleteAll = () => {
    showDeleteAllPopup.value = true
}
const confirmDeleteAll = () => {
    storageManager.setCompare([])
    compareProducts.value = []
    showDeleteAllPopup.value = false

}
const cancelDeleteAll = () => {
    showDeleteAllPopup.value = false
}
</script>

<style scoped>

    .fixed {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .compared-characteristics__heading {
        cursor: pointer;
    }

    .compared-characteristics__heading svg {
        transition: transform 0.3s ease;
    }

    .compared-characteristics__heading svg.active {
        transform: rotate(180deg);
    }

    .characteristics-table span {
        min-width: 200px;
        font-weight: bold;
    }

    .characteristic-row {
        flex: 1;
        width: 100%;
    }

</style>


