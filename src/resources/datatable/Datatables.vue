<script>
    // Import basics
    import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
    import VueEvents from 'vue-events'

    // Import custom components
    import FilterBar from './FilterBar.vue'
    import Pagination from './Pagination.vue'

    // Import mixins
    import SlotsMixins from './Mixins/SlotsMixins.vue';
    import CallbacksMixins from './Mixins/CallbackMixins.vue';

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
            Pagination
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

            // Export
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
                moreParams: {},
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
                        on: {},
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
                this.$refs.vuetable.changePage(page)
            },

            /*------------------------------------------------
             *   Internal
             */
            reload(id){
                Vue.nextTick(() => this.$refs[id].refresh())
            },

        }
    }
</script>

<style>
    .mailTo{
        color: black;
        border-bottom: 1px dashed black;
    }
</style>