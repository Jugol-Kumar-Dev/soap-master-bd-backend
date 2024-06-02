<script setup>
import { ref } from 'vue'
import SummernoteEditor from 'vue3-summernote-editor';
const myValue = ref('');


const toolbarOptions = [
    ['heading', ['paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']],
];



const summernoteChange = (newValue) => {
    console.log('summernoteChange', newValue);
};

const summernoteImageLinkInsert = (...args) => {
    console.log('summernoteImageLinkInsert()', args);
};
</script>

<template>
    <SummernoteEditor
        v-model="myValue"
        @update:modelValue="summernoteChange($event)"
        @summernoteImageLinkInsert="summernoteImageLinkInsert"
        :toolbar="toolbarOptions"
    />
</template>
<style lang="css">

// sumilton editor toolbar
.note-toolbar .note-style{
    display:none !important;
}
.note-toolbar .note-font{
    display:none !important;
}
.note-toolbar .note-fontname{
    display:none !important;
}
.note-toolbar .note-color-all{
    display:none !important;
}

.note-toolbar .note-view{
    display:none !important;
}
.note-toolbar .note-table{
    display:none !important;
}
.note-toolbar .note-para{
    display:none !important;
}
.note-editor .card-header{
    display: none !important;
}

</style>
