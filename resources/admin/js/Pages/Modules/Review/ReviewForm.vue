<template>
    <el-button @click="modal = true" v-if="!hide" type="primary">
        {{ buttonName }}
    </el-button>
    <el-dialog v-model="modal" :title="buttonName" :append-to-body="true">
        <el-form label-position="top">
            <form-item label="Имя" :errors="errors['name']">
                <el-input v-model="reviewData.name"/>
            </form-item>
            <form-item label="Оценка" :errors="errors['rating']">
                <el-input-number v-model="reviewData.rating"/>
            </form-item>
            <form-item label="Источник" :errors="errors['source']">
                <el-select v-model="reviewData.source">
                    <el-option v-for="(label, value) in reviewSources" :key="value" :label="label" :value="value"/>
                </el-select>
            </form-item>
            <form-item label="Отзыв" :errors="errors['text']">
                <el-input v-model="reviewData.text" type="textarea" :rows="5" placeholder="Введите текст отзыва"/>
            </form-item>
        </el-form>
        <el-button @click="save" type="primary" :loading="loading">
            Сохранить
        </el-button>
        <Drawer title="Изображения" button="Изображения">
            <template v-slot:body>
                <v-upload-editor-images v-model="imagesData"
                                        :model_id="reviewData.id"
                                        :model_type="model"
                                        group="editor"/>
            </template>
        </Drawer>
        <Drawer title="Видео" button="Видео">
            <template v-slot:body>
                <v-upload-files v-model="videosData"
                                :model_id="reviewData.id"
                                :model_type="model"
                                group="editor"
                                type="public"/>
            </template>
        </Drawer>
    </el-dialog>
</template>

<script>
import VEditor from "../../../components/UI/Form/VEditor.vue";
import {router} from "@inertiajs/vue3";
import HandleErrorsMixin from "../../../Shared/Mixins/HandleErrorsMixin";
import FormItem from "../../../components/UI/Form/FormItem.vue";
import {ElNotification} from "element-plus";
import Drawer from "../../../components/UI/Feedback/Drawer.vue";
import VUploadFiles from "../../../components/UI/Form/VUploadFiles.vue";
import VUploadEditorImages from "../../../components/UI/Form/VUploadEditorImages.vue";

export default {
    name: "ReviewForm",
    components: {VUploadEditorImages, VUploadFiles, Drawer, FormItem, VEditor},
    mixins: [HandleErrorsMixin],
    props: {
        review: null,
        index: null,
        hide: false,
        reviewSources: [],
        model: null
    },
    data() {
        return {
            loading: false,
            modal: false,
            reviewData: {
                name: '',
                rating: null,
                source : null,
                text: null,
                status: false
            },
            imagesData: [],
            videosData: []
        }
    },
    watch: {
        review(newReview) {
            if (newReview) {
                this.reviewData = {...newReview};
                this.imagesData = newReview.images ?? [];
                this.videosData = newReview.videos ?? [];
            }
        }
    },
    updated() {
        if (this.review) this.reviewData = {...this.review}
    },
    computed: {
        buttonName() {
            return this.hide ? null : 'Добавить'
        }
    },
    methods: {
        showModal() {
            this.modal = !this.modal
        },
        save() {
            this.loading = true
            this.errors = []
            if (this.review) {
                this.update()
            } else {
                this.store()
            }
        },
        update() {
            axios.patch(route('admin.review.update', this.reviewData.id), this.reviewData).then((response) => {
                ElNotification.success({message: 'Отзыв обновлен', position: 'bottom-right'})
                this.reviewData = response.data.data
                this.$emit('updateReview', {...this.reviewData}, this.index)
                this.modal = false
            }).catch((error) => {
                this.handleErrors(error)
            }).finally(() => {
                this.loading = false

            })
        },
        store() {
            axios.post(route('admin.review.store'), this.reviewData).then((response) => {
                ElNotification.success({message: 'Отзыв добавлен', position: 'bottom-right'})
                this.modal = false
                router.get(route('admin.review.index'))
            }).catch((error) => {
                this.handleErrors(error)
            }).finally(() => {
                this.loading = false
            })
        }
    }
}
</script>
