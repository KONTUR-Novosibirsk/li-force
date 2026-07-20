<template>
    <div class="feedback-popup__heading">
        Регистрация
    </div>
    <div class="feedback-popup__content auth-form">
        <div class="" v-if="completed"></div>
        <form @submit.prevent="store">
            <div class="feedback-popup__item">
                <InputField name="email" type="email" label="E-mail" placeholder="Email*" v-model="form.email" :errors="errors['email']"/>
            </div>
            <div class="feedback-popup__item">
                <InputField name="login" label="Имя" placeholder="Имя пользователя*" v-model="form.login" :errors="errors['login']"/>
            </div>
            <div class="feedback-popup__item">
                <InputField name="password" type="password" label="Пароль" placeholder="Пароль*" v-model="form.password" :errors="errors['password']"/>
            </div>
            <div class="feedback-popup__privacy" v-if="privacyPolicyLink">
                <CheckboxField v-model="form.policy" class="checkbox-inp" :errors="errors['policy']"/>
                <span>Я ознакомлен и согласен на  <a :href="privacyPolicyLink">обработку персональных данных</a></span>
            </div>
            <button type="submit" class="feedback-btn">Зарегистрироваться</button>
            <div class="feedback-popup__login">Уже зарегистрированы? <a href="/account/login">Авторизуйтесь</a></div>
<!--            <button type="button" class="outline-btn" >Зарегистрироваться</button>-->
        </form>
    </div>
    <div class="loader-frame" v-if="loading">
        <div class="loader"></div>
    </div>
</template>

<script>
import InputField from "../Ui/Form/InputField.vue";
import CheckboxField from "@/components/Ui/Form/CheckboxField.vue";

export default {
    name: "RegisterFormComponent",
    components: {CheckboxField, InputField},
    props: {
        privacyPolicyLink: null,
    },
    data() {
        return {
            completed: false,
            loading: false,
            errors: [],
            form: {
                email: null,
                password: null,
                login: null,
                policy:null,
            }
        }
    },
    methods: {
        store() {
            if (!this.form.policy) {
                this.errors = { policy: ['Необходимо согласие на обработку персональных данных'] };
                return;
            }
            this.loading = true
            axios.post('/account/store', this.form).then((response) => {
                this.completed = true
                window.location.href = '/account/me'
            }).catch((error) => {
                if (error.response['status'] === 422) {
                    this.errors = error.response.data.errors
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
