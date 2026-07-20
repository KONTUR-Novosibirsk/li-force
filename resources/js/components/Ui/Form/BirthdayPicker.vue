<template>
    <div class="birthday-picker">
        <!-- Поле ввода -->
        <div class="input-wrapper" @click.stop="toggleCalendar">
            <input
                type="text"
                :value="formattedDate"
                readonly
                :placeholder="placeholder"
                class="birthday-input"
            />
            <span class="calendar-icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.4688 3.23438H12.5156V2.10938C12.5156 2.03203 12.4523 1.96875 12.375 1.96875H11.3906C11.3133 1.96875 11.25 2.03203 11.25 2.10938V3.23438H6.75V2.10938C6.75 2.03203 6.68672 1.96875 6.60938 1.96875H5.625C5.54766 1.96875 5.48438 2.03203 5.48438 2.10938V3.23438H2.53125C2.22012 3.23438 1.96875 3.48574 1.96875 3.79688V15.4688C1.96875 15.7799 2.22012 16.0312 2.53125 16.0312H15.4688C15.7799 16.0312 16.0312 15.7799 16.0312 15.4688V3.79688C16.0312 3.48574 15.7799 3.23438 15.4688 3.23438ZM14.7656 14.7656H3.23438V8.08594H14.7656V14.7656ZM3.23438 6.89062V4.5H5.48438V5.34375C5.48438 5.42109 5.54766 5.48438 5.625 5.48438H6.60938C6.68672 5.48438 6.75 5.42109 6.75 5.34375V4.5H11.25V5.34375C11.25 5.42109 11.3133 5.48438 11.3906 5.48438H12.375C12.4523 5.48438 12.5156 5.42109 12.5156 5.34375V4.5H14.7656V6.89062H3.23438Z" fill="black" fill-opacity="0.25"/>
                </svg>
            </span>
        </div>

        <!-- Календарь -->
        <div v-if="isOpen" class="calendar-dropdown" ref="calendarRef" @click.stop>
            <!-- Заголовок с навигацией -->
            <div class="calendar-header">
                <div class="nav-group">
                    <button
                        type="button"
                        @click.stop.prevent="prevYear"
                        class="nav-btn nav-double"
                        title="Предыдущий год"
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.26445 8L8.41132 2.70156C8.47538 2.62031 8.41757 2.5 8.31288 2.5H7.10507C7.02851 2.5 6.95507 2.53594 6.9082 2.59531L2.91913 7.69219C2.85005 7.78022 2.8125 7.88888 2.8125 8.00078C2.8125 8.11268 2.85005 8.22135 2.91913 8.30937L6.9082 13.4047C6.95507 13.4656 7.02851 13.5 7.10507 13.5H8.31288C8.41757 13.5 8.47538 13.3797 8.41132 13.2984L4.26445 8ZM9.01445 8L13.1613 2.70156C13.2254 2.62031 13.1676 2.5 13.0629 2.5H11.8551C11.7785 2.5 11.7051 2.53594 11.6582 2.59531L7.66913 7.69219C7.60005 7.78022 7.5625 7.88888 7.5625 8.00078C7.5625 8.11268 7.60005 8.22135 7.66913 8.30937L11.6582 13.4047C11.7051 13.4656 11.7785 13.5 11.8551 13.5H13.0629C13.1676 13.5 13.2254 13.3797 13.1613 13.2984L9.01445 8Z" fill="black" fill-opacity="0.45"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click.stop.prevent="prevMonth"
                        class="nav-btn"
                        title="Предыдущий месяц"
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.3125 3.41119V2.20338C11.3125 2.09869 11.1922 2.04088 11.111 2.10494L4.06721 7.6065C4.00736 7.65304 3.95894 7.71264 3.92563 7.78074C3.89232 7.84885 3.875 7.92366 3.875 7.99947C3.875 8.07528 3.89232 8.1501 3.92563 8.2182C3.95894 8.2863 4.00736 8.3459 4.06721 8.39244L11.111 13.894C11.1938 13.9581 11.3125 13.9003 11.3125 13.7956V12.5878C11.3125 12.5112 11.2766 12.4378 11.2172 12.3909L5.59221 8.00025L11.2172 3.60807C11.2766 3.56119 11.3125 3.48775 11.3125 3.41119Z" fill="black" fill-opacity="0.45"/>
                        </svg>
                    </button>
                </div>

                <span class="month-year" @click.stop="resetToToday">
          {{ currentMonthName }} {{ currentYear }}
        </span>

                <div class="nav-group">
                    <button
                        type="button"
                        @click.stop.prevent="nextMonth"
                        class="nav-btn"
                        title="Следующий месяц"
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.964 7.60637L4.92031 2.10481C4.90191 2.09032 4.87979 2.08131 4.8565 2.07883C4.8332 2.07634 4.80968 2.08048 4.78863 2.09076C4.76758 2.10104 4.74986 2.11704 4.7375 2.13694C4.72514 2.15684 4.71864 2.17982 4.71875 2.20325V3.41106C4.71875 3.48762 4.75469 3.56106 4.81406 3.60793L10.439 8.00012L4.81406 12.3923C4.75313 12.4392 4.71875 12.5126 4.71875 12.5892V13.797C4.71875 13.9017 4.83906 13.9595 4.92031 13.8954L11.964 8.39387C12.0239 8.34717 12.0723 8.28744 12.1057 8.21921C12.139 8.15098 12.1563 8.07605 12.1563 8.00012C12.1563 7.92419 12.139 7.84927 12.1057 7.78104C12.0723 7.71281 12.0239 7.65307 11.964 7.60637Z" fill="black" fill-opacity="0.45"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click.stop.prevent="nextYear"
                        class="nav-btn nav-double"
                        title="Следующий год"
                    >
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.3315 7.69219L4.34244 2.59531C4.29557 2.53438 4.22213 2.5 4.14557 2.5H2.93775C2.83307 2.5 2.77525 2.62031 2.83932 2.70156L6.98619 8L2.83932 13.2984C2.82489 13.3169 2.81594 13.339 2.81348 13.3622C2.81103 13.3855 2.81518 13.409 2.82544 13.43C2.83571 13.4511 2.85169 13.4688 2.87155 13.4811C2.89141 13.4935 2.91435 13.5001 2.93775 13.5H4.14557C4.22213 13.5 4.29557 13.4641 4.34244 13.4047L8.3315 8.30937C8.47369 8.12656 8.47369 7.87344 8.3315 7.69219ZM13.0815 7.69219L9.09244 2.59531C9.04557 2.53438 8.97213 2.5 8.89557 2.5H7.68775C7.58307 2.5 7.52525 2.62031 7.58932 2.70156L11.7362 8L7.58932 13.2984C7.57489 13.3169 7.56594 13.339 7.56348 13.3622C7.56103 13.3855 7.56518 13.409 7.57544 13.43C7.58571 13.4511 7.60169 13.4688 7.62155 13.4811C7.64141 13.4935 7.66435 13.5001 7.68775 13.5H8.89557C8.97213 13.5 9.04557 13.4641 9.09244 13.4047L13.0815 8.30937C13.2237 8.12656 13.2237 7.87344 13.0815 7.69219Z" fill="black" fill-opacity="0.45"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="calendar-main">
                <!-- Дни недели -->
                <div class="weekdays">
                    <div v-for="day in weekdays" :key="day" class="weekday">
                        {{ day }}
                    </div>
                </div>

                <!-- Ячейки дней -->
                <div class="days-grid">
                    <div
                        v-for="day in daysInMonth"
                        :key="day.date.getTime()"
                        class="day-cell"
                        :class="{
                            'empty': !day.isCurrentMonth,
                            'selected': isSelected(day.date),
                            'today': isToday(day.date),
                            'disabled': isDisabled(day.date)
                          }"
                        @click.stop="selectDate(day.date)"
                    >
                       <span> {{ day.day }}</span>
                    </div>
                </div>
            </div>

            <!-- Кнопка "Сегодня" -->
            <div class="calendar-footer">
                <button
                    type="button"
                    @click.stop.prevent="setToday"
                    class="today-btn"
                >
                    Сегодня
                </button>
                <button
                    type="button"
                    @click.stop.prevent="clearDate"
                    class="clear-btn"
                >
                    Очистить
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BirthdayPicker',

    props: {
        modelValue: {
            type: [String, Date, null],
            default: null
        },
        minDate: {
            type: String,
            default: null
        },
        maxDate: {
            type: String,
            default: null
        },
        placeholder: {
            type: String,
            default: 'Выберите дату рождения'
        }
    },

    emits: ['update:modelValue'],

    data() {
        const now = new Date();
        const selected = this.modelValue ? new Date(this.modelValue) : null;

        return {
            isOpen: false,
            selectedDate: selected,
            currentYear: selected ? selected.getFullYear() : now.getFullYear(),
            currentMonth: selected ? selected.getMonth() : now.getMonth(),
            weekdays: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']
        }
    },

    computed: {
        currentMonthName() {
            return new Date(this.currentYear, this.currentMonth).toLocaleString('ru-RU', {
                month: 'long'
            })
        },

        daysInMonth() {
            const year = this.currentYear
            const month = this.currentMonth
            const firstDay = new Date(year, month, 1)
            const lastDay = new Date(year, month + 1, 0)
            const days = []

            // Дни предыдущего месяца
            const prevMonthLastDay = new Date(year, month, 0).getDate()
            const firstDayOfWeek = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1

            for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                const date = new Date(year, month - 1, prevMonthLastDay - i)
                days.push({
                    day: date.getDate(),
                    date: date,
                    isCurrentMonth: false
                })
            }

            // Дни текущего месяца
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const date = new Date(year, month, i)
                days.push({
                    day: i,
                    date: date,
                    isCurrentMonth: true
                })
            }

            // Дни следующего месяца
            const remainingDays = 42 - days.length
            for (let i = 1; i <= remainingDays; i++) {
                const date = new Date(year, month + 1, i)
                days.push({
                    day: i,
                    date: date,
                    isCurrentMonth: false
                })
            }

            return days
        },

        formattedDate() {
            if (!this.selectedDate) return ''
            return this.selectedDate.toLocaleDateString('ru-RU', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            })
        }
    },

    watch: {
        modelValue(newVal) {
            if (newVal) {
                this.selectedDate = newVal instanceof Date ? newVal : new Date(newVal)
            } else {
                this.selectedDate = null
            }
        }
    },

    mounted() {
        document.addEventListener('click', this.handleClickOutside)
    },

    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside)
    },

    methods: {
        toggleCalendar(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }
            this.isOpen = !this.isOpen
        },

        handleClickOutside(event) {
            if (this.isOpen && this.$refs.calendarRef) {
                const calendar = this.$refs.calendarRef
                const input = this.$el.querySelector('.input-wrapper')

                if (!calendar.contains(event.target) && !input.contains(event.target)) {
                    this.isOpen = false
                }
            }
        },

        selectDate(date) {
            if (this.isDisabled(date)) return

            this.selectedDate = new Date(date)
            this.$emit('update:modelValue', this.selectedDate)
            this.isOpen = false
        },

        isSelected(date) {
            if (!this.selectedDate) return false
            return date.getFullYear() === this.selectedDate.getFullYear() &&
                date.getMonth() === this.selectedDate.getMonth() &&
                date.getDate() === this.selectedDate.getDate()
        },

        isToday(date) {
            const today = new Date()
            return date.getFullYear() === today.getFullYear() &&
                date.getMonth() === today.getMonth() &&
                date.getDate() === today.getDate()
        },

        isDisabled(date) {
            if (this.minDate && date < new Date(this.minDate)) return true
            if (this.maxDate && date > new Date(this.maxDate)) return true

            const today = new Date()
            today.setHours(0, 0, 0, 0)
            const checkDate = new Date(date)
            checkDate.setHours(0, 0, 0, 0)

            if (checkDate > today) return true

            return false
        },

        prevMonth(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }

            if (this.currentMonth === 0) {
                this.currentMonth = 11
                this.currentYear--
            } else {
                this.currentMonth--
            }
        },

        nextMonth(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }

            if (this.currentMonth === 11) {
                this.currentMonth = 0
                this.currentYear++
            } else {
                this.currentMonth++
            }
        },

        prevYear(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }
            this.currentYear--
        },

        nextYear(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }
            this.currentYear++
        },

        resetToToday(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }
            const today = new Date()
            this.currentYear = today.getFullYear()
            this.currentMonth = today.getMonth()
        },

        setToday(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }
            const today = new Date()
            this.selectDate(today)
        },

        clearDate(event) {
            if (event) {
                event.stopPropagation()
                event.preventDefault()
            }
            this.selectedDate = null
            this.$emit('update:modelValue', null)
            this.isOpen = false
        }
    }
}
</script>

<style scoped>
.birthday-picker {
    position: relative;
    display: block;
    width: 100%;
    z-index: 1000;
}

.input-wrapper {
    position: relative;
    cursor: pointer;
    width: 100%;
}

.birthday-input {
    width: 100%;
    padding: 7px 40px 7px 12px;
    border: 1px solid rgba(217, 217, 217, 1);
    border-radius: 6px;
    font-size: 16px;
    line-height: 24px;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
    outline: none;
    box-sizing: border-box;
}
.calendar-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.calendar-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 100%;
    min-width: 280px;
    background: white;
    border-radius: 6px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 8px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.nav-group {
    display: flex;
}

.month-year {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    text-transform: capitalize;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background 0.2s;
    user-select: none;
}

.nav-btn {
    background: none;
    border: none;
    cursor: pointer;
}
.calendar-main{
    padding: 8px 18px;
}
.weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.weekday {
    text-align: center;
    font-size: 14px;
    font-weight: 400;
    padding: 4px 0;
}

.days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.day-cell {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.15s ease;
    padding: 3px 5px;
    height: 30px;
    width: 34px;
    margin: 0 auto;
    user-select: none;

    span{
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid transparent;
    }
}

.day-cell:not(.empty):not(.disabled):hover {
    span{
        background:rgba(0, 0, 0, 0.04);
    }

}

.day-cell.empty {
    color: #ccc;
    cursor: default;
}

.day-cell.selected {
    span{
        background:rgba(53, 140, 49, 1);
        color: white;
    }
}
.day-cell.selected:hover{
    span{
        background:rgba(53, 140, 49, 1) !important;
    }
}
.day-cell.today {
    span{
        border: 1px solid rgba(53, 140, 49, 1);
    }
}

.day-cell.today.selected {
    border-color: white;
}

.day-cell.disabled {
    color: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

.day-cell.disabled:hover {
    background: none;
    transform: none;
}

.calendar-footer {
    display: flex;
    justify-content: space-between;
    padding: 8px;
    border-top: 1px solid #f0f0f0;
}

.today-btn,
.clear-btn {
    padding: 6px 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 80px;
}

.today-btn {
    background: rgba(53, 140, 49, 1);
    color: white;
}

.today-btn:hover {
    background: #3a7bc8;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(74, 144, 217, 0.3);
}

.today-btn:active {
    background: #2d6ba8;
    transform: translateY(0);
}

.clear-btn {
    background: #f0f0f0;
    color: #555;
}

.clear-btn:hover {
    background: #e0e0e0;
    transform: translateY(-1px);
}

.clear-btn:active {
    background: #d0d0d0;
    transform: translateY(0);
}

/* Адаптивность */
@media (max-width: 480px) {
    .calendar-dropdown {
        min-width: unset;
        width: 100%;
        left: 0;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 340px;
    }

    .birthday-picker {
        max-width: 100%;
    }

    .nav-btn {
        font-size: 16px;
        min-width: 28px;
        min-height: 28px;
        padding: 2px 6px;
    }

    .nav-double {
        font-size: 14px;
    }

    .day-cell {
        height: 32px;
        width: 32px;
        font-size: 13px;
    }
}
</style>
