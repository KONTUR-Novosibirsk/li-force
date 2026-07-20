<template>
    <VGrid :data="services">
        <template #filter>
            <div class="flex justify-between">
                <div class="flex items-center gap-2">
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
                            <el-select v-model="formFilters.active" placeholder="Выберите фильтр" style="width: 115px"
                                       :clearable="true">
                                <el-option label="Активные" value="1"/>
                                <el-option label="Не активные" value="0"/>
                            </el-select>
                        </template>
                    </el-input>
                    <el-button
                        type="primary"
                        size="small"
                        @click="toggleSortDirection"
                        title="Переключить направление сортировки"
                    >
                        <template v-if="sortAscending">
                            <i class="bi bi-sort-numeric-up"></i>
                        </template>
                        <template v-else>
                            <i class="bi bi-sort-numeric-down"></i>
                        </template>
                    </el-button>
                </div>
                <Link :href="route('admin.services.create')" class="el-button el-button--primary">Создать</Link>
            </div>
        </template>
        <el-table-column prop="id" label="#" width="60" sortable="custom"/>
        <el-table-column prop="name" label="Название" sortable="custom"/>
        <el-table-column prop="created_at" label="Дата создания" sortable="custom"/>
        <el-table-column prop="price" label="Стоимость" sortable="custom"/>
        <el-table-column prop="is_active" label="Активность">
            <template #default="scope">
                <el-tag v-if="scope.row.is_active" type="success">Активна</el-tag>
                <el-tag v-else type="danger">Не активна</el-tag>
            </template>
        </el-table-column>
        <el-table-column prop="sort" label="Порядок сортировки">
            <template #default="scope">
                <el-input-number
                    v-model="scope.row.sort"
                    @change="updateServiceSort(scope.row)"
                    size="small"
                    class="mr-2"
                />
            </template>
        </el-table-column>
        <el-table-column prop="active" label="Управление">
            <template #default="scope">
                <el-space wrap>
                    <PreviewPage :url="route('admin.services.show', scope.row.id)"
                                 class="el-button el-button--primary el-button--small">
                        <i class="bi bi-eye-fill"></i>
                    </PreviewPage>
                    <Link :href="route('admin.services.edit', scope.row.id)"
                          class="el-button el-button--primary el-button--small">
                        Редактировать
                    </Link>
                    <v-modal-delete :url="route('admin.services.delete', scope.row.id)" :refresh="true"/>
                </el-space>
            </template>
        </el-table-column>
    </VGrid>
</template>

<script>
import MainLayout from "../../../Layouts/MainLayout.vue";
import Pagination from "../../../Shared/Pagination.vue";
import FilterMixin from "../../../Shared/Mixins/FilterMixin";
import PreviewPage from "../../../Shared/PreviewPage.vue";
import VGrid from "../../../components/UI/Data/VGrid.vue";
import VModalDelete from "../../../components/UI/Feedback/VModalDelete.vue";
import {ElNotification} from "element-plus";

export default {
    name: "Index",
    layout: MainLayout,
    components: {VModalDelete, VGrid, PreviewPage, Pagination},
    mixins: [FilterMixin],
    props: {
        services: null,
    },
    data() {
        return {
            filterUrl: route('admin.services.index'),
            formFilters: {
                search: this.filters.search,
                active: this.filters.active
            },
            sortAscending: true
        }
    },
    methods: {
        updateServiceSort(service) {
            axios.post(route('admin.services.updateSort', service.id), {
                sort: service.sort
            }).then(() => {
                this.sortServices();
                ElNotification.success({ message: 'Сортировка обновлена', position: 'bottom-right' });
            }).catch(() => {
                ElNotification.error({ message: 'Ошибка при обновлении сортировки', position: 'bottom-right' });
            });
        },
        toggleSortDirection() {
            this.sortAscending = !this.sortAscending;
            this.sortServices();
        },
        sortServices() {
            if (!this.services || !this.services.data) return;

            const currentPage = this.services.meta.current_page || 1;

            axios.post(route('admin.services.getServicesSort'), {
                sort: this.sortAscending ? 'ASC' : 'DESC',
                page: currentPage
            }).then((response) => {
                this.services.data = response.data.services;
            }).catch(() => {
                ElNotification.error({ message: 'Ошибка при обновлении сортировки', position: 'bottom-right' });
            });
            this.services.data.sort((a, b) =>
                this.sortAscending ? a.sort - b.sort : b.sort - a.sort
            );
        }
    }
}
</script>

<style scoped>

</style>
