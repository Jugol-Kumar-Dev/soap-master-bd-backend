<template>

    <Head title="My Courses" />
    <section class="app-order-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                        <div class="d-flex align-items-center justify-content-center">
                            <img :src="course.cover" class="img-fluid product-img" :alt="course.name">
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <h4>{{ course.name }}</h4>
                        <span class="card-text item-company">By <a href="#" class="company-name">{{ course.user.name }}</a></span>
                        <hr>
                        <span v-text="course.description"></span>
                        <hr>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion" id="accordionExample">
                                <div v-if="lessonVideos.length > 0" class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <p> videos Without Chapter</p>
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                         data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>
                                                All Videos Without Chapter ({{ lessonVideos.length }} videos)
                                            </strong>
                                            <div class="row mt-5">
                                                <div class="col-md-11 col-12 ms-md-auto md-overflow-x-scroll">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h4>All Videos In This Lessons</h4>
                                                    </div>
                                                    <table class="user-list-table table table-responsive">
                                                        <thead class="table-light">
                                                        <tr class="">
                                                            <th class="sorting">SL.</th>
                                                            <th class="sorting">Title</th>
                                                            <th class="sorting">Status</th>
                                                            <th class="sorting">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(lesson, index) in lessonVideos" :key="lesson?.id">
                                                            <td>#{{ index+1 }}</td>
                                                            <td>
                                                                <small>{{ lesson?.name }}</small>
                                                            </td>
                                                            <td>
                                                                <span v-if="lesson?.status == '1'" class="badge badge-light-primary">Active</span>
                                                                <span v-else class="badge badge-light-warning">Draft</span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group dropdown dropdown-icon-wrapper">
                                                                    <button type="button"
                                                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                    </button>

                                                                    <div class="dropdown-menu">
                                                                        <span v-if="lesson.content != null" class="dropdown-item"
                                                                              @click="showFile(lesson?.content, lesson.name)">
                                                                                        <Icon title="eye"/>
                                                                                       <span class="ms-1">File</span>
                                                                            </span>

                                                                        <span v-if="lesson.file != null" class="dropdown-item"
                                                                              @click="showPDF(lesson?.file)">
                                                                                <Icon title="eye"/>
                                                                                <span class="ms-1">File</span>
                                                                            </span>

                                                                        <span v-if="lesson.video" class="dropdown-item"
                                                                              @click="playVideo(lesson?.video, lesson.name)">
                                                                                <Icon title="play-circle"/>
                                                                               <span class="ms-1">Show</span>
                                                                            </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item" v-for="(chap,i) in course.chapters">
                                    <h2 class="accordion-header" id="headingOnes">
                                        <button class="accordion-button text-"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                :data-bs-target="`#collups${i}`"
                                                aria-expanded="true"
                                                :aria-controls="`collups${i}`">
                                            {{ chap?.chapter_title }}
                                        </button>
                                    </h2>
                                    <div :id="`collups${i}`"
                                         class="accordion-collapse collapse"
                                         :aria-labelledby="`collups${i}`"
                                         data-bs-parent="#accordionExample">
                                        <div class="accordion-body ">
                                            <strong class="">
                                                {{ chap?.chapter_title }} - {{ chap?.videos?.length }} Videos
                                            </strong>
                                            <p>{{ chap?.chapter_details }}</p>

                                            <div class="row mt-5">
                                                <div class="col-md-11 col-12 ms-md-auto md-overflow-x-scroll">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h4>All Videos In This Lessons</h4>
                                                    </div>
                                                    <table class="user-list-table table table-responsive">
                                                        <thead class="table-light">
                                                        <tr class="">
                                                            <th class="sorting">SL.</th>
                                                            <th class="sorting">Title</th>
                                                            <th class="sorting">Status</th>
                                                            <th class="sorting">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(lesson, index) in chap.videos" :key="lesson?.id">
                                                            <td>#{{ index+1 }}</td>
                                                            <td>
                                                                <small>{{ lesson?.name }}</small>
                                                            </td>
                                                            <td>
                                                                <span v-if="lesson?.status == '1'" class="badge badge-light-primary">Active</span>
                                                                <span v-else class="badge badge-light-warning">Draft</span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group dropdown dropdown-icon-wrapper">
                                                                    <button type="button"
                                                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <span v-if="lesson.content != null" class="dropdown-item"
                                                                          @click="showFile(lesson?.content, lesson.name)">
                                                                                    <Icon title="eye"/>
                                                                                   <span class="ms-1">File</span>
                                                                        </span>

                                                                        <span v-if="lesson.file != null" class="dropdown-item"
                                                                              @click="showPDF(lesson?.file)">
                                                                                <Icon title="eye"/>
                                                                                <span class="ms-1">File</span>
                                                                            </span>

                                                                        <span v-if="lesson.video" class="dropdown-item"
                                                                              @click="playVideo(lesson?.video, lesson.name)">
                                                                                <Icon title="play-circle"/>
                                                                               <span class="ms-1">Show</span>
                                                                            </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="user-list-table table table-striped">
                            <thead class="table-light">
                            <tr class="">
                                <th class="sorting" width="70%">Meeting Title</th>
                                <th class="sorting">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(meeting, index) in zooms" :key="meeting.id">
                                <td>{{ meeting.meeting_title }}</td>
                                <td>
                                    <div class="demo-inline-spacing">
                                        <a :href="meeting.join_url"
                                           target="_blank"
                                           class="btn btn-icon bg-light-primary text-black waves-effect waves-float waves-light d-flex">
<!--                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>-->
                                            Join Class
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- list and filter end -->
    </section>

    <Modal id="showVideo" size="lg" :title="vimeo_title" v-vb-is:modal>
        <div class="modal-body">
            <div class="w-100 h-100" id="attatchedVideo"></div>
        </div>
    </Modal>

    <Modal id="showFile" size="lg" title="Show Course Video" v-vb-is:modal>
        <div class="modal-body" id="showContent">
        </div>
    </Modal>

    <!-- cloud pdf viewer io pdf viewer components     -->
    <Modal id="showPDFfile" size="lg" title="Show Course Files" v-vb-is:modal>
        <div class="modal-body">
            <div id="webviewer" ref="viewer"></div>
        </div>
    </Modal>
    <!-- cloud pdf viewer io pdf viewer components     -->

</template>

<script>
import SLayout from './Layout.vue'
export default {
    layout: SLayout,
};
</script>

<script setup>
import { ref } from "vue";
import Modal from '@/components/Modal.vue';
import Icon from '@/components/Icon.vue';
import PDFViewer from "@cloudpdf/viewer";
let props = defineProps({
    course: Object,
    lessonVideos: Array|[]|null,
    url: String,
    zooms:Object,
});

    let vimeo_title = ref('')
    let file_name = ref('');
    let playVideo = (url, title) => {
        vimeo_title.value = title
        document.querySelector("#attatchedVideo").innerHTML = url;
        document.getElementById('showVideo').$vb.modal.show()
    }

    let showFile = (url, title) => {
        vimeo_title.value = title
        document.querySelector("#attatchedVideo").innerHTML = url;
        document.getElementById('showVideo').$vb.modal.show()
    }

    let showPDF = (pdfId) => {
        document.getElementById('webviewer').innerHTML=""
        PDFViewer(
            {
                documentId: pdfId,
                darkMode: true,
            },
            document.getElementById('webviewer')
        );
        vimeo_title.value = "show title";
        document.getElementById('showPDFfile').$vb.modal.show()
    }



</script>

<style scoped>
    #webviewer {
        height: 400px;
        width: 100%;
    }
</style>
