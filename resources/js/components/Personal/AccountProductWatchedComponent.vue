<template>
    <div class="resent-products" v-if="watchedProducts && watchedProducts.length">
        <div class="resent-products__title">Вы недавно смотрели</div>
        <div class="resent-products__list">
            <div class="products-item" v-for="product in watchedProducts" :key="product.id">
                <a :href="product.url" class="products-item__img">
                    <img :src="product.images.length ? product.images[0].full : ''" :alt="product.title">
                </a>

                <div class="products-item__content">
                    <div class="products-item__title">
                        <a :href="product.url">
                            {{ product.title }}
                        </a>
                    </div>
                    <div class="products-item__price">
                        <div class="products-item__price_new">
                            {{ product.price }} ₽ / шт
                        </div>
                        <div class="products-item__price_old" v-if="product.old_price">
                            {{ product.old_price }} ₽
                        </div>
                    </div>
                    <div class="products-item__buttons">
                        <a class="products-item__btn js__add_to_cart" @click.prevent="addToCart(product)" :data-product="JSON.stringify(getCartProductData(product))">
                            В корзину
                        </a>

                        <div class="selected-btn" :class="{ active: isInFavorites(product.id) }" @click="toggleFavorite(product)">
                            <svg width="27" height="24" viewBox="0 0 27 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.50994 2.23297C6.65827 2.1978 6.9819 2.13187 7.22912 2.08791C7.47634 2.04396 7.84942 2.00879 8.06068 2.0044C8.27194 2 8.67198 2.03956 8.95965 2.08791C9.24283 2.14066 9.65187 2.23736 9.86762 2.31209C10.0879 2.38242 10.389 2.5011 10.5419 2.57582C10.6992 2.65055 10.9958 2.82637 11.2071 2.96264C11.4184 3.0989 11.7914 3.40659 12.0386 3.64835C12.2904 3.89011 12.605 4.23736 12.7443 4.41758C12.8792 4.5978 13.0994 4.91429 13.2253 5.12088C13.3512 5.32747 13.468 5.51648 13.477 5.53846C13.492 5.56777 13.507 5.56777 13.522 5.53846C13.531 5.51648 13.6478 5.32747 13.7737 5.12088C13.8995 4.91429 14.1108 4.60659 14.2411 4.43956C14.3715 4.27253 14.6277 3.97363 14.8075 3.78462C14.9918 3.5956 15.3334 3.2967 15.5671 3.12527C15.8009 2.95385 16.156 2.72967 16.3537 2.62857C16.5515 2.53187 16.8662 2.3956 17.0504 2.33407C17.2347 2.26813 17.6348 2.16703 17.9404 2.10549C18.2551 2.04396 18.6461 2 18.8619 2C19.0642 2 19.4822 2.04396 19.7923 2.09231C20.1025 2.14505 20.5385 2.24615 20.7587 2.31648C20.979 2.39121 21.3565 2.54505 21.5903 2.66374C21.824 2.77802 22.1791 2.99341 22.3769 3.13407C22.5747 3.27473 22.8848 3.53846 23.0691 3.71868C23.2534 3.8989 23.4961 4.16264 23.6085 4.30769C23.7209 4.45275 23.9051 4.72088 24.0175 4.9011C24.1299 5.08132 24.3052 5.41978 24.4086 5.64835C24.512 5.87692 24.6513 6.24615 24.7142 6.46154C24.7772 6.67692 24.8715 7.09451 24.921 7.38462C24.9974 7.81538 25.0109 8.03956 24.9929 8.61538C24.9839 9.0022 24.939 9.4989 24.8985 9.71429C24.8581 9.92967 24.7457 10.3385 24.6513 10.6154C24.5569 10.8923 24.3726 11.3275 24.2423 11.5824C24.1119 11.8374 23.8917 12.2242 23.7523 12.4396C23.6175 12.6549 23.3298 13.0637 23.114 13.3407C22.9028 13.6176 22.4982 14.1055 22.2151 14.4176C21.9364 14.7297 21.3296 15.3538 20.8711 15.8022C20.4126 16.2506 19.7159 16.9055 19.3204 17.2571C18.9248 17.6088 18.3585 18.1011 18.0618 18.356C17.7651 18.6066 17.1089 19.1516 16.601 19.5648C16.093 19.978 15.1986 20.6945 14.6097 21.156C14.0254 21.622 13.531 22 13.5085 22C13.4905 22 12.9916 21.622 12.3982 21.1604C11.8049 20.6945 10.8565 19.9385 10.2856 19.4725C9.71479 19.011 8.97763 18.4 8.64501 18.1143C8.31239 17.8286 7.71457 17.3011 7.31902 16.9451C6.92347 16.589 6.24474 15.9429 5.81324 15.5121C5.38173 15.0769 4.80638 14.4659 4.53669 14.1538C4.27149 13.8374 3.9119 13.3934 3.74559 13.1648C3.57478 12.9363 3.33206 12.5802 3.20171 12.3736C3.07136 12.167 2.8601 11.7846 2.72974 11.5165C2.59939 11.2484 2.43308 10.8527 2.35667 10.6374C2.28475 10.422 2.18137 10.044 2.12743 9.8022C2.0645 9.51648 2.01955 9.12967 2.00607 8.7033C1.99258 8.3033 2.00157 7.88571 2.03304 7.64835C2.0645 7.43297 2.13642 7.05494 2.19485 6.81319C2.25329 6.57143 2.36116 6.20659 2.44207 6C2.51848 5.79341 2.6803 5.43736 2.80616 5.20879C2.93201 4.98022 3.14777 4.63736 3.28711 4.44396C3.42645 4.25495 3.70513 3.94286 3.90291 3.74506C4.10068 3.55165 4.41533 3.28352 4.59962 3.14725C4.78391 3.01538 5.09855 2.82198 5.29632 2.71648C5.4941 2.61538 5.78627 2.47912 5.94808 2.41758C6.1099 2.35604 6.36161 2.27253 6.50994 2.23297Z" stroke="#535353" stroke-width="2.5"/>
                            </svg>
                        </div>

                        <div class="compared-btn" :class="{ active: compareStates[product.id] }" @click="toggleCompare(product)">
                            <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.14258 6.77637H1.25098C1.38321 6.79837 1.50603 6.8581 1.60059 6.95117C1.72021 7.06898 1.78613 7.22754 1.78613 7.39062V18.875C1.78613 19.0381 1.72026 19.1966 1.60059 19.3145C1.48062 19.4325 1.31561 19.5 1.14258 19.5C0.969727 19.4999 0.805438 19.4324 0.685547 19.3145C0.565846 19.1966 0.5 19.0381 0.5 18.875V7.40137C0.500056 7.23837 0.565986 7.08064 0.685547 6.96289C0.805454 6.84487 0.969646 6.77644 1.14258 6.77637ZM8 0.5C8.17288 0.5 8.33709 0.567655 8.45703 0.685547C8.57673 0.803364 8.64258 0.961861 8.64258 1.125V18.875C8.64258 19.0381 8.57673 19.1966 8.45703 19.3145C8.33709 19.4323 8.17289 19.5 8 19.5C7.82712 19.5 7.66291 19.4323 7.54297 19.3145C7.42327 19.1966 7.35742 19.0381 7.35742 18.875V1.125C7.35742 0.961861 7.42327 0.803364 7.54297 0.685547C7.66291 0.567654 7.82712 0.5 8 0.5ZM14.6846 12.2754C14.8131 12.2406 14.9498 12.2451 15.0752 12.2891C15.2005 12.333 15.3073 12.4141 15.3828 12.5186C15.4581 12.6228 15.4989 12.746 15.5 12.8721V18.8643C15.4999 19.0272 15.434 19.185 15.3145 19.3027C15.1945 19.4208 15.0304 19.4882 14.8574 19.4883C14.6844 19.4883 14.5194 19.4208 14.3994 19.3027C14.28 19.185 14.214 19.0271 14.2139 18.8643V12.8711C14.2145 12.7306 14.2653 12.5932 14.3584 12.4834L14.3594 12.4814C14.4427 12.3825 14.5561 12.3102 14.6846 12.2754Z" fill="#4F4F4F" stroke="#535353"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="no-products">
        <p>Вы еще не смотрели ни одного товара</p>
    </div>
</template>

<script>
import Cart from '../../cart/Cart.js'
import { storageManager } from '../../utils/storageManager.js'

export default {
    name: "AccountProductWatchedComponent",
    props: {
        watchedProducts: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            favoriteProducts: [],
            compareStates: {}
        }
    },
    mounted() {
        this.loadFavoriteProducts();
        this.loadCompareStates();

        // Добавляем обработчики событий для синхронизации
        window.addEventListener('favoritesUpdated', this.loadFavoriteProducts);
        window.addEventListener('compareUpdated', this.loadCompareStates);
    },
    beforeUnmount() {
        window.removeEventListener('favoritesUpdated', this.loadFavoriteProducts);
        window.removeEventListener('compareUpdated', this.loadCompareStates);
    },
    watch: {
        watchedProducts: {
            handler() {
                this.loadCompareStates();
            },
            deep: true
        }
    },
    methods: {
        getCartProductData(product) {
            return {
                id: product.id,
                title: product.title,
                price: product.price,
                old_price: product.old_price,
                images: product.images
            }
        },

        addToCart(product) {
            const event = {
                preventDefault: () => {},
                currentTarget: {
                    dataset: {
                        product: JSON.stringify(this.getCartProductData(product))
                    }
                }
            };
            Cart.addToCart(event);
        },

        loadFavoriteProducts() {
            this.favoriteProducts = storageManager.getFavorites();
        },

        loadCompareStates() {
            const compareProducts = storageManager.getCompare();
            // Обновляем состояния для всех товаров в просмотре
            this.watchedProducts.forEach(product => {
                this.compareStates[product.id] = compareProducts.some(p => p.id === product.id);
            });
        },

        isInFavorites(productId) {
            return this.favoriteProducts.some(item => item.id == productId);
        },

        isInCompare(productId) {
            return this.compareStates[productId] || false;
        },

        toggleFavorite(product) {
            if (this.isInFavorites(product.id)) {
                this.removeFromFavorites(product.id);
                this.showNotification('Товар удален из избранного');
            } else {
                this.addToFavorites(product);
                this.showNotification('Товар добавлен в избранное');
            }
        },

        addToFavorites(product) {
            storageManager.addToFavorites(product);
            this.loadFavoriteProducts();
            window.dispatchEvent(new CustomEvent('favoritesUpdated'));
        },

        removeFromFavorites(productId) {
            storageManager.removeFromFavorites(productId);
            this.loadFavoriteProducts();
            window.dispatchEvent(new CustomEvent('favoritesUpdated'));
        },

        toggleCompare(product) {
            if (this.isInCompare(product.id)) {
                storageManager.removeFromCompare(product.id);
                this.compareStates[product.id] = false;
            } else {
                storageManager.addToCompare(product);
                this.compareStates[product.id] = true;
            }
            // НЕ вызываем dispatchEvent
        },

        showNotification(message) {
            // Можно добавить всплывающее уведомление
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #333;
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
        }
    }
}
</script>

<style scoped>
.no-products {
    text-align: center;
    padding: 20px;
    color: #666;
}
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
