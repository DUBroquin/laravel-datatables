<script>
    export default{
        actionsSlot: ({
            methods: {
                onAction (id, action, data, index) {
                    this.openModal(id, action, data, index)
                },
            }
        })
    }
</script>