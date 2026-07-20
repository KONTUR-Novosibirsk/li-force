<template>
    <el-form label-position="top">
        <el-row :gutter="20">
            <el-col :xs="24" :lg="16" :xl="18">
                <el-card header="Основное">
                    <form-item label="Название" :errors="errors['name']">
                        <el-input v-model="serviceValue.name" :clearable="true"/>
                    </form-item>
                    <form-item label="Короткое описание" :errors="errors['short_description']">
                        <v-editor v-model="serviceValue.short_description"/>
                    </form-item>
                    <form-item label="Описание" :errors="errors['description']">
                        <v-editor v-model="serviceValue.description"/>
                    </form-item>
                </el-card>
            </el-col>
            <el-col :xs="24" :lg="8" :xl="6">
                <div class="position-sticky-left-bar flex lg:flex-col-reverse">
                    <div class="">
                        <el-card header="Дополнительно" class="mb-3">
                            <form-item label="Алиас" :errors="errors['alias']">
                                <form-alias v-model="serviceValue.alias" :title="serviceValue.name"
                                            :in-real-time="aliasInRealTimeUpdate"/>
                            </form-item>
                            <form-item label="Стоимость" :errors="errors['price']">
                                <el-input v-model="serviceValue.price"/>
                            </form-item>
                            <form-item label="Активность" :errors="errors['is_active']">
                                <el-select v-model="serviceValue.is_active">
                                    <el-option label="Активно" :value="true"/>
                                    <el-option label="Не активно" :value="false"/>
                                </el-select>
                            </form-item>
                            <form-item label="Порядок сортировки" :errors="errors['sort']">
                                <el-input-number v-model="serviceValue.sort">
                                </el-input-number>
                            </form-item>
                        </el-card>
                        <el-card class="mb-3" body-class="p-2" header="Превью">
                            <v-upload-image v-model="serviceValue.image"
                                            :model_id="serviceValue.id"
                                            :model_type="model"
                                            group="preview"
                            />
                        </el-card>
                        <div class="mb-2">
                            <Drawer title="SEO" button="Seo">
                                <template v-slot:body>
                                    <seo-component v-model="serviceValue.seo" :errors="errors"></seo-component>
                                </template>
                            </Drawer>
                            <Drawer title="Галерея" button="Галерея">
                                <template v-slot:body>
                                    <v-upload-editor-images v-model="imagesData"
                                                            :model_id="serviceValue.id"
                                                            :model_type="model"
                                                            group="editor"
                                    ></v-upload-editor-images>
                                </template>
                            </Drawer>
                        </div>
                    </div>
                    <div class="mb-2">
                        <el-button :loading="loading" @click="save" type="primary">
                            Сохранить
                        </el-button>
                    </div>
                </div>
            </el-col>
        </el-row>
    </el-form>
</template>

<script>
import SeoComponent from "../../../components/seo/SeoComponent.vue";
import MainLayout from "../../../Layouts/MainLayout.vue";
import {router} from "@inertiajs/vue3";
import HandleErrorsMixin from "../../../Shared/Mixins/HandleErrorsMixin";
import FormItem from "../../../components/UI/Form/FormItem.vue";
import VUploadImage from "../../../components/UI/Form/VUploadImage.vue";
import VEditor from "../../../components/UI/Form/VEditor.vue";
import FormAlias from "../../../components/UI/Form/FormAlias.vue";
import Drawer from "../../../components/UI/Feedback/Drawer.vue";
import {ElNotification} from "element-plus";
import VUploadEditorImages from "../../../components/UI/Form/VUploadEditorImages.vue";

export default {
    name: "Edit",
    layout: MainLayout,
    mixins: [HandleErrorsMixin],
    props: {
        model: null,
        service: null,
        images: null,
        preview: null,
    },
    data() {
        return {
            loading: false,
            imagesData: this.images.data,
            serviceValue: {
                name: null,
                image: null,
                alias: null,
                price: null,
                is_active: true,
                description: '',
                short_description: '',
                seo: null,
                sort: 0
            },
            aliasInRealTimeUpdate: true,
        }
    },
    components: {
        VUploadEditorImages,
        Drawer, FormAlias, VEditor, VUploadImage, FormItem,
        MainLayout,
        SeoComponent,
    },
    mounted() {
        if (this.service) {
            this.serviceValue = this.service.data
            if (this.service.data.name) {
                this.aliasInRealTimeUpdate = false
            }
        }
        if (this.preview)
            this.serviceValue.image = this.preview.data;
    },
    methods: {
        save() {
            this.loading = true
            this.errors = []
            if (this.service) {
                this.update().finally(() => {
                    this.loading = false
                })
            } else {
                this.store().finally(() => {
                    this.loading = false
                })
            }
        },
        async update() {
            await axios.patch(route('admin.services.update', this.service.data.id), this.serviceValue).then((response) => {
                this.serviceValue = response.data.data
                ElNotification.success({message: 'Услуга сохранена', position: 'bottom-right'});
            }).catch((error) => {
                this.handleErrors(error)
            })
        },
        async store() {
            await axios.post(route('admin.services.store'), this.serviceValue).then((response) => {
                router.visit(route('admin.services.edit', response.data.data.id));
                ElNotification.success({message: 'Услуга создана', position: 'bottom-right'});
            }).catch((error) => {
                this.handleErrors(error)
            })
        }
    }
}
</script>

<style scoped>

</style>
