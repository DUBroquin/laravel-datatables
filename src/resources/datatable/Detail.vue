<template>
    <div @click="onClick">
        <div class="inline field">
            <label class="label">{{trans('comments.detail.label')}}</label>
            <span>{{rowData.content}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                required:true
            }
        },
        methods: {
            onClick (event) {}
        },
    }
</script>