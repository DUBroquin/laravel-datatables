<template>
    <div class="row">
        <div class="filter-bar pull-right">
            <form class="form-inline">
                <div class="form-group">
                    <label>{{ trans('module.datatable.search')}}</label>
                    <input type="text" v-model="filterText" class="form-control no-round" placeholder="">
                    <button class="btn btn-primary" @click.prevent="doFilter"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="filter-bar pull-right" v-show="showActions">
            <el-popover
                    ref="format"
                    placement="bottom"
                    width="160">
                <h5 class="exportPopover caption-subject font-dark bold"> {{ trans('template.datatable.export.title')}}</h5>
                <div style="text-align: center;">
                    <el-button size="mini" type="text" v-for="format in exportFormats" @click="doExport(format)" :key="format">{{upperFormat(format)}}</el-button>
                </div>
            </el-popover>
            <el-button type="text" @click="doPrint"><i class="fa fa-print" aria-hidden="true"></i></el-button>
            <el-button type="text" v-popover:format><i class="fa fa-file-o" aria-hidden="true"></i></el-button>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            id:{
              required:true
            },
            showActions:{
                type:Boolean
            },
            exportFormats:{
                type: Array,
                default: function(){
                    return ['xlsx', 'pdf']
                }
            }
        },
        data () {
            return {
                filterText: ''
            }
        },
        methods: {
            doFilter () {
                this.$events.fire('filter-set', this.filterText, this.id)
            },
            resetFilter () {
                this.filterText = ''
                this.$events.fire('filter-reset')
            },
            doPrint(){
                this.$parent.printTable();
            },
            doExport(format){
                this.$parent.exportTable(format);
            },
            upperFormat(format){
                return _.toUpper(format)
            }
        }
    }
</script>
<style>
    .filter-bar {
        padding: 10px 15px 10px 0px;
    }
    .exportPopover{
        margin:0;
        margin-bottom:1em;
        text-align: center;
    }
</style>
