<template>
    <v-grid :data="reviewsData">
        <template #filter>
            <div class="flex justify-between">
                <div class="flex items-center gap-2">
                    <el-input
                        v-model="formFilters.search"
                        style="width: 600px"
                        placeholder="Начните ввод для поиска"
                        class="input-with-select"
                        :clearable="true"
                    >
                        <template #prepend>
                            <el-button><Icon icon="icon-park-outline:search"/></el-button>
                        </template>
                    </el-input>
                    <el-select v-model="formFilters.status" :clearable="true" placeholder="Статус">
                        <el-option value="1" label="Опубликованные"/>
                        <el-option value="0" label="На модерации"/>
                    </el-select>
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
                <ReviewForm :reviewSources="reviewSources" :model="model"/>
                <ReviewForm :reviewSources="reviewSources" :model="model"
                            :review="reviewForEdit"
                            :index="reviewForEditIndex"
                            :hide="true"
                            @clearEditData="clearEditData"
                            @updateReview="update"
                            ref="modal"/>
            </div>
        </template>
        <el-table-column prop="id" label="#" width="60" sortable="custom"/>
        <el-table-column prop="name" label="Имя" width="200" sortable="custom"/>
        <el-table-column prop="source_label" label="Источник" width="120"/>
        <el-table-column prop="product_title" label="Товар"/>
        <el-table-column prop="marketplace_product_id" label="Артикул товара" width="150" sortable="custom"/>
        <el-table-column prop="rating" label="Оценка" width="100" sortable="custom"/>
        <el-table-column prop="created_at" label="Дата публикации" width="200" sortable="custom"/>
        <el-table-column prop="status" label="Активность" width="200" sortable="custom">
            <template #default="prop">
                <el-tag type="success" v-if="prop.row.status == 1">Одобрено</el-tag>
                <el-tag type="danger" v-else>На модерации</el-tag>
            </template>
        </el-table-column>
        <el-table-column label="Управление">
            <template #default="prop">
                <el-space wrap>
                    <el-button type="primary" size="small" @click="moderate(prop.$index, prop.row)"
                               :loading="prop.$index == moderateIndex">
                        Изменить статус
                    </el-button>
                    <el-button type="primary" size="small" @click="setReviewForEdit(prop.row, prop.$index)">
                        Изменить
                    </el-button>
                    <v-modal-delete :url="route('admin.review.delete', prop.row.id)" :refresh="true"/>
                </el-space>
            </template>
        </el-table-column>
    </v-grid>
</template>

<script>
import MainLayout from "../../../Layouts/MainLayout.vue";
import ReviewForm from "./ReviewForm.vue";
import FilterMixin from "../../../Shared/Mixins/FilterMixin";
import VGrid from "../../../components/UI/Data/VGrid.vue";
import HandleErrorsMixin from "../../../Shared/Mixins/HandleErrorsMixin";
import VModalDelete from "../../../components/UI/Feedback/VModalDelete.vue";
import {ElNotification} from "element-plus";

export default {
    name: "Index",
    layout: MainLayout,
    mixins: [FilterMixin, HandleErrorsMixin],
    props: {
        reviews: null,
        reviewSources: [],
        model: null,
    },
    components: {VModalDelete, VGrid, ReviewForm},
    data() {
        return {
            moderateIndex: null,
            modal: null,
            reviewsData: this.reviews,
            reviewForEdit: null,
            reviewForEditIndex: null,
            filterUrl: route('admin.review.index'),
            formFilters: {
                search: this.filters.search,
                status: this.filters.status
            },
            sortAscending: true
        }
    },
    mounted() {
        this.modal = this.$refs.modal
    },
    updated() {
        this.reviewsData = this.reviews
    },
    methods: {
        moderate(index, review) {
            this.moderateIndex = index
            axios.patch(route('admin.review.status.change', review.id)).then((response) => {
                this.reviewsData.data[index] = response.data.data
            }).catch((error) => {
                this.handleErrors(error)
            }).finally(() => {
                this.moderateIndex = null
            })
        },
        readyModal(modal) {
            this.modal = modal
        },
        clearEditData() {
            this.reviewForEditIndex = null
            this.reviewForEdit = null
        },
        setReviewForEdit(review, index) {
            this.reviewForEditIndex = index
            this.reviewForEdit = review
            this.modal.showModal()
        },
        update(review, index) {
            this.clearEditData()
            this.reviewsData.data[index] = review
        },
        updateReviewSort(review) {
            axios.post(route('admin.review.updateSort', review.id), {
                sort: review.sort
            }).then(() => {
                this.sortReviews()
                ElNotification.success({ message: 'Сортировка обновлена', position: 'bottom-right' });
            }).catch(() => {
                ElNotification.error({ message: 'Ошибка при обновлении сортировки', position: 'bottom-right' });
            });
        },
        toggleSortDirection() {
            this.sortAscending = !this.sortAscending;
            this.sortReviews();
        },
        sortReviews() {
            if (!this.reviewsData || !this.reviewsData.data) return;

            const currentPage = this.reviewsData.meta.current_page || 1;

            axios.post(route('admin.review.getReviewSort'), {
                sort: this.sortAscending ? 'ASC' : 'DESC',
                page: currentPage
            }).then((response) => {
                this.reviewsData.data = response.data.reviews;
            }).catch(() => {
                ElNotification.error({ message: 'Ошибка при обновлении сортировки', position: 'bottom-right' });
            });
            this.reviewsData.data.sort((a, b) =>
                this.sortAscending ? a.sort - b.sort : b.sort - a.sort
            );
        }
    },
}
</script>

<style scoped>

</style>
