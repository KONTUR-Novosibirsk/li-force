<template>
    <div class="">
        <div v-if="loading" class="loader-frame">
            <div class="loader"></div>
        </div>

        <div v-else class="account">
            <div class="flex profile-items">
                <p v-for="tab in tabs"
                   :key="tab.name"
                   :class="['cursor-pointer', { 'active': activeTab === tab.name }] "
                   @click="switchTab(tab.name)">
                    {{ tab.label }}
                </p>
            </div>

            <div class="profile-items-content">
                <div v-show="activeTab === 'history'" data-tab="history">
                    <AccountHistoryComponent :history="account.history" />
                </div>
                <div v-show="activeTab === 'personal_data'" data-tab="personal_data" class="profile-order_count">
                    <AccountPersonalDataComponent :personalData="account.personalData" />
                </div>
                <div v-show="activeTab === 'selected_product'" data-tab="product_watched">
                    <AccountSelectedProducts :watchedProducts="account.selectedProducts"/>
                </div>
                <div v-show="activeTab === 'product_watched'" data-tab="product_watched">
                    <AccountProductWatchedComponent :watchedProducts="account.watchedProducts"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AccountPersonalDataComponent from "@/components/Personal/AccountPersonalDataComponent.vue";
import AccountHistoryComponent from "@/components/Personal/AccountHistoryComponent.vue";
import AccountProductWatchedComponent from "@/components/Personal/AccountProductWatchedComponent.vue"
import AccountSelectedProducts from "@/components/Personal/AccountSelectedProducts.vue";

export default {
    name: "Account",
    components: {AccountSelectedProducts, AccountHistoryComponent, AccountPersonalDataComponent, AccountProductWatchedComponent },
    data() {
        return {
            loading: true,
            activeTab: 'history',
            account: {
                personalData: null,
                history: null,
                selectedProduct: null,
                watchedProducts: null
            },
            tabs: [
                { name: 'history', label: 'Мои заказы'},
                { name: 'personal_data', label: 'Личные данные'},
                { name: 'selected_product', label: 'Избранное'},
                { name: 'my_basket', label: 'Корзина'},
                { name: 'my_reviews', label: 'Мои отзывы'},
                { name: 'organization', label: 'Организации'},
            ],
        };
    },
    mounted() {
        // Считываем параметр tab из URL
        const urlParams = new URLSearchParams(window.location.search);
        const tabFromUrl = urlParams.get('tab');

        // Проверяем, есть ли такой таб в списке
        if (tabFromUrl && this.tabs.some(tab => tab.name === tabFromUrl)) {
            this.activeTab = tabFromUrl;
        }

        this.loadInitialData();
    },
    methods: {
        switchTab(tabName) {
            this.activeTab = tabName;
            // Обновляем URL без перезагрузки страницы
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        },

        async loadInitialData() {
            try {
                await Promise.all([
                    this.loadAccountData(),
                    this.loadHistory(),
                    this.loadWatchedProducts()
                ]);
            } finally {
                this.loading = false;
            }
        },

        async loadAccountData() {
            const response = await axios.get('/account/me/show');
            this.account.personalData = response.data;
        },

        async loadHistory() {
            const response = await axios.get('/account/me/history');
            this.account.history = response.data;
        },
        async loadSelectedProducts() {
            const response = await axios.get('/account/me/selected-products');
            this.account.selectedProduct = response.data;
        },
        async loadWatchedProducts() {
            const response = await axios.get('/account/me/watched-products');
            this.account.watchedProducts = response.data;
        }
    }
};
</script>
