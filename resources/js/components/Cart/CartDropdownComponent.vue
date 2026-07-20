<template>
    <div v-if="productsData.length > 0" class="cart-dropdown">
        <div class="cart-dropdown__item" v-for="(product, index) in productsData" :key="product.hash || product.id">
            <a :href="product.url" class="cart-dropdown__item-img">
                <img v-if="product.first_preview" :src="product.first_preview" alt="{{ product.title }}">
                <img v-else src="/images/product-img.jpg" alt="{{ product.title }}">
            </a>
            <div class="cart-dropdown__item-info">
                <a :href="product.url" class="cart-dropdown__item-ttl">{{ product.title }}</a>
                <div class="cart-dropdown__item-price">
                    <span>{{ product.count }}</span> {{ product.price }} ₽
                </div>
            </div>
        </div>
        <a href="/cart" class="cart-dropdown__btn">
            В корзину · {{ totalPrice }} ₽
        </a>
    </div>
    <div class="cart-content__empty" v-if="productsData.length === 0">
        <svg width="40" height="34" viewBox="0 0 40 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.0165208L7.50971 0L8.91262 3.60153C9.68835 5.58402 11.2068 9.49944 14.2767 17.4459L35.0728 17.5285L35.7 12.1262C36.0466 9.13599 36.3767 6.42658 36.5583 5.46837H39.4466L39.3641 6.17877C39.3146 6.55875 38.968 9.51596 38.6049 12.7375C38.2417 15.9591 37.8951 19.0154 37.7136 20.4197L26.6058 20.4527C20.5155 20.4858 15.5146 20.5353 15.5146 20.5849C15.5146 20.6344 15.8117 21.4274 16.8184 24.0542L18.1058 24.1699C18.8155 24.2194 23.3544 24.5168 36.9874 25.3759L37.1359 25.9541C37.2184 26.268 37.3505 26.7636 37.5485 27.5732L34.7427 27.5071C33.2078 27.474 27.9427 27.3584 23.0573 27.2758C18.1883 27.1767 14.1117 27.0775 14.0291 27.028C13.9301 26.9784 13.8641 26.8462 13.8641 26.7306C13.8641 26.6315 11.9 21.3944 5.16602 3.73369L0 3.65109L0 0.0165208ZM13.6165 27.9366C14.2437 27.9366 14.6233 28.0357 15.1845 28.3992C15.6466 28.6966 16.0427 29.1096 16.2408 29.5061C16.4058 29.8696 16.5544 30.4973 16.5544 30.9104C16.5544 31.3399 16.4058 31.9512 16.1913 32.3477C15.9602 32.8213 15.6081 33.1902 15.135 33.4546C14.7553 33.6693 14.2272 33.9006 13.9466 33.9502C13.666 34.0163 13.1874 33.9832 12.8738 33.8676C12.5602 33.7685 12.0485 33.4876 11.735 33.2398C11.4214 33.0085 11.0252 32.5129 10.8602 32.1494C10.6951 31.786 10.5631 31.2408 10.5631 30.9434C10.5631 30.6625 10.6786 30.1339 10.8272 29.7869C10.9592 29.4565 11.2068 28.9939 11.3553 28.7627C11.5204 28.5479 11.9 28.267 12.2136 28.1514C12.5272 28.0357 13.1544 27.9531 13.6165 27.9366ZM35.2874 28.5975C35.667 28.5975 36.1786 28.6966 36.4262 28.8287C36.6903 28.9444 37.0864 29.2748 37.334 29.5722C37.5981 29.853 37.8456 30.3652 37.8951 30.6956C37.9447 31.0425 37.9447 31.5712 37.8951 31.852C37.8456 32.1494 37.5485 32.6616 37.2515 32.992C36.9709 33.3059 36.4262 33.6858 36.0631 33.8345C35.5019 34.0493 35.3039 34.0493 34.7427 33.8676C34.3796 33.7685 33.901 33.5537 33.6864 33.405C33.4718 33.2563 33.1583 32.8763 32.9602 32.5624C32.7291 32.1659 32.6301 31.7199 32.6466 31.1582C32.6631 30.5634 32.7786 30.1669 33.0757 29.7374C33.3068 29.4235 33.7524 29.027 34.033 28.8783C34.3301 28.7296 34.8913 28.5975 35.2709 28.5975H35.2874Z" fill="#2D2D2D"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.8389 0.015625C19.9709 0.015625 20.8126 0.75906 21.7204 1.6677C22.6282 2.57634 23.4369 3.31978 23.5359 3.31978C23.6185 3.31978 24.4437 2.57634 25.335 1.6677C26.2427 0.75906 27.068 0.015625 27.1505 0.015625C27.2495 0.015625 27.6622 0.34604 28.8175 1.50249L27.134 3.20413C26.1932 4.1293 25.434 5.0049 25.434 5.13706C25.434 5.26923 26.1932 6.12831 27.101 7.03695C28.5369 8.45774 28.7515 8.73859 28.6029 9.01944C28.5204 9.20117 28.2233 9.54811 27.9262 9.79592C27.6457 10.0603 27.2825 10.2585 27.1175 10.2585C26.969 10.2585 26.3913 9.79592 25.8466 9.23421C25.302 8.68903 24.5427 7.92907 23.4534 6.87174L21.7534 8.55686C20.8291 9.49855 19.9544 10.2585 19.8223 10.2585C19.6903 10.2585 19.2942 9.97765 18.9476 9.63071C18.6175 9.3003 18.3369 8.93684 18.3369 8.85424C18.3369 8.75511 19.0796 7.94559 19.9874 7.03695C20.8952 6.12831 21.6379 5.30227 21.6379 5.21967C21.6379 5.12054 20.8952 4.31102 19.9874 3.40238C19.0796 2.49374 18.3534 1.65118 18.3699 1.51901C18.3699 1.40337 18.6505 1.00687 18.9971 0.659935C19.3437 0.296478 19.7233 0.015625 19.8389 0.015625Z" fill="#44A300"/>
        </svg>
        <div class="cart-content__empty-ttl">Корзина пуста</div>
        <p>Выберите что-нибудь в каталоге</p>
        <a href="/shop" class="cart-content__empty-btn">Каталог</a>
    </div>
</template>

<script>
import DeleteModalComponent from "./DeleteModalComponent.vue";
import FlushModalComponent from "./FlushModalComponent.vue";
import Cart from "../../cart/Cart";
import OrderComponent from "./OrderComponent.vue";
import {ElNotification} from "element-plus";

export default {
    name: "CartDropdownComponent",
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

        // Методы для работы с избранным
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
