<template>
    <div v-if="show" class="cart-popup__container" style="">
        <div class="cart-popup">
            <div class="cart-popup__heading">Удалить выбранные товары?</div>
<!--            <div class="selected-count" v-if="selectedCount > 0">-->
<!--                Будет удалено: <strong>{{ selectedCount }} товар(ов)</strong>-->
<!--            </div>-->
            <div class="cart-popup__content">
                <button class="cart-popup__submit"@click="confirmDelete" :disabled="deleting">
                    <span v-if="deleting" class="btn-loader">
                    </span>
                    {{ deleting ? 'Удаление...' : 'Удалить' }}
                </button>
                <button class="cart-popup__close" @click="closeModal" :disabled="deleting">Отмена</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ConfirmationModal",
    props: {
        show: Boolean,
        selectedCount: Number
    },
    data() {
        return {
            deleting: false
        }
    },
    methods: {
        closeModal() {
            if (!this.deleting) {
                this.$emit('close');
            }
        },
        async confirmDelete() {
            this.deleting = true;
            try {
                await this.$emit('confirm');
            } finally {
                this.deleting = false;
            }
        }
    },
    watch: {
        show(newVal) {
            if (!newVal) {
                this.deleting = false;
            }
        }
    }
}
</script>

<style scoped>

</style>
