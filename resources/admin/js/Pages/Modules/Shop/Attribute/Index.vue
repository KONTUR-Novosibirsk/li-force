<template>
    <div class="mb-3">
        <CategoryFormModal @addCategory="addCategory"/>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-3 mb-3">
        <el-card body-class="p-2" v-for="(category, index) in categoriesData || []" :key="category.id">
            <div class="flex justify-between">
                <el-button link>
                    <Icon icon="material-symbols:folder" class="me-1"/>
<!--                    //:href="route('admin.iblock.category.show', category.id)"-->
                    <Link
                          class="el-link el-link--default is-underline">
                        {{ category.name }}
                    </Link>
                </el-button>
                <div class="my-auto">
                    <CategoryFormModal :category="category" :index="index" size="small"
                                       @updateCategory="updateCategory"/>
                    <v-modal-delete :url="route('admin.attribute.category.delete', category.id)"  :index="index"
                                    @onDelete="deleteCategory">Удалить
                    </v-modal-delete>
                </div>
            </div>
        </el-card>
    </div>
    <v-grid :data="attributes">
        <template #filter>
            <div class="flex justify-between">
                <div class="">
                    <el-input
                        v-model="formFilters.search"
                        style="max-width: 600px"
                        placeholder="Начните ввод для поиска"
                        class="input-with-select"
                        :clearable="true"
                    >
                        <template #prepend>
                            <el-button><Icon icon="icon-park-outline:search"/></el-button>
                        </template>
                        <template #append>
                            <el-select v-model="formFilters.type" placeholder="Выберите фильтр" style="width: 115px"
                                       :clearable="true">
                                <el-option value="input" label="Строка"/>
                                <el-option value="select" label="Выпадающий список"/>
                                <el-option value="multi_select" label="Множественный выбор"/>
                            </el-select>
                        </template>
                    </el-input>
                </div>
                <FormModal/>
            </div>
        </template>
        <el-table-column prop="name" label="Название" sortable="custom"/>
        <el-table-column prop="type" label="Тип" sortable="custom">
            <template #default="prop">
                {{ types[prop.row.type] }}
            </template>
        </el-table-column>
        <el-table-column label="Управление">
            <template #default="prop">
                <el-space wrap>
                    <FormModal :categories="categoriesData" :attribute="prop.row" size="small"/>
                    <v-modal-delete :url="route('admin.shop.attribute.delete', prop.row.id)" :refresh="true"/>
                </el-space>
            </template>
        </el-table-column>
    </v-grid>
</template>

<script>
import MainLayout from "../../../../Layouts/MainLayout.vue";
import FormModal from "./FormModal.vue";
import FilterMixin from "../../../../Shared/Mixins/FilterMixin";
import VGrid from "../../../../components/UI/Data/VGrid.vue";
import VModalDelete from "../../../../components/UI/Feedback/VModalDelete.vue";
import CategoryFormModal from "./CategoryForAttr/CategoryFormModal.vue";
import Edit from "../../IBlock/Edit.vue";

export default {
    name: "Index",
    layout: MainLayout,
    components: {Edit, CategoryFormModal, VModalDelete, VGrid, FormModal},
    mixins: [FilterMixin],
    props: {
        attributes: {
            type: Object,
            default: {},
        },
        categories: null
    },
    data() {
        return {
            filterUrl: route('admin.shop.attribute.index'),
            formFilters: {
                search: this.filters.search,
                type: this.filters.type
            },
            types: {
                select: 'Выпадающий список',
                input: 'Строка',
                multi_select: 'Множественный выбор',
            },
            categoriesData: [],
        }
    },
    mounted() {
        this.categoriesData = this.categories.data
    },
    methods: {
        deleteCategory(index) {
            this.categoriesData.splice(index, 1)
        },
        addCategory(category) {
            this.categoriesData.push(category)
        },
        updateCategory(index, category) {
            this.categoriesData[index] = category
        }
    }
}
</script>

<style scoped>

</style>
