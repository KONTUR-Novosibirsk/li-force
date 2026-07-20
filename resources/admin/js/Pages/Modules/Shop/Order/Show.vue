<template>
    <el-row :gutter="20">
        <el-col :xs="24" :lg="14" class="mb-3">
            <el-card>
                <div class="mb-1">
                    <span class="fw-bold">Имя:</span> {{ orderData.user_name }}
                </div>
                <div class="mb-1">
                    <span class="fw-bold">Телефон:</span> {{ orderData.user_phone }}
                </div>
                <div class="mb-1">
                    <span class="fw-bold">E-mail:</span> {{ orderData.user_email }}
                </div>
                <div class="">
                    <span class="fw-bold">Адрес:</span> {{ orderData.address }}
                </div>
                <div class="">
                    <form-item label="Точка продажи">
                        <el-select v-model="orderData.point" filterable>
                            <el-option v-for="point in points" :value="point.label" :label="point.label"/>
                        </el-select>
                    </form-item>
                </div>
                <div v-if="orderData.cdek_status">
                    <span class="fw-bold">Логистическая компания:</span> CDEK
                </div>
                <div v-if="orderData.cdek_status">
                    <span class="fw-bold">Статус доставки:</span> {{ cdekStatusText }}
                </div>
                <div v-if="orderData.cdek_point_delivery" class="">
                    <span class="fw-bold">Адрес доставки:</span> {{ orderData.cdek_point_delivery.name }}
                </div>
            </el-card>
        </el-col>
        <el-col :xs="24" :lg="10" class="mb-3">
            <el-card v-loading="loading" class="flex flex-col">
                <div class="mb-2">
                    <div class="mb-1">
                        <span class="fw-bold">Создан:</span> {{ orderData.date_for_human }}
                    </div>
                    <div class="">
                        <span class="fw-bold">Обновлен:</span> {{ orderData.updated_at }}
                    </div>
                </div>
                <el-button class="w-full" size="large" :type="orderData.status === 0 ? 'danger' : 'success'"
                           @click="changeStatus">
                    {{ orderData.status === 0 ? 'В работе' : 'Завершен' }}
                </el-button>
            </el-card>
        </el-col>
    </el-row>

    <el-table :data="productsData" border>
        <el-table-column label="Товар" prop="title">
            <template #default="prop">
                <div class="flex" v-if="prop.row.title">
                    <div>
                        <img :src="prop.row.first_preview ?? '/images/no_image.png'" alt=""
                             class="product-image me-2" style="width: 50px;">
                    </div>
                    <div class="">
                        {{ prop.row.title }}
                    </div>
                </div>
                <div v-for="attr in prop.row.attributesValue" :key="attr.id">
                    <span class="font-extrabold">{{ attr.attribute.name }}:</span> {{ attr.value }}
                </div>
            </template>
        </el-table-column>
        <el-table-column label="Цена" prop="currentPrice">
            <template #default="prop">
                <template v-if="prop.row.currentPrice">
                    {{ prop.row.currentPrice }}<span v-html="settings('currency', 'рубль', 'shop')"></span>
                </template>
            </template>
        </el-table-column>
        <el-table-column v-if="orderData.cdek_price" label="Доставка CDEK" prop="cdek_price">
            <template #default="prop">
                <template v-if="prop.row.count">
                    {{ orderData.cdek_price }}<span v-html="settings('currency', 'рубль', 'shop')"></span>
                </template>
            </template>
        </el-table-column>
        <el-table-column prop="count" label="Количество"/>
        <el-table-column label="Итого">
            <template #default="prop">
                <template v-if="prop.row.count"> {{ prop.row.currentPrice * prop.row.count + orderData.cdek_price }}<span
                    v-html="settings('currency', 'рубль', 'shop')"></span></template>
                <template v-else>{{ prop.row.amount + orderData.cdek_price }} <span v-html="settings('currency', 'рубль', 'shop')"></span>
                </template>
            </template>
        </el-table-column>
    </el-table>

</template>

<script>
import MainLayout from "../../../../Layouts/MainLayout.vue";
import FormItem from "../../../../components/UI/Form/FormItem.vue";

export default {
    name: "Show",
    components: {FormItem},
    layout: MainLayout,
    props: {
        products: null,
        order: null,
        points: Array
    },
    data() {
        return {
            loading: false,
            orderData: this.order.data
        }
    },
    computed: {
        productsData() {
            const products = [...this.products.data]
            products.push({
                price: null,
                count: null,
                amount: this.orderData.price
            })
            return products
        },
        cdekStatusText() {
            const map = {
                CREATED: 'Создан',
                ACCEPTED: 'Принят СДЭК',
                INVALID: 'Ошибка заказа',

                RECEIVED_AT_SHIPMENT_WAREHOUSE: 'Принят на складе отправителя',
                READY_FOR_SHIPMENT: 'Готов к отправке',
                TAKEN_BY_TRANSPORTER: 'Передан перевозчику',

                IN_TRANSIT: 'В пути',
                LEFT_SENDER_CITY: 'Покинул город отправителя',
                ARRIVED_AT_TRANSIT_WAREHOUSE: 'Прибыл на транзитный склад',
                LEFT_TRANSIT_WAREHOUSE: 'Покинул транзитный склад',

                ARRIVED_AT_DESTINATION_WAREHOUSE: 'Прибыл в город получателя',
                READY_FOR_PICKUP: 'Готов к выдаче',
                COURIER_DELIVERY: 'Передан курьеру',

                DELIVERED: 'Доставлен',
                NOT_DELIVERED: 'Не доставлен',

                RETURNED_TO_SENDER: 'Возвращён отправителю',
                RETURNING: 'Возврат в процессе',
                LOST: 'Утерян',
                DAMAGED: 'Повреждён'
            };

            return map[this.orderData.cdek_status] || this.orderData.cdek_status;
        }
    },
    watch: {
        'orderData.point'(newVal, oldVal) {
            this.updatePoint(newVal);
        }
    },
    methods: {
        updatePoint(value) {
            axios.post(route('admin.shop.order.update', this.orderData.id), {
                point: this.orderData.point
            }).then((response) => {
                this.orderData = response.data.data
            }).catch((errors) => {
                    if (errors.response['point'] === 422) {
                        this.alertsStore.add('error', 'Ошибка валидации')
                    } else {
                        this.alertsStore.add('error', 'Неизвестная ошибка')
                    }
            })
        },
        changeStatus() {
            this.loading = true
            axios.patch(route('admin.shop.order.changeStatus', this.orderData.id), {
                status: this.orderData.status === 0 ? 5 : 0
            }).then((response) => {
                this.orderData = response.data.data
            }).catch((errors) => {
                if (errors.response['status'] === 422) {
                    this.alertsStore.add('error', 'Ошибка валидации')
                } else {
                    this.alertsStore.add('error', 'Неизвестная ошибка')
                }
            }).finally(() => {
                this.loading = false
            })
        }
    }
}
</script>

<style scoped>

</style>
