<template>
    <div id="order">
        <div class="feedback-popup__heading">
            <template v-if="completed">
                Заказ оформлен
            </template>
            <template v-else>
                <h2>Оформление заказа</h2>
            </template>
        </div>

        <div class="feedback-popup__content">
            <div v-if="completed">/</div>

            <form v-else @submit.prevent="store">
                <RadioGroupComponent v-model="order.user_type" />
<!--                <p>Выбрано: {{ order.user_type }}</p>-->

                <div class="feedback-popup__item">
                    <InputField
                        name="user_name"
                        label="Имя"
                        placeholder="Инициалы (фамилия, имя, отчество)*"
                        v-model="order.user_name"
                        :errors="errors['user_name']"
                    />
                </div>

                <div class="feedback-popup__items">
                    <div class="feedback-popup__item">
                        <InputField
                            name="user_email"
                            label="Email"
                            placeholder="Электронная почта"
                            v-model="order.user_email"
                            :errors="errors['user_email']"
                        />
                    </div>
                    <div class="feedback-popup__item">
                        <PhoneField
                            name="user_phone"
                            label="Ваш телефон"
                            placeholder="Телефон*"
                            v-model="order.user_phone"
                            :errors="errors['user_phone']"
                        />
                    </div>
                </div>

                <div class="feedback-popup__subtitle">Доставка</div>
                <PointsRadioGroupComponent v-model="order.point" :points="pointsSelected()"/>
<!--                <p>Выбрано: {{ order.point }}</p>-->
                <div class="feedback-popup__item">
                    <div class="feedback-popup__item-text">
                        Укажите населенный пункт и выберите удобный способ доставки*
                    </div>
                    <AutocompleteInput
                        v-model="cityQuery"
                        :options="cities"
                        placeholder="Начните вводить адрес и выберите вариант из списка"
                        @select="handleCitySelect"
                    />
                </div>
                <div class="feedback-popup__city-message">
                    Заказы с некорректно заполненными адресами отправляются дольше всего.
                </div>
                <DeliveryComponent
                    v-model="order.is_pickup"
                />
<!--                <p>Выбрано: {{ order.is_pickup }}</p>-->

                <div v-if="cdekPointsDelivery.length" class="feedback-popup__item">
                    <div class="feedback-popup__item-text">
                        Укажите пункт доставки CDEK
                    </div>
                    <SelectInputField
                        v-model="pointDelivery"
                        :data="cdekPointsDelivery"
                        placeholder="Начните вводить адрес и выберите вариант из списка"
                    />
                </div>

                <div v-if="tariffDelivery.length" class="feedback-popup__item">
                    <div class="feedback-popup__item-text">
                        Стоимость доставки в пункт выдачи: {{ tariffDeliveryPrint().price }} руб.
                    </div>
                    <div class="feedback-popup__item-text">
                        Дата доставки с {{ tariffDeliveryPrint().date.min }} до {{ tariffDeliveryPrint().date.max }}
                    </div>
                </div>

                <div class="feedback-popup__subtitle">Оплата</div>
                <PaymentComponent v-model="order.payment_type" />
<!--                <p>Выбрано: {{ order.payment_type }}</p>-->

                <div class="feedback-popup__subtitle">Комментарий к заказу</div>
                <div class="feedback-popup__item">
                    <TextField
                        name="comment"
                        label="Комментарий"
                        placeholder="Напишите всё, о чем нам следует знать"
                        v-model="order.comment"
                        :errors="errors['comment']"
                    />
                </div>

                <div class="feedback-popup__bottom">
                    <button type="submit" class="feedback-btn">Оформить заказ</button>
                    <div class="feedback-popup__privacy" v-if="privacyPolicyLink">
                        <CheckboxField
                            class="checkbox-inp"
                            :errors="errors['policy']"
                        />
                        <span>
              Я ознакомлен и согласен на
              <a :href="privacyPolicyLink">обработку персональных данных</a>
            </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="loader-frame" v-if="loading">
            <div class="loader"></div>
        </div>

        <div id="errors-saby-popup" style="display: none;" class="vue_app">
            <div v-for="error in sabyErrors">
                <span>{{ error }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from "../Ui/Form/InputField.vue";
import PhoneField from "../Ui/Form/PhoneField.vue";
import TextField from "../Ui/Form/TextField.vue";
import RadioGroupComponent from "@/components/Cart/RadioGroupComponent.vue";
import PointsRadioGroupComponent from "@/components/Cart/PointsRadioGroupComponent.vue";
import DeliveryComponent from "@/components/Cart/DeliveryComponent.vue";
import PaymentComponent from "@/components/Cart/PaymentComponent.vue";
import CheckboxField from "@/components/Ui/Form/CheckboxField.vue";
import AutocompleteInput from "@/components/Ui/Form/AutocompleteInput.vue";
import debounce from "lodash/debounce";
import $ from 'jquery';
window.$ = window.jQuery = $;

import '@fancyapps/fancybox/dist/jquery.fancybox.min.css';
import '@fancyapps/fancybox/dist/jquery.fancybox.min.js';
import SelectInputField from "../Ui/Form/SelectInputField.vue";


export default {
    name: "OrderComponent",
    components: {
        SelectInputField,
        AutocompleteInput,
        CheckboxField,
        PaymentComponent,
        DeliveryComponent,
        RadioGroupComponent,
        PointsRadioGroupComponent,
        InputField,
        PhoneField,
        TextField,
    },
    props: {
        privacyPolicyLink: null,
        user: null,
        points: Array
    },
    data() {
        return {
            completed: false,
            loading: false,
            errors: [],
            order: {
                account_id: this.user?.id,
                user_name: this.user?.full_name + " " + this.user?.last_name || '',
                user_email: this.user?.email,
                user_phone: this.user?.phone,
                address: "",
                user_type: "",       //   пользователя
                comment: "",
                is_pickup: "",       //   доставки
                payment_type: "",    //   оплаты
                point: "",
                cdek_point_delivery: [],
                cdek_tariffs: []
            },
            cityQuery: '',
            selectedCity: null,
            cities: [],
            sabyErrors: [],
            cdekPointsDelivery: [],
            pointDelivery: null,
            tariffDelivery: []
        };
    },
    watch: {
        cityQuery: debounce(function (newVal) {
            if (!newVal || newVal.length < 3) return;
            this.fetchCities(newVal);
        }, 300),

        'order.point'(newVal, oldVal) {
            this.getBalance();
        },

        'order.is_pickup'(newVal) {
            if (newVal === 'CDEK - до пункта выдачи' && this.cityQuery) {
                this.getPointsOfDelivery();
            }
        },

        'pointDelivery'(newVal) {
            this.order.cdek_point_delivery = this.cdekPointsDelivery.find(
                el => el.key === newVal
            );
        },

        'order.cdek_point_delivery'(newVal) {
            this.getPointOfDelivery()
        }
    },
    methods: {
        tariffDeliveryPrint() {
            if (!this.tariffDelivery.length) return null;

            const item = this.tariffDelivery.find(
                el => el.tariff_name === 'Посылка дверь-склад'
            );

            return item ? { price: item.delivery_sum, date: item.delivery_date_range } : null;
        },
        pointsSelected() {
            return this.points.salesPoints.map(item => ({
                value: item.id,
                label: item.address
            }));
        },
        getPointOfDelivery() {
            const item = this.points.salesPoints.find(el => el.address === this.order.point);

            axios.post('/cdek/getShippingPrice', {
                lat: item.latitude,
                lon: item.longitude,
                address: item.address,
                toLocation: this.order.cdek_point_delivery
            }).then((response) => {
                this.tariffDelivery = response.data;
                this.order.cdek_tariffs = this.tariffDelivery;
            }).catch((errors) => {
                this.alertsStore.add('error', 'Неизвестная ошибка')
            })
        },
        getPointsOfDelivery() {
            axios.post('/cdek/getPointsOfDelivery', {
                city: this.cityQuery
            }).then((response) => {
                this.cdekPointsDelivery = response.data.points.map(item => ({
                    key: item.code,
                    name: item.name,
                    original: item
                }));
            }).catch((errors) => {
                this.alertsStore.add('error', 'Неизвестная ошибка')
            })
        },
        getBalance() {
            const selectedPoint = this.pointsSelected().find(p => p.label === this.order.point);
            const value = selectedPoint ? selectedPoint.value : null;

            axios.post('/shop/order/getBalance', {
                pointId: value
            }).then((response) => {
                this.sabyErrors = response.data

                if (this.sabyErrors.length) {
                    this.$nextTick(() => {
                        $.fancybox.open({
                            src: '#errors-saby-popup',
                            type: 'inline'
                        });
                    });
                }
            }).catch((errors) => {
                this.alertsStore.add('error', 'Неизвестная ошибка')
            })
        },
        fetchCities(query) {
            axios.get('/DaData/getAddress', {
                params: { q: query }
            }).then(res => {
                this.cities = res.data;
            });
        },
        store() {
            const item = this.points.salesPoints.find(el => el.address === this.order.point);
            this.order.coordinates = {lat: item.latitude, lon: item.longitude};
            this.order.cdek_price = this.tariffDeliveryPrint()?.price;

            this.loading = true;
            axios.post('/shop/order/store', this.order)
                .then((response) => {
                    this.completed = true;
                    setTimeout(() => {
                        window.location.href = response.data.paymentLink;
                    }, 2000);
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        handleCitySelect(city) {
            this.selectedCity = city.value;
            this.order.address = city.label;
        }
    }
};
</script>
