<script setup>
import { ref } from "vue";
import axios from "axios";
import InputField from "../Ui/Form/InputField.vue";

// читаем параметры из URL
const query = new URLSearchParams(window.location.search);

let email = ref(query.get('email') || "");
let token = ref(query.get('token') || "");

let password = ref("");
let password_confirmation = ref("");

function resetPassword() {
    axios.patch('/account/password/update', {
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
        token: token.value,
    })
        .then(res => {
            alert(res.data.message);
            if (res.data.success) {
                window.location.href = '/';
            }
        })
        .catch(err => console.error(err.response?.data || err));
}
</script>

<template>
    <div class="feedback-popup__heading">
        Сброс пароля
    </div>

    <div class="feedback-popup__content auth-form">
        <form>
            <div class="feedback-popup__item">
                <InputField
                    name="email"
                    type="email"
                    label="email"
                    placeholder="email"
                    v-model="email"
                />
            </div>
            <div class="feedback-popup__item">
                <InputField
                    name="password"
                    type="text"
                    label="password"
                    placeholder="password"
                    v-model="password"
                />

            </div>
            <div class="feedback-popup__item">
                <InputField
                    name="password_confirmation"
                    type="password_confirmation"
                    label="password_confirmation"
                    placeholder="password_confirmation"
                    v-model="password_confirmation"
                />
            </div>
            <button @click.prevent="resetPassword" class="feedback-btn">Сбросить пароль</button>
        </form>
    </div>
</template>
<style scoped>
.success-message {
    color: #16a34a; /* зелёный */
    font-weight: 500;
    margin-bottom: 1rem;
}
</style>
