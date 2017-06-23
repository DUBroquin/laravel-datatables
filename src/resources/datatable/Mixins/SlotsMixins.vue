<script>
    export default{
        actionsSlot: ({
            methods: {
                onAction (id, action, data, index) {
                    this.openModal(id, action, data)
                },

                reload(id){
                    Vue.nextTick(() => this.$refs[id].refresh())
                },
            }
        })
    }

</script>