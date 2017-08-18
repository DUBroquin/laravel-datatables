<script>
    // Import basics
    import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
    import VueEvents from 'vue-events'
    import TableExport from 'tableexport'
    import PrintHtmlElement from 'print-html-element'
    import JsPDF from 'jspdf'
    import autoTable from 'jspdf-autotable'

    // Import custom components
    import FilterBar from './FilterBar.vue'
    import Pagination from './Pagination.vue'
    import Detail from './Detail.vue'

    // Import mixins
    import SlotsMixins from './Mixins/SlotsMixins.vue';
    import CallbacksMixins from './Mixins/CallbackMixins.vue';

    // Tell Vue what it must use
    Vue.component('detail', Detail)

    export default{
        mixins:[CallbacksMixins.callbacks],
        render(h) {
            return h(
                'div',
                {class: {ui: false, container: false}}, // Remove default style
                [
                    this.renderFilterBar(h),
                    this.renderVuetable(h),
                    this.renderPagination(h),
                ]
            )
        },
        mounted(){
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
        },
        components: {
            Vuetable,
            FilterBar,
            Pagination,
            Detail
        },
        props: {
            // Mandatories
            id: {
                required: true,
            },
            api: {
                required: true,
            },
            columns: {
                required: true,
            },

            // Query
            moreParams:{
                type:Object,
                default:function(){
                    return {}
                }
            },

            // Export
            exportReject:{
                type: Array,
                default:function(){
                    return ['actions'];
                }
            },
            exportFormats: {
                type: Array
            },

            // Display components
            showFilter: {
                type: Boolean,
                default: function () {
                    return true;
                }
            },
            showActions: {
                type: Boolean,
                default: function () {
                    return true;
                }
            },
            showPagination: {
                type: Boolean,
                default: function () {
                    return true;
                }
            }

        },
        data(){
            return {
                vuetable: {
                    tableClass: 'table table-striped table-bordered table-hover',
                    loadingClass: 'loading',
                    ascendingIcon: 'fa fa-sort-asc',
                    descendingIcon: 'fa fa-sort-desc',
                    handleIcon: 'fa fa-bart',
                    pagination: {
                        infoClass: 'pull-left',
                        wrapperClass: 'vuetable-pagination pull-right',
                        activeClass: 'btn btn-primary',
                        disabledClass: 'disabled',
                        pageClass: 'btn btn-default',
                        linkClass: 'btn btn-default',
                        icons: {
                            first: 'fa fa-backward',
                            prev: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            last: 'fa fa-forward',
                        },
                    },
                },
                filterText: ''
            }
        },
        methods: {
            /*------------------------------------------------
             *   Render components
             */
            renderFilterBar(h) {
                return h(
                    'filter-bar',
                    {
                        props: {
                            id: this.id,
                            showFilter: this.showFilter,
                            showActions: this.showActions,
                            exportFormats: this.exportFormats
                        },
                    }
                )
            },
            renderVuetable(h) {
                return h(
                    'vuetable',
                    {
                        ref: this.id,
                        props: {
                            id: this.id,
                            name: this.id,
                            apiUrl: this.api,
                            fields: this.columns,
                            css: this.vuetable,
                            paginationPath: "",
                            perPage: 10,
                            appendParams: this.moreParams,
                        },
                        on: {
                            'vuetable:pagination-data': this.onPaginationData,

                        },
                        scopedSlots: this.$vnode.data.scopedSlots
                    }
                )
            },
            renderPagination(h) {
                return h(
                    'pagination',
                    {
                        ref: 'pagination',
                        props: {
                            showPagination: this.showPagination,
                            css: this.vuetable.pagination,
                        },
                        on: {
                            //'vuetable-pagination:change-page': this.$refs.pagination.onChangePage
                        },
                        scopedSlots: this.$vnode.data.scopedSlots
                    }
                )
            },

            /*------------------------------------------------
             *   Filter
             */
            onFilterSet (filterText) {
                this.moreParams = {
                    'filter': filterText
                }
                Vue.nextTick(() => this.$refs[this.id].refresh())
            },

            /*------------------------------------------------
             *   Pagination
             */
            onPaginationData (paginationData) {
                this.$refs.pagination.setDatas(paginationData)
            },
            onChangePage (page) {
                this.$refs[this.id].changePage(page)
            },

            /*------------------------------------------------
             *   Detail
             */
            onCellClicked (data, field, event) {
                this.clickedRowData = data
                this.clickedRowIndex = data.id
                this.$refs[this.id].toggleDetailRow(data.id)
            },

            /*------------------------------------------------
             *   Print
             */
            printTable(){
                // Stock context
                let self = this;

                let PHE = PrintHtmlElement;
                console.log(document.getElementById(self.id));
                PHE.printElement(document.getElementById(self.id))
            },

            /*------------------------------------------------
             *   Export
             */
            exportTable(format){
                console.log(format)

                // Stock context
                let self = this;

                // Get infos
                let table = this.exportHelper(format);

                if (format !== 'pdf') {
                    // Init Table export
                    let prepareExport = new TableExport(document.getElementById(self.id), {
                        formats: [format],
                        exportButtons: false,
                        bootstrap: true,
                        ignoreCols:table.col,
                        filename: table.file,
                        trimWhitespace: true
                    });

                    // Add datas
                    let expDatas = prepareExport.getExportData()[this.id][format];

                    // Export !
                    prepareExport.export2file(expDatas.data, expDatas.mimeType, expDatas.filename, expDatas.fileExtension)
                } else {

                    // Instanciatate JsPDF
                    let preparePDF = new JsPDF();

                    // Use autotable
                    preparePDF.autoTable(table.col, table.data);
                    preparePDF.save(table.file)
                }
            },

            exportHelper(format){
                // Stock context
                let self = this;

                // Prepare columns for exportation
                let cols = [];

                // Prepare rejected columns name
                let formatedReject = [];

                console.log(this.exportReject);

                this.exportReject.forEach(function(reject){
                    formatedReject.push(_.startCase(_.toLower(reject)));
                });

                this.columns.forEach(function (col, key) {
                    let isReject = formatedReject.indexOf(col.title);
                    if (format !== 'pdf') {
                        if(isReject > '-1') {
                            cols.push(key)
                        }
                    }else {
                        if(isReject == '-1') {
                            cols.push({title: col.title, dataKey: col.name})
                        }
                    }
                });

                // Format table
                let datas = $.map(self.$refs[self.id].tableData, function(value, index) {
                    return [value];
                });

                // Create file name
                let name = self.id .toUpperCase() + moment().format("D-MM-Y-h-m-s")

                return {data: datas, col: cols, file: name}
            },


            /*------------------------------------------------
             *   Internal
             */
            reload(id){
                Vue.nextTick(() => this.$refs[id].refresh())
            },
        },
    }
</script>