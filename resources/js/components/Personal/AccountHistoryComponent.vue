<template>
    <h3 class="acc-history__title">
        Мои заказы
        <span>{{ totalProductsCount }}</span>
    </h3>

    <div class="acc-history__filters">
        <div class="acc-history__filter-group">
            <div class="acc-history__dropdown">
                <button @click="toggleDropdown('orderStatus')" class="acc-history__dropdown-btn">
                    <span class="acc-history__dropdown-label" :class="{ 'hidden': orderStatusFilter !== null }">
                        Статус заказа
                    </span>
                    <span class="acc-history__dropdown-value" :class="{ 'visible': orderStatusFilter !== null }">
                        {{ selectedStatusLabel }}
                    </span>
                    <span class="acc-history__dropdown-arrow">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.5393 4.5H14.2209C14.1313 4.5 14.0469 4.54395 13.9942 4.61602L9.00022 11.4996L4.00628 4.61602C3.95354 4.54395 3.86917 4.5 3.77952 4.5H2.46116C2.3469 4.5 2.28011 4.63008 2.3469 4.72324L8.54495 13.268C8.76995 13.5773 9.2305 13.5773 9.45374 13.268L15.6518 4.72324C15.7203 4.63008 15.6535 4.5 15.5393 4.5Z" fill="black" fill-opacity="0.25"/>
                        </svg>
                    </span>
                </button>
                <div v-if="activeDropdown === 'orderStatus'" class="acc-history__dropdown-menu">
                    <div
                        v-for="status in orderStatuses"
                        :key="status.value"
                        @click="selectFilter('orderStatus', status.value)"
                        class="acc-history__dropdown-item"
                        :class="{ 'active': orderStatusFilter === status.value }"
                    >
                        {{ status.label }}
                    </div>
                </div>
            </div>
        </div>

        <div class="acc-history__filter-group">
            <div class="acc-history__dropdown">
                <button @click="toggleDropdown('paymentStatus')" class="acc-history__dropdown-btn">
                    <span class="acc-history__dropdown-label" :class="{ 'hidden': paymentStatusFilter !== null }">
                        Статус оплаты
                    </span>
                    <span class="acc-history__dropdown-value" :class="{ 'visible': paymentStatusFilter !== null }">
                        {{ selectedPaymentLabel }}
                    </span>
                    <span class="acc-history__dropdown-arrow">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.5393 4.5H14.2209C14.1313 4.5 14.0469 4.54395 13.9942 4.61602L9.00022 11.4996L4.00628 4.61602C3.95354 4.54395 3.86917 4.5 3.77952 4.5H2.46116C2.3469 4.5 2.28011 4.63008 2.3469 4.72324L8.54495 13.268C8.76995 13.5773 9.2305 13.5773 9.45374 13.268L15.6518 4.72324C15.7203 4.63008 15.6535 4.5 15.5393 4.5Z" fill="black" fill-opacity="0.25"/>
                        </svg>
                    </span>
                </button>
                <div v-if="activeDropdown === 'paymentStatus'" class="acc-history__dropdown-menu">
                    <div
                        v-for="status in paymentStatuses"
                        :key="status.value"
                        @click="selectFilter('paymentStatus', status.value)"
                        class="acc-history__dropdown-item"
                        :class="{ 'active': paymentStatusFilter === status.value }"
                    >
                        {{ status.label }}
                    </div>
                </div>
            </div>
        </div>

        <div class="acc-history__filter-group">
            <div class="acc-history__date-filter">
                <button
                    @click="toggleCalendar"
                    class="acc-history__date-btn"
                    :class="{ 'active': dateFilter !== null }"
                >
                    <span class="acc-history__dropdown-label acc-history__dropdown-date-label" :class="{ 'hidden': dateFilter !== null }">
                        С “сегодня”
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.3475 10.4801L12.4646 6.82383C12.4121 6.75708 12.3451 6.70311 12.2687 6.66597C12.1922 6.62883 12.1084 6.60948 12.0234 6.60938H10.8844C10.7666 6.60938 10.7016 6.74473 10.7736 6.83789L13.3102 10.0547H2.67188C2.59453 10.0547 2.53125 10.118 2.53125 10.1953V11.25C2.53125 11.3273 2.59453 11.3906 2.67188 11.3906H14.9045C15.3756 11.3906 15.6375 10.8492 15.3475 10.4801Z" fill="black" fill-opacity="0.25"/>
                        </svg>
                        По “1-ый заказ”
                    </span>
                    <span class="acc-history__dropdown-value" :class="{ 'visible': dateFilter !== null }">
                        {{ selectedDateLabel }}
                    </span>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.4688 3.23438H12.5156V2.10938C12.5156 2.03203 12.4523 1.96875 12.375 1.96875H11.3906C11.3133 1.96875 11.25 2.03203 11.25 2.10938V3.23438H6.75V2.10938C6.75 2.03203 6.68672 1.96875 6.60938 1.96875H5.625C5.54766 1.96875 5.48438 2.03203 5.48438 2.10938V3.23438H2.53125C2.22012 3.23438 1.96875 3.48574 1.96875 3.79688V15.4688C1.96875 15.7799 2.22012 16.0312 2.53125 16.0312H15.4688C15.7799 16.0312 16.0312 15.7799 16.0312 15.4688V3.79688C16.0312 3.48574 15.7799 3.23438 15.4688 3.23438ZM14.7656 14.7656H3.23438V8.08594H14.7656V14.7656ZM3.23438 6.89062V4.5H5.48438V5.34375C5.48438 5.42109 5.54766 5.48438 5.625 5.48438H6.60938C6.68672 5.48438 6.75 5.42109 6.75 5.34375V4.5H11.25V5.34375C11.25 5.42109 11.3133 5.48438 11.3906 5.48438H12.375C12.4523 5.48438 12.5156 5.42109 12.5156 5.34375V4.5H14.7656V6.89062H3.23438Z" fill="black" fill-opacity="0.45"/>
                    </svg>
                </button>

                <div v-if="showCalendar" class="acc-history__calendar">
                    <div class="acc-history__calendar-header">
                        <div class="acc-history__calendar-nav-group">
                            <button @click="prevYear" class="acc-history__calendar-nav acc-history__calendar-nav-double">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.26445 8L8.41132 2.70156C8.47538 2.62031 8.41757 2.5 8.31288 2.5H7.10507C7.02851 2.5 6.95507 2.53594 6.9082 2.59531L2.91913 7.69219C2.85005 7.78022 2.8125 7.88888 2.8125 8.00078C2.8125 8.11268 2.85005 8.22135 2.91913 8.30937L6.9082 13.4047C6.95507 13.4656 7.02851 13.5 7.10507 13.5H8.31288C8.41757 13.5 8.47538 13.3797 8.41132 13.2984L4.26445 8ZM9.01445 8L13.1613 2.70156C13.2254 2.62031 13.1676 2.5 13.0629 2.5H11.8551C11.7785 2.5 11.7051 2.53594 11.6582 2.59531L7.66913 7.69219C7.60005 7.78022 7.5625 7.88888 7.5625 8.00078C7.5625 8.11268 7.60005 8.22135 7.66913 8.30937L11.6582 13.4047C11.7051 13.4656 11.7785 13.5 11.8551 13.5H13.0629C13.1676 13.5 13.2254 13.3797 13.1613 13.2984L9.01445 8Z" fill="black" fill-opacity="0.45"/>
                                </svg>
                            </button>
                            <button @click="prevMonth" class="acc-history__calendar-nav">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.3125 3.41119V2.20338C11.3125 2.09869 11.1922 2.04088 11.111 2.10494L4.06721 7.6065C4.00736 7.65304 3.95894 7.71264 3.92563 7.78074C3.89232 7.84885 3.875 7.92366 3.875 7.99947C3.875 8.07528 3.89232 8.1501 3.92563 8.2182C3.95894 8.2863 4.00736 8.3459 4.06721 8.39244L11.111 13.894C11.1938 13.9581 11.3125 13.9003 11.3125 13.7956V12.5878C11.3125 12.5112 11.2766 12.4378 11.2172 12.3909L5.59221 8.00025L11.2172 3.60807C11.2766 3.56119 11.3125 3.48775 11.3125 3.41119Z" fill="black" fill-opacity="0.45"/>
                                </svg>
                            </button>
                        </div>
                        <span class="acc-history__calendar-month">{{ currentMonthName }} {{ currentYear }}</span>
                        <div class="acc-history__calendar-nav-group">
                            <button @click="nextMonth" class="acc-history__calendar-nav">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.964 7.60637L4.92031 2.10481C4.90191 2.09032 4.87979 2.08131 4.8565 2.07883C4.8332 2.07634 4.80968 2.08048 4.78863 2.09076C4.76758 2.10104 4.74986 2.11704 4.7375 2.13694C4.72514 2.15684 4.71864 2.17982 4.71875 2.20325V3.41106C4.71875 3.48762 4.75469 3.56106 4.81406 3.60793L10.439 8.00012L4.81406 12.3923C4.75313 12.4392 4.71875 12.5126 4.71875 12.5892V13.797C4.71875 13.9017 4.83906 13.9595 4.92031 13.8954L11.964 8.39387C12.0239 8.34717 12.0723 8.28744 12.1057 8.21921C12.139 8.15098 12.1563 8.07605 12.1563 8.00012C12.1563 7.92419 12.139 7.84927 12.1057 7.78104C12.0723 7.71281 12.0239 7.65307 11.964 7.60637Z" fill="black" fill-opacity="0.45"/>
                                </svg>
                            </button>
                            <button @click="nextYear" class="acc-history__calendar-nav acc-history__calendar-nav-double">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.3315 7.69219L4.34244 2.59531C4.29557 2.53438 4.22213 2.5 4.14557 2.5H2.93775C2.83307 2.5 2.77525 2.62031 2.83932 2.70156L6.98619 8L2.83932 13.2984C2.82489 13.3169 2.81594 13.339 2.81348 13.3622C2.81103 13.3855 2.81518 13.409 2.82544 13.43C2.83571 13.4511 2.85169 13.4688 2.87155 13.4811C2.89141 13.4935 2.91435 13.5001 2.93775 13.5H4.14557C4.22213 13.5 4.29557 13.4641 4.34244 13.4047L8.3315 8.30937C8.47369 8.12656 8.47369 7.87344 8.3315 7.69219ZM13.0815 7.69219L9.09244 2.59531C9.04557 2.53438 8.97213 2.5 8.89557 2.5H7.68775C7.58307 2.5 7.52525 2.62031 7.58932 2.70156L11.7362 8L7.58932 13.2984C7.57489 13.3169 7.56594 13.339 7.56348 13.3622C7.56103 13.3855 7.56518 13.409 7.57544 13.43C7.58571 13.4511 7.60169 13.4688 7.62155 13.4811C7.64141 13.4935 7.66435 13.5001 7.68775 13.5H8.89557C8.97213 13.5 9.04557 13.4641 9.09244 13.4047L13.0815 8.30937C13.2237 8.12656 13.2237 7.87344 13.0815 7.69219Z" fill="black" fill-opacity="0.45"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="acc-history__calendar-main">
                        <div class="acc-history__calendar-weekdays">
                            <div v-for="day in weekDays" :key="day" class="acc-history__calendar-weekday">
                                {{ day }}
                            </div>
                        </div>
                        <div class="acc-history__calendar-days">
                            <div
                                v-for="day in calendarDays"
                                :key="day.date"
                                @click="selectDate(day)"
                                class="acc-history__calendar-day"
                                :class="{
                                'empty': !day.date,
                                'selected': isDateSelected(day.date),
                                'today': isToday(day.date),
                                'in-range': isInRange(day.date),
                                'range-start': isRangeStart(day.date),
                                'range-end': isRangeEnd(day.date)
                            }"
                            >
                              <span>  {{ day.day }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="acc-history__calendar-footer">
                        <button @click="resetDateFilter" class="acc-history__calendar-reset">Сбросить</button>
                        <button @click="applyDateRange" class="acc-history__calendar-apply">Применить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="acc-history">
        <div class="acc-history__items">
            <div v-if="filteredOrders.length">
                <div v-for="(history, index) in filteredOrders" :key="history.id" class="acc-history__item">
                    <div class="acc-history__item-header">
                        <div class="acc-history__item-left">
                            <div class="acc-history__numb">Заказ: {{ history.id }}</div>
                            <div class="acc-history__date">{{ history.date_for_human }}</div>
                        </div>
                        <div class="acc-history__item-right">
                            <div class="acc-history__status" :class="getStatusClass(history.status)">
                                {{ getOrderStatusText(history.status) }}

                            </div>
                        </div>
                    </div>
                    <div class="acc-history__item-body">
                        <div class="acc-history__item-body-top">
                            <div>
                                <div class="acc-history__total">
                                    <span>{{ history.price }} ₽</span>
                                </div>
                                <div class="acc-history__date" :class="getPaymentStatusClass(history.payment_status)">
                                    {{ getPaymentStatusText(history.payment_status) }}
                                </div>
                                <button class="error-text">Сообщить о проблеме</button>
                            </div>
                            <div class="acc-history__item-body-products">
                                <div v-for="product in history.products" :key="product.id" class="acc-history__product">
                                    <div class="acc-history__product-img">
                                        <img :src="product.images[0]?.preview_url" :alt="product.name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="acc-history__item-btns">
                            <button v-if="history.payment_status !== 'paid'" @click="payOrder(history.id)">Оплатить</button>
                            <button @click="viewOrder(history.id)">О заказе</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="acc-history__empty">
                <p>Нет заказов</p>
            </div>
        </div>
    </div>
</template>

<script>
import { productFilterOnMount } from "@/Shop/ProductFilterOnMount.js";

export default {
    name: "AccountHistoryComponent",
    props: {
        history: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            activeDropdown: null,
            showCalendar: false,
            orderStatusFilter: null,
            paymentStatusFilter: null,
            dateFilter: null,
            dateFrom: null,
            dateTo: null,
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            selectedDate: null,
            orderStatuses: [
                { value: 'all', label: 'Все статусы' },
                { value: 0, label: 'На подтверждении' },
                { value: 1, label: 'В обработке' },
                { value: 2, label: 'Сборка' },
                { value: 3, label: 'Доставлен' },
                { value: 4, label: 'В пути' },
                { value: 5, label: 'Отправлен' },
                { value: 6, label: 'Отменен' }
            ],
            paymentStatuses: [
                { value: 'all', label: 'Все статусы' },
                { value: 0, label: 'Не оплачен' },
                { value: 1, label: 'Оплачен' },
                { value: 2, label: 'Ошибка' }
            ],
            weekDays: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']
        };
    },
    computed: {
        totalProductsCount() {
            return this.history.reduce((total, order) => {
                const productsCount = order.products?.length || 0;
                return total + productsCount;
            }, 0);
        },
        currentMonthName() {
            const months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            return months[this.currentMonth];
        },
        calendarDays() {
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startDayOfWeek = firstDay.getDay() || 7;

            const days = [];
            for (let i = 1; i < startDayOfWeek; i++) {
                days.push({ day: '', date: null });
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(this.currentYear, this.currentMonth, i);
                days.push({
                    day: i,
                    date: date
                });
            }

            return days;
        },
        filteredOrders() {
            let orders = [...this.history];

            // Фильтр по статусу заказа
            if (this.orderStatusFilter !== null && this.orderStatusFilter !== 'all') {
                orders = orders.filter(order => order.status === this.orderStatusFilter);
            }

            // Фильтр по статусу оплаты (t_bank_status)
            if (this.paymentStatusFilter !== null && this.paymentStatusFilter !== 'all') {
                orders = orders.filter(order => order.t_bank_status === this.paymentStatusFilter);
            }

            // Фильтр по дате
            if (this.dateFilter === 'range' && this.dateFrom && this.dateTo) {
                const from = new Date(this.dateFrom);
                const to = new Date(this.dateTo);
                orders = orders.filter(order => {
                    // Парсим дату из date_for_human (формат "07.07.2026")
                    const dateParts = order.date_for_human.split('.');
                    const orderDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    return orderDate >= from && orderDate <= to;
                });
            } else if (this.dateFilter === 'today') {
                const today = new Date();
                const startOfDay = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                orders = orders.filter(order => {
                    const dateParts = order.date_for_human.split('.');
                    const orderDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    return orderDate >= startOfDay;
                });
            } else if (this.dateFilter === 'week') {
                const today = new Date();
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                orders = orders.filter(order => {
                    const dateParts = order.date_for_human.split('.');
                    const orderDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    return orderDate >= weekAgo;
                });
            } else if (this.dateFilter === 'month') {
                const today = new Date();
                const monthAgo = new Date(today);
                monthAgo.setMonth(monthAgo.getMonth() - 1);
                orders = orders.filter(order => {
                    const dateParts = order.date_for_human.split('.');
                    const orderDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    return orderDate >= monthAgo;
                });
            } else if (this.dateFilter === '3months') {
                const today = new Date();
                const threeMonthsAgo = new Date(today);
                threeMonthsAgo.setMonth(threeMonthsAgo.getMonth() - 3);
                orders = orders.filter(order => {
                    const dateParts = order.date_for_human.split('.');
                    const orderDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    return orderDate >= threeMonthsAgo;
                });
            } else if (this.dateFilter === 'year') {
                const today = new Date();
                const yearAgo = new Date(today);
                yearAgo.setFullYear(yearAgo.getFullYear() - 1);
                orders = orders.filter(order => {
                    const dateParts = order.date_for_human.split('.');
                    const orderDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                    return orderDate >= yearAgo;
                });
            }

            // Сортировка по дате (новые сначала)
            return orders.sort((a, b) => {
                const datePartsA = a.date_for_human.split('.');
                const datePartsB = b.date_for_human.split('.');
                const dateA = new Date(datePartsA[2], datePartsA[1] - 1, datePartsA[0]);
                const dateB = new Date(datePartsB[2], datePartsB[1] - 1, datePartsB[0]);
                return dateB - dateA;
            });
        },
        isAnyFilterActive() {
            return this.orderStatusFilter !== null ||
                this.paymentStatusFilter !== null ||
                this.dateFilter !== null;
        },
        selectedStatusLabel() {
            if (this.orderStatusFilter === null) return '';
            const status = this.orderStatuses.find(s => s.value === this.orderStatusFilter);
            return status ? status.label : '';
        },
        selectedPaymentLabel() {
            if (this.paymentStatusFilter === null) return '';
            const status = this.paymentStatuses.find(s => s.value === this.paymentStatusFilter);
            return status ? status.label : '';
        },
        selectedDateLabel() {
            if (this.dateFilter === null) return '';

            if (this.dateFilter === 'range' && this.dateFrom && this.dateTo) {
                const from = this.formatDate(this.dateFrom);
                const to = this.formatDate(this.dateTo);
                return `${from} - ${to}`;
            }

            const labels = {
                'all': 'Все даты',
                'today': 'Сегодня',
                'week': 'Неделя',
                'month': 'Месяц',
                '3months': '3 месяца',
                'year': 'Год'
            };
            return labels[this.dateFilter] || '';
        }
    },
    mounted() {
        productFilterOnMount();
    },
    methods: {
        toggleDropdown(dropdown) {
            this.activeDropdown = this.activeDropdown === dropdown ? null : dropdown;
            if (this.activeDropdown && this.showCalendar) {
                this.showCalendar = false;
            }
        },
        toggleCalendar() {
            this.showCalendar = !this.showCalendar;
            if (this.showCalendar && this.activeDropdown) {
                this.activeDropdown = null;
            }
        },
        selectFilter(filterType, value) {
            if (filterType === 'orderStatus') {
                this.orderStatusFilter = value;
            } else if (filterType === 'paymentStatus') {
                this.paymentStatusFilter = value;
            }
            this.activeDropdown = null;
        },
        selectDate(day) {
            if (!day.date) return;

            if (!this.dateFrom || (this.dateFrom && this.dateTo)) {
                this.dateFrom = new Date(day.date);
                this.dateTo = null;
                this.selectedDate = day.date;
                this.dateFilter = 'range';
            } else {
                if (day.date < this.dateFrom) {
                    this.dateTo = this.dateFrom;
                    this.dateFrom = new Date(day.date);
                } else {
                    this.dateTo = new Date(day.date);
                }
                this.selectedDate = null;
                this.showCalendar = false;
            }
        },
        isDateSelected(date) {
            if (!date || !this.selectedDate) return false;
            const compareDate = new Date(date);
            const selected = new Date(this.selectedDate);
            return compareDate.toDateString() === selected.toDateString();
        },
        isToday(date) {
            if (!date) return false;
            const today = new Date();
            const compareDate = new Date(date);
            return compareDate.toDateString() === today.toDateString();
        },
        isInRange(date) {
            if (!date || !this.dateFrom || !this.dateTo) return false;
            const compareDate = new Date(date);
            return compareDate > this.dateFrom && compareDate < this.dateTo;
        },
        isRangeStart(date) {
            if (!date || !this.dateFrom) return false;
            const compareDate = new Date(date);
            return compareDate.toDateString() === this.dateFrom.toDateString();
        },
        isRangeEnd(date) {
            if (!date || !this.dateTo) return false;
            const compareDate = new Date(date);
            return compareDate.toDateString() === this.dateTo.toDateString();
        },
        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },
        prevYear() {
            this.currentYear--;
        },
        nextYear() {
            this.currentYear++;
        },
        applyDateRange() {
            if (this.dateFrom && this.dateTo) {
                this.showCalendar = false;
            } else if (this.dateFrom && !this.dateTo) {
                this.dateTo = new Date(this.dateFrom);
                this.showCalendar = false;
            }
        },
        resetDateFilter() {
            this.dateFrom = null;
            this.dateTo = null;
            this.dateFilter = null;
            this.selectedDate = null;
            this.showCalendar = false;
        },
        resetFilters() {
            this.orderStatusFilter = null;
            this.paymentStatusFilter = null;
            this.dateFilter = null;
            this.dateFrom = null;
            this.dateTo = null;
            this.selectedDate = null;
            this.activeDropdown = null;
            this.showCalendar = false;
        },
        formatDate(date) {
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            return `${day}.${month}`;
        },
        getOrderStatusText(status) {
            const statusMap = {
                0: 'На подтверждении',
                1: 'В обработке',
                2: 'Сборка',
                3: 'Доставлен',
                4: 'В пути',
                5: 'Отправлен',
                6: 'Отменен'
            };
            return statusMap[status] || 'Неизвестный статус';
        },
        getStatusClass(status) {
            const classMap = {
                0: 'status-pending',
                1: 'status-processing',
                2: 'status-assembly',
                3: 'status-sent',
                4: 'status-transit',
                5: 'status-delivered',
                6: 'status-cancelled'
            };
            return classMap[status] || '';
        },
        getPaymentStatusText(tBankStatus) {
            const statusMap = {
                0: 'Не оплачен',
                1: 'Оплачен',
                2: 'Ошибка оплаты'
            };
            return statusMap[tBankStatus] || 'Неизвестно';
        },
        getPaymentStatusClass(tBankStatus) {
            const classMap = {
                0: 'payment-unpaid',
                1: 'payment-paid',
                2: 'payment-error'
            };
            return classMap[tBankStatus] || '';
        },
        payOrder(orderId) {
            // Здесь логика оплаты
        },
        viewOrder(orderId) {
            // Здесь логика просмотра
        },
        declinateProduct(count) {
            const lastDigit = count % 10;
            const lastTwoDigits = count % 100;

            if (lastTwoDigits >= 11 && lastTwoDigits <= 19) return 'товаров';
            if (lastDigit === 1) return 'товар';
            if (lastDigit >= 2 && lastDigit <= 4) return 'товара';
            return 'товаров';
        }
    }
}
</script>

<style scoped>
</style>
