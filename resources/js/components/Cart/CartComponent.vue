<template>
    <div class="cart-container container">
        <h2>
            Корзина
            <span v-if="productsData.length > 0">{{ totalCount }}</span>
        </h2>

        <div class="cart-content__empty" v-if="productsData.length === 0">
            <svg width="40" height="34" viewBox="0 0 40 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.0165208L7.50971 0L8.91262 3.60153C9.68835 5.58402 11.2068 9.49944 14.2767 17.4459L35.0728 17.5285L35.7 12.1262C36.0466 9.13599 36.3767 6.42658 36.5583 5.46837H39.4466L39.3641 6.17877C39.3146 6.55875 38.968 9.51596 38.6049 12.7375C38.2417 15.9591 37.8951 19.0154 37.7136 20.4197L26.6058 20.4527C20.5155 20.4858 15.5146 20.5353 15.5146 20.5849C15.5146 20.6344 15.8117 21.4274 16.8184 24.0542L18.1058 24.1699C18.8155 24.2194 23.3544 24.5168 36.9874 25.3759L37.1359 25.9541C37.2184 26.268 37.3505 26.7636 37.5485 27.5732L34.7427 27.5071C33.2078 27.474 27.9427 27.3584 23.0573 27.2758C18.1883 27.1767 14.1117 27.0775 14.0291 27.028C13.9301 26.9784 13.8641 26.8462 13.8641 26.7306C13.8641 26.6315 11.9 21.3944 5.16602 3.73369L0 3.65109L0 0.0165208ZM13.6165 27.9366C14.2437 27.9366 14.6233 28.0357 15.1845 28.3992C15.6466 28.6966 16.0427 29.1096 16.2408 29.5061C16.4058 29.8696 16.5544 30.4973 16.5544 30.9104C16.5544 31.3399 16.4058 31.9512 16.1913 32.3477C15.9602 32.8213 15.6081 33.1902 15.135 33.4546C14.7553 33.6693 14.2272 33.9006 13.9466 33.9502C13.666 34.0163 13.1874 33.9832 12.8738 33.8676C12.5602 33.7685 12.0485 33.4876 11.735 33.2398C11.4214 33.0085 11.0252 32.5129 10.8602 32.1494C10.6951 31.786 10.5631 31.2408 10.5631 30.9434C10.5631 30.6625 10.6786 30.1339 10.8272 29.7869C10.9592 29.4565 11.2068 28.9939 11.3553 28.7627C11.5204 28.5479 11.9 28.267 12.2136 28.1514C12.5272 28.0357 13.1544 27.9531 13.6165 27.9366ZM35.2874 28.5975C35.667 28.5975 36.1786 28.6966 36.4262 28.8287C36.6903 28.9444 37.0864 29.2748 37.334 29.5722C37.5981 29.853 37.8456 30.3652 37.8951 30.6956C37.9447 31.0425 37.9447 31.5712 37.8951 31.852C37.8456 32.1494 37.5485 32.6616 37.2515 32.992C36.9709 33.3059 36.4262 33.6858 36.0631 33.8345C35.5019 34.0493 35.3039 34.0493 34.7427 33.8676C34.3796 33.7685 33.901 33.5537 33.6864 33.405C33.4718 33.2563 33.1583 32.8763 32.9602 32.5624C32.7291 32.1659 32.6301 31.7199 32.6466 31.1582C32.6631 30.5634 32.7786 30.1669 33.0757 29.7374C33.3068 29.4235 33.7524 29.027 34.033 28.8783C34.3301 28.7296 34.8913 28.5975 35.2709 28.5975H35.2874Z" fill="#2D2D2D"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.8389 0.015625C19.9709 0.015625 20.8126 0.75906 21.7204 1.6677C22.6282 2.57634 23.4369 3.31978 23.5359 3.31978C23.6185 3.31978 24.4437 2.57634 25.335 1.6677C26.2427 0.75906 27.068 0.015625 27.1505 0.015625C27.2495 0.015625 27.6622 0.34604 28.8175 1.50249L27.134 3.20413C26.1932 4.1293 25.434 5.0049 25.434 5.13706C25.434 5.26923 26.1932 6.12831 27.101 7.03695C28.5369 8.45774 28.7515 8.73859 28.6029 9.01944C28.5204 9.20117 28.2233 9.54811 27.9262 9.79592C27.6457 10.0603 27.2825 10.2585 27.1175 10.2585C26.969 10.2585 26.3913 9.79592 25.8466 9.23421C25.302 8.68903 24.5427 7.92907 23.4534 6.87174L21.7534 8.55686C20.8291 9.49855 19.9544 10.2585 19.8223 10.2585C19.6903 10.2585 19.2942 9.97765 18.9476 9.63071C18.6175 9.3003 18.3369 8.93684 18.3369 8.85424C18.3369 8.75511 19.0796 7.94559 19.9874 7.03695C20.8952 6.12831 21.6379 5.30227 21.6379 5.21967C21.6379 5.12054 20.8952 4.31102 19.9874 3.40238C19.0796 2.49374 18.3534 1.65118 18.3699 1.51901C18.3699 1.40337 18.6505 1.00687 18.9971 0.659935C19.3437 0.296478 19.7233 0.015625 19.8389 0.015625Z" fill="#44A300"/>
            </svg>
            <div class="cart-content__empty-ttl">Корзина пуста</div>
            <p>Выберите что-нибудь в каталоге</p>
            <a href="/shop" class="cart-content__empty-btn">Каталог</a>
        </div>

        <div class="cart-content">
            <div class="cart-content__left" v-if="productsData.length > 0">
                <div class="cart-item" v-for="(product, index) in productsData" :key="product.hash || product.id">
                    <div class="cart-item__left">
                        <a :href="product.url" class="cart-item__img">
                            <img v-if="product.first_preview" :src="product.first_preview" alt="">
                            <img v-else src="/images/product-img.jpg" alt="">
                        </a>
                    </div>
                    <div class="cart-item__right">
                        <div class="cart-item__ttl">
                            <div class="cart-item__code">Артикул: {{ product.article || '8512' }}</div>
                            <a :href="product.url" class="cart-item__link">{{ product.title }}</a>
                            <div class="cart-item__btns">
                                <button @click.stop="toggleFavorite(product)" :class="{ active: isInFavorites(product.id) }" class="favorite-btn">
                                    <svg width="18" height="18" viewBox="0 0 27 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.50994 2.23297C6.65827 2.1978 6.9819 2.13187 7.22912 2.08791C7.47634 2.04396 7.84942 2.00879 8.06068 2.0044C8.27194 2 8.67198 2.03956 8.95965 2.08791C9.24283 2.14066 9.65187 2.23736 9.86762 2.31209C10.0879 2.38242 10.389 2.5011 10.5419 2.57582C10.6992 2.65055 10.9958 2.82637 11.2071 2.96264C11.4184 3.0989 11.7914 3.40659 12.0386 3.64835C12.2904 3.89011 12.605 4.23736 12.7443 4.41758C12.8792 4.5978 13.0994 4.91429 13.2253 5.12088C13.3512 5.32747 13.468 5.51648 13.477 5.53846C13.492 5.56777 13.507 5.56777 13.522 5.53846C13.531 5.51648 13.6478 5.32747 13.7737 5.12088C13.8995 4.91429 14.1108 4.60659 14.2411 4.43956C14.3715 4.27253 14.6277 3.97363 14.8075 3.78462C14.9918 3.5956 15.3334 3.2967 15.5671 3.12527C15.8009 2.95385 16.156 2.72967 16.3537 2.62857C16.5515 2.53187 16.8662 2.3956 17.0504 2.33407C17.2347 2.26813 17.6348 2.16703 17.9404 2.10549C18.2551 2.04396 18.6461 2 18.8619 2C19.0642 2 19.4822 2.04396 19.7923 2.09231C20.1025 2.14505 20.5385 2.24615 20.7587 2.31648C20.979 2.39121 21.3565 2.54505 21.5903 2.66374C21.824 2.77802 22.1791 2.99341 22.3769 3.13407C22.5747 3.27473 22.8848 3.53846 23.0691 3.71868C23.2534 3.8989 23.4961 4.16264 23.6085 4.30769C23.7209 4.45275 23.9051 4.72088 24.0175 4.9011C24.1299 5.08132 24.3052 5.41978 24.4086 5.64835C24.512 5.87692 24.6513 6.24615 24.7142 6.46154C24.7772 6.67692 24.8715 7.09451 24.921 7.38462C24.9974 7.81538 25.0109 8.03956 24.9929 8.61538C24.9839 9.0022 24.939 9.4989 24.8985 9.71429C24.8581 9.92967 24.7457 10.3385 24.6513 10.6154C24.5569 10.8923 24.3726 11.3275 24.2423 11.5824C24.1119 11.8374 23.8917 12.2242 23.7523 12.4396C23.6175 12.6549 23.3298 13.0637 23.114 13.3407C22.9028 13.6176 22.4982 14.1055 22.2151 14.4176C21.9364 14.7297 21.3296 15.3538 20.8711 15.8022C20.4126 16.2506 19.7159 16.9055 19.3204 17.2571C18.9248 17.6088 18.3585 18.1011 18.0618 18.356C17.7651 18.6066 17.1089 19.1516 16.601 19.5648C16.093 19.978 15.1986 20.6945 14.6097 21.156C14.0254 21.622 13.531 22 13.5085 22C13.4905 22 12.9916 21.622 12.3982 21.1604C11.8049 20.6945 10.8565 19.9385 10.2856 19.4725C9.71479 19.011 8.97763 18.4 8.64501 18.1143C8.31239 17.8286 7.71457 17.3011 7.31902 16.9451C6.92347 16.589 6.24474 15.9429 5.81324 15.5121C5.38173 15.0769 4.80638 14.4659 4.53669 14.1538C4.27149 13.8374 3.9119 13.3934 3.74559 13.1648C3.57478 12.9363 3.33206 12.5802 3.20171 12.3736C3.07136 12.167 2.8601 11.7846 2.72974 11.5165C2.59939 11.2484 2.43308 10.8527 2.35667 10.6374C2.28475 10.422 2.18137 10.044 2.12743 9.8022C2.0645 9.51648 2.01955 9.12967 2.00607 8.7033C1.99258 8.3033 2.00157 7.88571 2.03304 7.64835C2.0645 7.43297 2.13642 7.05494 2.19485 6.81319C2.25329 6.57143 2.36116 6.20659 2.44207 6C2.51848 5.79341 2.6803 5.43736 2.80616 5.20879C2.93201 4.98022 3.14777 4.63736 3.28711 4.44396C3.42645 4.25495 3.70513 3.94286 3.90291 3.74506C4.10068 3.55165 4.41533 3.28352 4.59962 3.14725C4.78391 3.01538 5.09855 2.82198 5.29632 2.71648C5.4941 2.61538 5.78627 2.47912 5.94808 2.41758C6.1099 2.35604 6.36161 2.27253 6.50994 2.23297Z"
                                            :stroke="isInFavorites(product.id) ? '#FF2E2E' : '#535353'"
                                            :fill="isInFavorites(product.id) ? '#FF2E2E' : 'none'"
                                            stroke-width="2.5"/>
                                    </svg>
                                </button>
                                <button type="button" @click.stop.prevent="deleteProduct(index)" class="cart-item__delete">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.32812 3.23438H6.1875C6.26484 3.23438 6.32812 3.17109 6.32812 3.09375V3.23438H11.6719V3.09375C11.6719 3.17109 11.7352 3.23438 11.8125 3.23438H11.6719V4.5H12.9375V3.09375C12.9375 2.47324 12.433 1.96875 11.8125 1.96875H6.1875C5.56699 1.96875 5.0625 2.47324 5.0625 3.09375V4.5H6.32812V3.23438ZM15.1875 4.5H2.8125C2.50137 4.5 2.25 4.75137 2.25 5.0625V5.625C2.25 5.70234 2.31328 5.76562 2.39062 5.76562H3.45234L3.88652 14.959C3.91465 15.5584 4.41035 16.0312 5.00977 16.0312H12.9902C13.5914 16.0312 14.0854 15.5602 14.1135 14.959L14.5477 5.76562H15.6094C15.6867 5.76562 15.75 5.70234 15.75 5.625V5.0625C15.75 4.75137 15.4986 4.5 15.1875 4.5ZM12.8549 14.7656H5.14512L4.71973 5.76562H13.2803L12.8549 14.7656Z" fill="black" fill-opacity="0.88"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="cart-item__info">
                            <div class="cart-item__price">
                                <div class="cart-item__price_new">
                                    {{ product.price }} ₽
                                </div>
                                <div v-if="product.old_price > 0" class="cart-item__price_old">
                                    {{ product.old_price }} ₽
                                </div>
                            </div>
                            <div class="cart-item__count">
                                <button class="cart-minus" @click="decreaseCount(index)">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.3281 8.33203H2.67188C2.59453 8.33203 2.53125 8.39531 2.53125 8.47266V9.52734C2.53125 9.60469 2.59453 9.66797 2.67188 9.66797H15.3281C15.4055 9.66797 15.4688 9.60469 15.4688 9.52734V8.47266C15.4688 8.39531 15.4055 8.33203 15.3281 8.33203Z" fill="black" fill-opacity="0.88"/>
                                    </svg>
                                </button>
                                <input type="number" :value="productsData[index].count" class="cart-value"
                                       @input="updateCount(index, $event)" disabled>
                                <button class="cart-plus" @click="increaseCount(index)">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.47266 2.67188H9.52734C9.62109 2.67188 9.66797 2.71875 9.66797 2.8125V15.1875C9.66797 15.2812 9.62109 15.3281 9.52734 15.3281H8.47266C8.37891 15.3281 8.33203 15.2812 8.33203 15.1875V2.8125C8.33203 2.71875 8.37891 2.67188 8.47266 2.67188Z" fill="black" fill-opacity="0.88"/>
                                        <path d="M3.09375 8.33203H14.9062C15 8.33203 15.0469 8.37891 15.0469 8.47266V9.52734C15.0469 9.62109 15 9.66797 14.9062 9.66797H3.09375C3 9.66797 2.95312 9.62109 2.95312 9.52734V8.47266C2.95312 8.37891 3 8.33203 3.09375 8.33203Z" fill="black" fill-opacity="0.88"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cart-content__right" v-if="productsData.length > 0">
                <div class="cart-content__right-top">
                    <a href="#" class="cart-buy" data-src="#order-popup"
                       data-auto-focus="false" data-fancybox>Перейти к оформлению</a>
                    <div class="cart-total__heading">
                        <div>Ваша корзина</div>
                    </div>
                    <div class="cart-total__content cart-total__price">
                        <div class="cart-total__content_left">
                            Товары ({{ totalCount }})
                        </div>
                        <div class="cart-total__content_right">
                            {{ totalPrice }} ₽
                        </div>
                    </div>
                    <div class="cart-total__content cart-total__price">
                        <div class="cart-total__content_left">
                            Скидка
                        </div>
                        <div class="cart-total__content_right">
                            - {{ sale }} ₽
                        </div>
                    </div>
                    <div class="cart-total__bottom">
                        Цена с НДС 22%
                    </div>
                </div>
                <div class="cart-content__right-bottom">
                    Появились вопросы о товаре?
                    <a href="#" data-fancybox data-src="#feedback-popup">
                        Консультация специалиста
                    </a>
                </div>
            </div>
        </div>

    </div>
    <DeleteModalComponent @onReady="readyModal" @deleteProduct="confirmDelete"></DeleteModalComponent>
    <div id="order-popup" style="display: none;">
        <OrderComponent :user="user" :privacy-policy-link="privacyPolicyLink" :points="points"></OrderComponent>
    </div>
    <div class="loader-frame" v-if="loading">
        <div class="loader"></div>
    </div>
</template>

<script>
import DeleteModalComponent from "./DeleteModalComponent.vue";
import FlushModalComponent from "./FlushModalComponent.vue";
import Cart from "../../cart/Cart";
import OrderComponent from "./OrderComponent.vue";
import {ElNotification} from "element-plus";

export default {
    name: "CartComponent",
    components: {OrderComponent, FlushModalComponent, DeleteModalComponent},
    props: {
        accounts: {
            type: [Object, String],
            default: () => ({})
        },
        user: null,
        privacyPolicyLink: null,
        products: [],
        points: Array
    },
    created() {
        if (typeof this.accounts === 'string') {
            try {
                this.localAccount = JSON.parse(this.accounts);
            } catch (e) {
                this.localAccount = {};
            }
        } else {
            this.localAccount = this.accounts || {};
        }
        this.loadFavoriteProducts();
    },
    data() {
        return {
            delete_index: null,
            modal: null,
            totalPrice: 0,
            totalOldPrice: 0,
            sale: 0,
            totalCount: 0,
            productsData: this.products,
            localAccount: {},
            loading: false,
            deliveryCost: 1200,
            favoriteProducts: [],
        }
    },
    watch: {
        productsData: {
            handler(newValue) {
                this.computedPrice();
            },
            deep: true,
        }
    },
    mounted() {
        this.computedPrice();
        window.addEventListener('favoritesUpdated', this.loadFavoriteProducts);
    },
    beforeUnmount() {
        window.removeEventListener('favoritesUpdated', this.loadFavoriteProducts);
    },
    methods: {
        computedPrice() {
            let totalPrice = 0;
            let totalOldPrice = 0;
            let totalCount = 0;
            for (let index in this.productsData) {
                totalPrice += +this.productsData[index].price * +this.productsData[index].count
                totalOldPrice += Math.max(this.productsData[index].price, this.productsData[index].old_price) * +this.productsData[index].count
                totalCount += +this.productsData[index].count
            }
            this.totalPrice = totalPrice
            this.totalOldPrice = totalOldPrice
            this.sale = totalOldPrice - totalPrice
            this.totalCount = totalCount
            Cart.changeTotalCount(totalCount)
        },
        updateCount(index, event) {
            if (event.target.value > 0) {
                if (this.productsData[index].count > event.target.value) {
                    this.decreaseCount(index, +this.productsData[index].count - +event.target.value)
                } else {
                    this.increaseCount(index, +event.target.value - +this.productsData[index].count)
                }
            }
        },
        increaseCount(index, count = 1) {
            axios.post('/cart/add', {
                id: this.productsData[index].id,
                count: count,
            }).then((response) => {
                this.productsData[index].count = +this.productsData[index].count + count
            })
        },
        decreaseCount(index, count = 1, checkCount = true) {
            if (this.productsData[index].count - count <= 0 && checkCount) {
                this.deleteProduct(index)
                return
            }
            axios.post('/cart/remove', {
                hash: this.productsData[index].hash,
                count: count,
            }).then((response) => {
                this.productsData[index].count = +this.productsData[index].count - count
                if (this.productsData[index].count == 0) {
                    this.productsData.splice(index, 1);
                }
            })
        },
        readyModal(modal) {
            this.modal = modal
        },
        deleteProduct(index) {
            this.delete_index = index
            this.modal.show()
        },
        confirmDelete() {
            this.decreaseCount(this.delete_index, this.productsData[this.delete_index].count, false)
            this.delete_index = null
            this.modal.hide()
        },
        flushCart() {
            this.productsData = []
            Cart.changeTotalCount(0)
        },
        store() {
            this.loading = true
            let form = {
                user_name: this.localAccount.full_name,
                user_phone: this.localAccount.phone,
                user_email: this.localAccount.email,
            };

            axios.post('/shop/order/store', form).then((response) => {
                this.flushCart()
                this.completed = true
                this.error = []
                ElNotification({
                    title: 'Система',
                    message: 'Менеджер скоро свяжется с Вами.',
                    type: 'success',
                    position: 'bottom-right',
                })
            }).catch((error) => {
                if (error.response && error.response.status === 401) {
                    window.location.href = '/account/register';
                }
                ElNotification({
                    title: 'Система',
                    message: error.response.data.message,
                    type: 'error',
                    position: 'bottom-right',
                })
            }).finally(() => {
                this.loading = false
            })
        },

        loadFavoriteProducts() {
            const stored = localStorage.getItem('selectProducts')
            if (stored) {
                this.favoriteProducts = JSON.parse(stored)
            } else {
                this.favoriteProducts = []
            }
        },

        isInFavorites(productId) {
            return this.favoriteProducts.some(item => item.id == productId)
        },

        toggleFavorite(product) {
            if (this.isInFavorites(product.id)) {
                this.removeFromFavorites(product.id)
            } else {
                this.addToFavorites(product)
            }
        },

        addToFavorites(product) {
            const favorites = [...this.favoriteProducts]
            if (!favorites.some(item => item.id === product.id)) {
                // Создаем объект с правильной структурой ДЛЯ СТРАНИЦЫ ИЗБРАННЫХ
                const favoriteProduct = {
                    id: product.id,
                    name: product.title || product.name,  // Страница избранных использует name
                    price: product.price,
                    oldPrice: product.old_price,          // Страница избранных использует oldPrice (без подчеркивания)
                    url: product.url,
                    // ВАЖНО: страница избранных ожидает images массив с объектами, у которых есть preview_url
                    images: product.first_preview ? [{ preview_url: product.first_preview }] : [],
                    first_preview: product.first_preview, // Оставляем для совместимости
                    article: product.article,
                    shopAttributeCategory: product.shopAttributeCategory || null
                }

                favorites.push(favoriteProduct)
                this.favoriteProducts = favorites
                localStorage.setItem('selectProducts', JSON.stringify(favorites))
                window.dispatchEvent(new CustomEvent('favoritesUpdated'))

                ElNotification({
                    title: 'Избранное',
                    message: 'Товар добавлен в избранное',
                    type: 'success',
                    position: 'bottom-right',
                })
            }
        },

        removeFromFavorites(productId) {
            const favorites = this.favoriteProducts.filter(item => item.id !== productId)
            this.favoriteProducts = favorites
            localStorage.setItem('selectProducts', JSON.stringify(favorites))
            window.dispatchEvent(new CustomEvent('favoritesUpdated'))

            ElNotification({
                title: 'Избранное',
                message: 'Товар удален из избранного',
                type: 'info',
                position: 'bottom-right',
            })
        }
    }
}
</script>

<style scoped>

</style>
