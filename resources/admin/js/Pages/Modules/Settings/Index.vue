<template>
    <div class="flex justify-between mb-4">
        <el-button :loading="loading" @click="save">Сохранить</el-button>
        <div class="" v-if="!group">
            <Drawer title="Внешний вид" button="Внешний вид">
                <template #body>
                    <AppearanceForm/>
                </template>
            </Drawer>
            <Drawer title="Команды" button="Команды">
                <template #body>
                    <CommandComponent/>
                </template>
            </Drawer>
            <Drawer v-if="envArray" title="Настройка .env" button="Настройка .env">
                <template #body>
                    <EnvForm :env-array="envArray" />
                </template>
            </Drawer>
        </div>
    </div>
    <el-card v-loading="loading">
        <el-tabs>
            <el-form label-position="left" label-width="auto" :scroll-to-error="true">
                <el-tab-pane v-for="(container,key,index) in formElementContainers.data" :label="container.name">
                    <template v-for="(element,indexElement) in container.formElements">
                        <form-item :label="element.config.labelName" :errors="errors[element['name']]">
                            <el-input v-if="element.config.element === 'input'"
                                      v-model="formElementContainers.data[key].formElements[indexElement].value"></el-input>
                            <el-input type="textarea" v-else-if="element.config.element === 'textarea'"
                                      :rows="8"
                                      v-model="formElementContainers.data[key].formElements[indexElement].value"></el-input>
                            <el-input v-else-if="element.config.element === 'textarea'"
                                      v-model="formElementContainers.data[key].formElements[indexElement].value"></el-input>
                            <el-checkbox v-else-if="element.config.element === 'checkbox'"
                                         v-model="formElementContainers.data[key].formElements[indexElement].value"></el-checkbox>
                            <el-select v-else-if="element.config.element === 'select'"
                                       :placeholder="element.config.labelName"
                                       v-model="formElementContainers.data[key].formElements[indexElement].value">
                                <el-option v-for="item in element.config.data"
                                           :key="item.key"
                                           :label="item.value"
                                           :value="item.key"></el-option>
                            </el-select>
                            <v-editor v-else-if="element.config.element === 'editor'"
                                    v-model="formElementContainers.data[key].formElements[indexElement].value"/>
                        </form-item>
                    </template>
                </el-tab-pane>
            </el-form>
        </el-tabs>
    </el-card>
</template>

<script>
import MainLayout from "../../../Layouts/MainLayout.vue";
import AppearanceForm from "./AppearanceForm.vue";
import CommandComponent from "./CommandComponent.vue";
import EnvForm from "./EnvForm.vue";
import VEditor from "../../../components/UI/Form/VEditor.vue";
import FormItem from "../../../components/UI/Form/FormItem.vue";
import Drawer from "../../../components/UI/Feedback/Drawer.vue";
import HandleErrorsMixin from "../../../Shared/Mixins/HandleErrorsMixin";
import {ElNotification} from "element-plus";

export default {
    name: "Index",
    layout: MainLayout,
    components: {
        Drawer,
        FormItem,
        VEditor,
        CommandComponent,
        AppearanceForm,
        EnvForm
    },
    mixins: [HandleErrorsMixin],
    props: {
        group: null,
        formElementContainers: {
            type: Object,
            default: {},
        },
        envArray: null
    },
    data() {
        return {
            loading: false,
        }
    },
    methods: {
        async dataForSave() {
            let data = {}
            for (let index in this.formElementContainers.data) {
                for (let elementIndex in this.formElementContainers.data[index].formElements) {
                    let element = this.formElementContainers.data[index].formElements[elementIndex]
                    data[element['name']] = element['value']
                }
            }
            return data
        },
        save() {
            this.errors = []
            this.loading = true
            this.dataForSave().then((data) => {
                axios.post(route('admin.settings.save', this.group), data).then((response) => {
                    ElNotification({
                        title: 'Система',
                        message: 'Настройки успешно сохранены',
                        type: 'success',
                        position: 'bottom-right',
                    })
                }).catch((errors) => {
                    this.handleErrors(errors)
                }).finally(() => {
                    this.loading = false
                })
            })
        }
    },
}
</script>

<style scoped>

</style>
