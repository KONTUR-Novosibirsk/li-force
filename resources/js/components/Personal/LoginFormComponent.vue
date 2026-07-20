<template>
    <div class="feedback-popup__heading">
        Вход
    </div>
    <div class="feedback-popup__content auth-form">
        <div class="" v-if="completed"></div>
        <form @submit.prevent="login">
            <div class="feedback-popup__item">
                <InputField name="email" label="email" placeholder="email" v-model="form.email" :errors="errors['email']"/>
            </div>
            <div class="feedback-popup__item">
                <InputField name="password" type="password" label="Пароль" placeholder="Пароль*" v-model="form.password" :errors="errors['password']"/>
            </div>

            <div v-if="errors.non_field_errors" class="error-message">
                <p v-for="error in errors.non_field_errors" :key="error">{{ error }}</p>
            </div>


            <a href="/account/password/reset" class="feedback-popup__reset">Забыли пароль?</a>

            <p class="feedback-popup__privacy">
                <CheckboxField/>
                Запомнить меня
            </p>

            <button type="submit" class="feedback-btn">Авторизоваться</button>
            <a href="/account/register" class="feedback-popup__register">Зарегистрироваться</a>
        </form>
    </div>
    <div class="loader-frame" v-if="loading">
        <div class="loader"></div>
    </div>
</template>

<script>
import InputField from "../Ui/Form/InputField.vue";
import CheckboxField from "@/components/Ui/Form/CheckboxField.vue";
import { ElNotification } from 'element-plus';

export default {
    name: "LoginFormComponent",
    components: {CheckboxField, InputField},
    props: {
        privacyPolicyLink: null,
    },
    data() {
        return {
            completed: false,
            loading: false,
            errors: {},
            form: {
                email: null,
                password: null,
            }
        }
    },
    methods: {
        login() {
            this.loading = true;
            this.errors = {};

            axios.post('/account/authenticate', this.form)
                .then((response) => {
                    this.completed = true;
                    window.location.href = '/account/me';
                })
                .catch((error) => {
                    console.log('Ошибка:', error.response);

                    if (error.response) {
                        switch (error.response.status) {
                            case 401:
                                this.errors = {
                                    non_field_errors: ['Неверные учетные данные']
                                };
                                ElNotification({
                                    title: 'Ошибка',
                                    message: 'Неверные логин или пароль',
                                    type: 'error',
                                    position: 'bottom-right',
                                });
                                break;
                            case 422:
                                this.errors = error.response.data.errors || {};
                                break;
                            default:
                                this.errors = {
                                    non_field_errors: ['Произошла неизвестная ошибка']
                                };
                                ElNotification({
                                    title: 'Ошибка',
                                    message: 'Произошла неизвестная ошибка',
                                    type: 'error',
                                    position: 'bottom-right',
                                });
                        }
                    } else {
                        this.errors = {
                            non_field_errors: ['Ошибка сети или сервера']
                        };
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

</style>
