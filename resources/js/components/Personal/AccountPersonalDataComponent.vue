<template>
    <h3>Личные данные и настройки</h3>
    <div class="personal-data">
        <div v-if="!accountValue.saby_id" class="personal-data__message">
            Контрагент не создан в системе САБИ<br>
            Что бы совершать заказы на сайте нужно создать контрагента в системе САБИ! Для этого нужно выбрать тип контрагента и заполнить внимательно все поля.
        </div>
        <div v-else class="personal-data__success-message">
            Контрагент создан в системе САБИ
        </div>
        <div class="pb-10">
            <RadioGroup
                v-model="accountValue.counterparty.type"
                :options="counterpartyOptions"
                name="payment"
                title="Выберите тип контрагента"
            />
        </div>
        <form @submit.prevent="update">
            <div>
                <InputField name="full_name" type="text" label="ФИО" placeholder="Иван" v-model="accountValue.full_name" :errors="errors['full_name']"/>
            </div>
            <div>
                <RadioGroup name="gender" :options="genderOptions" v-model="accountValue.gender"/>
            </div>
            <div>
                <PhoneField name="phone" label="Телефон" v-model="accountValue.phone" :errors="errors['phone']" readonly=""/>
            </div>
            <div>
                <BirthdayPicker
                    v-model="accountValue.birth_date"
                    :minDate="'1900-01-01'"
                    :maxDate="today"
                    placeholder="Дата рождения"
                />
            </div>
            <div>
                <InputField disabled="disabled" name="email" type="email" label="E-mail" placeholder="ygencjcu@gmail.com" v-model="accountValue.email" :errors="errors['email']"/>
            </div>
            <div>
                <PhoneField name="phone" label="Ваш телефон" placeholder="+7 (999) 999-99-99" v-model="accountValue.phone" :errors="errors['phone']"/>
            </div>
            <div v-for="(item, key) in accountValue.counterparty.data" :key="key">
                <div>
                    <InputField
                        :name="`counterparty.data.${key}`"
                        type="text"
                        :label="item.name"
                        :placeholder="item.name"
                        v-model="item.value"
                        :errors="errors[`counterparty.data.${key}.value`]"
                    />
                </div>
            </div>

            <!-- Секция смены пароля -->
            <div class="reset-password" :class="{ 'active': isPasswordActive }">
                <div v-if="isPasswordActive" class="flex flex-col gap-[20px]">
                    <InputField
                        name="current_password"
                        type="password"
                        label="Текущий пароль"
                        placeholder="Текущий пароль"
                        v-model="accountValue.current_password"
                        :errors="errors['current_password']"
                    />
                    <InputField
                        name="password"
                        type="password"
                        label="Новый пароль"
                        placeholder="Новый пароль"
                        v-model="accountValue.password"
                        :errors="errors['password']"
                    />
                    <InputField
                        name="password_confirmation"
                        type="password"
                        label="Подтвердите новый пароль"
                        placeholder="Подтвердите новый пароль"
                        v-model="accountValue.password_confirmation"
                        :errors="errors['password_confirmation']"
                    />
                </div>
                <button type="button" @click="togglePasswordFields" v-if="!isPasswordActive">
                    {{ isPasswordActive ? 'Отменить' : 'Сменить пароль' }}
                </button>
            </div>

            <button type="submit" class="confirm-btn">Подтвердить изменения</button>
        </form>
    </div>
    <div class="loader-frame" v-if="loading">
        <div class="loader"></div>
    </div>
</template>

<script>
import InputField from "@/components/Ui/Form/InputField.vue";
import { ElNotification } from "element-plus";
import PhoneField from "@/components/Ui/Form/PhoneField.vue";
import RadioGroup from "../Ui/Form/RadioGroup.vue";
import BirthdayPicker from "../Ui/Form/BirthdayPicker.vue";

export default {
    name: "AccountPersonalDataComponent",
    components: {BirthdayPicker, RadioGroup, PhoneField, InputField },

    props: {
        personalData: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        const today = new Date().toISOString().split('T')[0];

        return {
            completed: false,
            loading: false,
            isPasswordActive: false,
            today: today,
            accountValue: {
                full_name: '',
                email: '',
                phone: '',
                gender: null,
                birth_date: null,
                counterparty: {
                    type: null,
                    data: {}
                },
                saby_id: null,
                current_password: '',
                password: '',
                password_confirmation: '',
            },
            errors: {},
            counterpartyOptions: [
                {
                    value: 'natural_person',
                    label: 'Физическое лицо'
                },
                {
                    value: 'individual_entrepreneur',
                    label: 'Индивидуальный предприниматель'
                },
                {
                    value: 'organization',
                    label: 'Организация'
                }
            ],
            counterpartyData: {
                natural_person: {
                    patronymic: {
                        name: 'Отчество',
                        value: null
                    },
                    inn: {
                        name: 'ИНН',
                        value: ''
                    },
                    snils: {
                        name: 'СНИЛС',
                        value: ''
                    }
                },
                individual_entrepreneur: {
                    patronymic: {
                        name: 'Отчество',
                        value: null
                    },
                    inn: {
                        name: 'ИНН',
                        value: ''
                    },
                },
                organization: {
                    inn: {
                        name: 'ИНН',
                        value: ''
                    },
                    kpp: {
                        name: 'КПП',
                        value: null
                    },
                    name: {
                        name: 'Название организации',
                        value: null
                    },
                }
            },
            genderOptions: [
                {
                    value: 'male',
                    label: 'Мужчина'
                },
                {
                    value: 'female',
                    label: 'Женщина'
                }
            ],
        }
    },
    mounted() {
        if (this.personalData) {
            this.accountValue.full_name = this.personalData.full_name || '';
            this.accountValue.email = this.personalData.email || '';
            this.accountValue.phone = this.personalData.phone || '';
            this.accountValue.saby_id = this.personalData.saby_id ?? null;
            this.accountValue.gender = this.personalData.gender || null;

            // Загрузка даты рождения
            if (this.personalData.birth_date) {
                // Если дата приходит в формате строки, конвертируем
                if (typeof this.personalData.birth_date === 'string') {
                    this.accountValue.birth_date = this.personalData.birth_date;
                } else {
                    this.accountValue.birth_date = this.personalData.birth_date;
                }
            }

            // Загрузка данных контрагента
            if (this.personalData.counterparty) {
                this.accountValue.counterparty = this.personalData.counterparty;
                // Копируем данные в counterpartyData для отображения
                if (this.accountValue.counterparty.type && this.accountValue.counterparty.data) {
                    // Обновляем counterpartyData текущими значениями
                    const type = this.accountValue.counterparty.type;
                    if (this.counterpartyData[type]) {
                        // Сохраняем текущие значения
                        const currentData = this.accountValue.counterparty.data;
                        // Обновляем counterpartyData
                        for (const key in currentData) {
                            if (this.counterpartyData[type][key]) {
                                this.counterpartyData[type][key].value = currentData[key].value;
                            }
                        }
                        // Присваиваем данные в accountValue
                        this.accountValue.counterparty.data = this.counterpartyData[type];
                    }
                }
            } else {
                this.accountValue.counterparty.type = 'natural_person';
                this.accountValue.counterparty.data = this.counterpartyData[this.accountValue.counterparty.type];
            }
        }
    },
    watch: {
        'accountValue.counterparty.type'(newVal) {
            // Создаем копию данных нового типа
            const newData = {};
            const typeData = this.counterpartyData[newVal];
            for (const key in typeData) {
                newData[key] = {
                    ...typeData[key]
                };
            }
            this.accountValue.counterparty.data = newData;
        }
    },
    methods: {
        togglePasswordFields() {
            this.isPasswordActive = !this.isPasswordActive;
            // Очищаем поля пароля при скрытии
            if (!this.isPasswordActive) {
                this.accountValue.current_password = '';
                this.accountValue.password = '';
                this.accountValue.password_confirmation = '';
                // Удаляем ошибки, связанные с паролем
                if (this.errors.current_password) delete this.errors.current_password;
                if (this.errors.password) delete this.errors.password;
                if (this.errors.password_confirmation) delete this.errors.password_confirmation;
            }
        },
        update() {
            this.loading = true;
            this.errors = {};

            // Создаем объект для отправки
            const dataToSend = {...this.accountValue};

            // Если не меняем пароль, удаляем поля пароля из запроса
            if (!this.isPasswordActive) {
                delete dataToSend.current_password;
                delete dataToSend.password;
                delete dataToSend.password_confirmation;
            }

            // Форматируем дату для отправки
            if (dataToSend.birth_date) {
                // Если это объект Date или строка, приводим к формату YYYY-MM-DD
                if (dataToSend.birth_date instanceof Date) {
                    dataToSend.birth_date = dataToSend.birth_date.toISOString().split('T')[0];
                } else if (typeof dataToSend.birth_date === 'string' && dataToSend.birth_date.includes('/')) {
                    // Если строка в формате DD/MM/YYYY или похожем
                    const parts = dataToSend.birth_date.split('/');
                    if (parts.length === 3) {
                        // Предполагаем формат DD/MM/YYYY
                        dataToSend.birth_date = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }
                }
            }

            axios.patch('/account/me/update', dataToSend)
                .then((response) => {
                    this.completed = true;
                    this.errors = {};

                    // Сбрасываем поля пароля после успешного обновления
                    this.accountValue.current_password = '';
                    this.accountValue.password = '';
                    this.accountValue.password_confirmation = '';
                    this.isPasswordActive = false;

                    if (response.data.saby_id) {
                        this.accountValue.saby_id = response.data.saby_id;
                    }

                    ElNotification({
                        title: 'Система',
                        message: 'Данные успешно сохранены!',
                        type: 'success',
                        position: 'bottom-right',
                    });
                })
                .catch((error) => {
                    if (error.response) {
                        switch (error.response.status) {
                            case 422:
                                this.errors = error.response.data.errors || {};
                                break;
                            default:
                                ElNotification({
                                    title: 'Ошибка',
                                    message: error.response.data.message || 'Произошла неизвестная ошибка',
                                    type: 'error',
                                    position: 'bottom-right',
                                });
                        }
                    } else {
                        ElNotification({
                            title: 'Ошибка',
                            message: 'Ошибка соединения с сервером',
                            type: 'error',
                            position: 'bottom-right',
                        });
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    }
}
</script>

<style scoped>
.personal-data form {
    width: 100%;
    max-width: 352px;
    grid-template-columns: 1fr;
}

.reset-password {
    margin: 20px 0;
}

.reset-password button {
    background: none;
    border: none;
    color: #4a90d9;
    cursor: pointer;
    padding: 8px 0;
    font-size: 14px;
    text-decoration: underline;
}

.reset-password button:hover {
    color: #357abd;
}

.confirm-btn {
    width: 100%;
    padding: 12px;
    background: #4a90d9;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.confirm-btn:hover {
    background: #357abd;
}

.loader-frame {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #4a90d9;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.personal-data__message,
.personal-data__success-message {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.personal-data__message {
    background: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
}

.personal-data__success-message {
    background: #d4edda;
    border: 1px solid #28a745;
    color: #155724;
}
</style>
