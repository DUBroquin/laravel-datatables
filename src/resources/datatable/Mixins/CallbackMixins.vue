<script>
    export default{
        callbacks:({
            methods:{
                allCaps(content){
                    return content.toUpperCase();
                },
                bolder(content){
                    return '<b>'+content+'</b>'
                }
                mailTo(content){
                    return '<a class="mailTo" href="mailto:'+content+'">'+content+'</a>'
                },
                date(content){
                    return moment(content, "YYYY-MM-DD HH:MM:SS").format('DD/MM/YYYY HH:MM:SS')
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