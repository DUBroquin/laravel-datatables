<script>
    export default{
        callbacks:({
            methods:{
                allCaps(content){
                    return content.toUpperCase();
                },
                bolder(content){
                    return '<b>'+content+'</b>'
                },
                mailTo(content){
                    return '<a class="mailTo" href="mailto:'+content+'">'+content+'</a>'
                },
                date(content){
                    return moment(content, "YYYY-MM-DD HH:mm:ss").format('DD/MM/YYYY HH:mm:ss')
                },
                codeName(content){
                    return content.code + ' - ' + content.name
                }
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