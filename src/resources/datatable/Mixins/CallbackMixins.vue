<script>
    export default{
        callbacks:({
            methods:{
                allCaps(content){
                    return content.toUpperCase();
                },
                mailTo(content){
                    return '<a class="mailTo" href="mailto:'+content+'">'+content+'</a>'
                },
            }
        })

    }
</script>

<style>
    .mailTo{
        color: black;
        border-bottom: 1px dashed black;
    }
</style>