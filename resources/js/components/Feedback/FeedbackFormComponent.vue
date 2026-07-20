<template>
    <div class="feedback-popup__heading">Производство по вашему техническому заданию</div>
    <p>Спроектируем и изготовим аккумулятор любой модификации и мощности для юридических лиц и ИП</p>
    <div class="feedback-popup__content">
        <div class="" v-if="completed">
            Данные успешно отправлены, наш менеджер свяжется с вами в ближайшее время.
        </div>
        <form v-else @submit.prevent="store">
            <div class="feedback-popup__items">
                <div class="feedback-popup__item">
                    <InputField name="name" label="Имя" placeholder="Ваше имя*" v-model="form.name" :errors="errors['name']"/>
                </div>
                <div class="feedback-popup__item">
                    <PhoneField name="phone" label="Ваш телефон" placeholder="+7 (___) ___-__-__*" v-model="form.phone" :errors="errors['phone']"/>
                </div>
                <div class="feedback-popup__item">
                    <InputField name="email" label="Email" placeholder="E-mail" v-model="form.email" :errors="errors['email']"/>
                </div>
            </div>

            <button type="submit" class="feedback-btn">
                Заказать звонок
            </button>
            <div class="feedback-popup__privacy" v-if="privacyPolicyLink">
                <CheckboxField v-model="form.policy" class="checkbox-inp" :errors="errors['policy']"/>
                <span> Согласен на обработку <a :href="personalityLink">персональных данных</a> и ознакомлен с <a :href="privacyPolicyLink">политкой конфиденциальности </a></span>
            </div>

        </form>
    </div>
    <div class="loader-frame" v-if="loading">
        <div class="loader"></div>
    </div>
</template>

<script>
import InputField from "../Ui/Form/InputField.vue";
import PhoneField from "../Ui/Form/PhoneField.vue";
import FeedbackMixin from "../../Share/Mixins/FeedbackMixin";
import CheckboxField from "../Ui/Form/CheckboxField.vue";

export default {
    name: "FeedbackFormComponent",
    components: {CheckboxField, InputField, PhoneField},
    mixins: [FeedbackMixin],
    props: {
        privacyPolicyLink: null,
        personalityLink: null,
    },
    data() {
        return {
            name: 'callback',
            form: {
                name: null,
                email: null,
                phone: null,
                policy: null,
            }
        }
    },
    methods: {}
}
</script>

<style scoped>

</style>
