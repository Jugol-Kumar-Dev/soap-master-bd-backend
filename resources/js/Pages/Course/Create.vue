<template>

    <Layout>
    <Head title="Create Course" />
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="float-start mb-0">নতুন কোর্স তৈরি করুন</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="course-create-form">
        <div class="card">
            <div class="card-body">
                <form class="form form-vertical" @submit.prevent="createNewCourse">
                    <div class="row">
                        <div class="col-12">
                            <Text v-model="createForm.title" :error="props.errors.name" label="কোর্স টাইটেল" placeholder="কোর্স টাইটেল" />
                        </div>
                        <div class="col-12">
                            <Text v-model="createForm.name" :error="props.errors.name" label="কোর্সের নাম" placeholder="কোর্সের নাম" />
                        </div>
                        <div class="col-6">
                            <Image v-model="createForm.cover" :error="props.errors.cover" label="কভার ফোটো"/>
                        </div>
                        <div class="col-6">
                            <Text v-model="createForm.video" :error="props.errors.video" label="ইন্ট্রো ভিডিও (শুধু লিংক)" placeholder="ইন্ট্রো ভিডিও" />
                        </div>
                        <div class="col-6">
                            <Textarea v-model="createForm.description" :error="props.errors.description" label="উদ্দেশ্যসমূহ" />
                        </div>
                        <div class="col-6">
                            <Textarea v-model="createForm.achievement" :error="props.errors.achievement" label="লক্ষ্যসমূহ" />
                        </div>
                        <div class="col-12">
                            <Textarea v-model="createForm.finishing_achievement" :error="props.errors.finishing_achievement" label="কোর্স শেষে" />
                        </div>
                        <div class="col-4">
                            <Text v-model="createForm.old_price" type="number" label="পূর্বের মূল্য (যদি থাকে)" placeholder="১০০.০০" prefix="টাকা" />
                        </div>

                        <div class="col-4">
                            <Text v-model="createForm.price" type="number" label="বর্তমান মূল্য" placeholder="১০০.০০" prefix="টাকা" />
                        </div>
                        <div class="col-4">
                            <Text v-model="createForm.course_time" type="text" label="সময়সীমা" placeholder="কোর্সের সময়সীমা"/>
                        </div>

                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                সেভ করুন
                            </button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect">
                                কেটে দিন
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    </Layout>
</template>

<script setup>
import Text from '@/Components/form/Text.vue';
import Image from '@/Components/form/Image.vue';
import Textarea from '@/Components/form/Textarea.vue';
import TextEditor from '@/Components/TextEditor.vue';
import { computed } from 'vue';
import Datepicker from 'vue3-datepicker'
import Radio from '@/Components/form/Radio.vue';
import Video from '@/Components/form/Video.vue';
import Layout from "@/Shared/Layout.vue";
import {useForm} from "@inertiajs/vue3";
let props = defineProps({
    categories: Object,
    url: String,
    errors:Object,
})

let createForm = useForm({
    title: null,
    name: null,
    cover: null,
    video: null,
    description: null,
    achievement: null,
    finishing_achievement: null,
    old_price: null,
    price: null,
    course_time: null
})

let createNewCourse = () => {
    createForm.post(props.url, {
        onSuccess: () => {
            createForm.reset()
        }
    })
}


</script>
