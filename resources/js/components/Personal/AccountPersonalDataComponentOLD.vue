<template>
    <div class="personal-data">
        <div class="personal-data__heading">Личные данные</div>
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
                <InputField name="full_name" type="text" label="Имя" placeholder="Иван" v-model="accountValue.full_name" :errors="errors['full_name']"/>
            </div>
            <div>
                <InputField name="last_name" type="text" label="Фамилия" placeholder="Фамилия" v-model="accountValue.last_name" :errors="errors['last_name']"/>
            </div>
            <div>
                <InputField disabled="disabled" name="email" type="email" label="E-mail" placeholder="ygencjcu@gmail.com" v-model="accountValue.email" :errors="errors['email']"/>
            </div>
            <div>
                <PhoneField name="phone" label="Ваш телефон" placeholder="+7 (999) 999-99-99" v-model="accountValue.phone" :errors="errors['phone']"/>
            </div>
            <div v-for="(item, key) in accountValue.counterparty.data">
                <div>
                    <InputField :name='item.name' type="text" :label='item.name' :placeholder='item.name' v-model="item.value" :errors="errors[`counterparty.data.${key}.value`]"/>
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

export default {
    name: "AccountPersonalDataComponent",
    components: {RadioGroup, PhoneField, InputField },
    props: {
        personalData: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            completed: false,
            loading: false,
            isPasswordActive: false,
            accountValue: {
                full_name: '',
                last_name: '',
                email: '',
                phone: '',
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
            }
        }
    },
    mounted() {
        if (this.personalData) {
            this.accountValue.full_name = this.personalData.full_name || '';
            this.accountValue.last_name = this.personalData.last_name || '';
            this.accountValue.email = this.personalData.email || '';
            this.accountValue.phone = this.personalData.phone || '';
            this.accountValue.saby_id = this.personalData.saby_id ?? null;
            if (this.personalData.counterparty) {
                this.accountValue.counterparty = this.personalData.counterparty;
                this.counterpartyData[this.accountValue.counterparty.type] = this.accountValue.counterparty.data;
            }
            else {
                this.accountValue.counterparty.type = 'natural_person';
                this.accountValue.counterparty.data = this.counterpartyData[this.accountValue.counterparty.type];
            }
        }
    },
    watch: {
        'accountValue.counterparty.type'(newVal, oldVal) {
            this.accountValue.counterparty.data = {
                ...this.counterpartyData[newVal]
            };
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

            // Создаем объект для отправки
            const dataToSend = { ...this.accountValue };

            // Если не меняем пароль, удаляем поля пароля из запроса
            if (!this.isPasswordActive) {
                delete dataToSend.current_password;
                delete dataToSend.password;
                delete dataToSend.password_confirmation;
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
                    this.accountValue.saby_id = response.data.saby_id;

                    ElNotification({
                        title: 'Система',
                        message: 'Данные успешно сохранены!',
                        type: 'success',
                        position: 'bottom-right',
                    });
                })
                .catch((error) => {
                    switch (error.response.status) {
                        case 422:
                            this.errors = error.response.data.errors;
                            break;
                        default:
                            ElNotification({
                                title: 'Ошибка',
                                message: 'Произошла неизвестная ошибка',
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


