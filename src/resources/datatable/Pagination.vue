<template>
    <div class="row" v-show="showPagination">
        <div class="col-md-6">
            <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>
        </div>
        <div class="col-md-6">
            <vuetable-pagination
                    ref="pagination"
                    :css="css"
                    @vuetable-pagination:change-page="changePage">
            </vuetable-pagination>
        </div>
    </div>
</template>

<script>
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

    export default{
        components: {
            VuetablePagination,
            VuetablePaginationInfo
        },
        props:{
            showPagination:{
                type:Boolean,
                required:true,
            },
            css:{
                type:Object,
                required:true
            }
        },
        methods:{
            changePage(page){
                this.$parent.onChangePage(page)
            },
            setDatas(paginationData){
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            }
        }
    }
</script>