<template>

    <Head title="Mocktest List" />
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Mocktest List {{ timerCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <section class="app-order-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr class="">
                            <th class="sorting">Title</th>
                            <th class="sorting"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(mocktest, index) in allMOcks" :key="index">
                            <td>{{ mocktest.mock.title }}</td>

                            <td>
                                <a class="btn btn-success"  v-if="!mocktest.user" :href="`student/singele-mock?mock-id=${mocktest.mock.id}`" target="_blank">Start now</a>
                                <a class="btn btn-success disabled"  v-else >Start now</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- list and filter end -->
    </section>
</template>

<script>
import SLayout from './Layout.vue'
export default {
    layout: SLayout,
};
</script>

<script setup>
    import {computed } from 'vue'

    let props = defineProps({
        mocktests: Object,
    });

    const allMOcks = computed(()=>{

        const key = 'title';
        return [...new Map(props.mocktests.map(item =>
            [item.mock[key], item])).values()];

    })






</script>
