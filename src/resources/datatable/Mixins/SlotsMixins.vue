<script>
    export default{
        actionsSlot: ({
            methods: {
                onAction (id, action, data, index) {
                    this.openModal(id, action, data, index)
                },
                onDetail(data, id){
                    this.$refs[id].$refs[id].toggleDetailRow(data.id)
                }
            }
        })
    }
</script>