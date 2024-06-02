<template>

    <Head title="Mocktest Enroll" />
    <section class="app-dashboard">
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">{{ mocktest.name }}</h4>
                <h4 class="card-title">Duration: {{ mocktest.duration }} minutes</h4>
                <h4 class="card-title">Total Question: {{ mocktest.total_q }}</h4>

                <MinutesCounter :mainTime="mocktest.duration"/>
            </div>
        </div>
    </section>

    <section>
        <div class="row">
            <div class="col-12" v-for="(question, index) in ansForm.questions" :key="index">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Question-{{ index+1 }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text" v-html="question.title">
                        </p>
                        <ul class="list-group">
                            <li class="list-group-item">(A): <span v-html="question.a"></span></li>
                            <li class="list-group-item">(B): <span v-html="question.b"></span></li>
                            <li class="list-group-item">(C): <span v-html="question.c"></span></li>
                            <li class="list-group-item">(D): <span v-html="question.d"></span></li>
                            <li class="list-group-item">(E): <span v-html="question.e"></span></li>
                        </ul>
                        <div class="d-flex">
                            <div class="mt-1">Answer: </div>
                            <div class="demo-inline-spacing px-3">
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                        :id="`a${question.id}`" value="a">
                                    <label class="form-check-label" :for="`a${question.id}`">Option A</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                        :id="`b${question.id}`" value="b">
                                    <label class="form-check-label" :for="`b${question.id}`">Option B</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                        :id="`c${question.id}`" value="c">
                                    <label class="form-check-label" :for="`c${question.id}`">Option C</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                        :id="`d${question.id}`" value="d">
                                    <label class="form-check-label" :for="`d${question.id}`">Option D</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input v-model="question.ans" class="form-check-input" type="radio" :name="`answer${question.id}`"
                                        :id="`e${question.id}`" value="e">
                                    <label class="form-check-label" :for="`e${question.id}`">Option E</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <button class="btn btn-danger" @click="submitAnsForm">Submit Answer</button>
        </div>
    </section>


</template>

<script>
import SLayout from './Layout.vue'
import Icon from '@/components/Icon.vue'

export default {
    components: {
        Icon
    },
    layout: SLayout,
};
</script>

<script setup>
import { ref, onMounted } from 'vue'
import MinutesCounter from '@/components/MinutesCounter.vue'
import {useForm} from "@inertiajs/vue3";
let props = defineProps({
    questions: Object,
    mocktest: Object,
    url: String,
})



let ansForm = useForm({
    questions: props.questions,
    mocktest_id: props.mocktest.id,
})

const duration = ref(props.mocktest.duration)

onMounted (() => {
    setTimeout(() => {
        ansForm.post(props.url, {
            onSuccess: () => console.log('Saved')
        })

    }, (duration.value * 60 * 1000 ));
})

let submitAnsForm = () => {
    ansForm.post(props.url, {
        onSuccess: () => console.log('Saved')
    });
}




//
// watch: {
//     timerCount: {
//         handler(value) {
//
//             if (value > 0) {
//                 setTimeout(() => {
//                     this.timerCount--;
//                 }, 1000);
//             }
//
//         },
//         immediate: true // This ensures the watcher is triggered upon creation
//     }
//
// }
//



</script>
