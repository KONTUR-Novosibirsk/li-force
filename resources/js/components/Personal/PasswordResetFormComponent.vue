<template>
    <div class="feedback-popup__heading">
        Сброс пароля
    </div>

    <div class="feedback-popup__content auth-form">
        <!-- Сообщение об успехе -->
<!--        <div v-if="completed" class="success-message">-->
<!--            📩 Письмо для сброса пароля отправлено на ваш e-mail-->
<!--        </div>-->

        <!-- Форма запроса сброса пароля -->
        <form @submit.prevent="sendResetLink">
            <div class="feedback-popup__item">
                <InputField
                    name="email"
                    type="email"
                    label="E-mail"
                    placeholder="Email*"
                    v-model="form.email"
                    :errors="errors['email']"
                />
            </div>
            <button type="submit" class="feedback-btn">Отправить</button>
        </form>
    </div>

    <div class="loader-frame" v-if="loading">
        <div class="loader"></div>
    </div>
</template>

<script>
import InputField from "../Ui/Form/InputField.vue";

export default {
    name: "PasswordResetRequest",
    components: { InputField },
    data() {
        return {
            // completed: false,
            loading: false,
            errors: {},
            form: {
                email: null,
            },
        };
    },
    methods: {
        async sendResetLink() {
            this.loading = true;
            this.errors = {};

            try {
                await axios.post("/account/password/send-reset-link", this.form);
                // this.completed = true;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.success-message {
    color: #16a34a; /* зелёный */
    font-weight: 500;
    margin-bottom: 1rem;
}
</style>
