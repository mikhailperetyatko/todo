<template>
    <div id="report_toasts">
        <div class="container" v-for="toast in toasts">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" :id="toast.id">
              <div class="toast-header">
                <strong class="mr-auto">Сгенерирован новый отчет</strong>
                <small>{{ toast.time }}</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="toast-body">
                <ul class="container" v-for="table in toast.tables">
                    <li>{{ table.name }} - {{ table.data }}</li>
                </ul>
                <p>Файл отчета доступен на скачивание до {{ toast.timeBeforeDelete }}</p>
                <a :href="toast.url">Скачать файл</a>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    var toastsReport = [];
    export default {
        props: ['user'],
        data() {
            return {
                toasts:new Array()
            }
        },
        mounted() {
            Echo
                .private('ReportCompleted.' + this.user)
                .listen('GenerateReport', (data) => {
                    var date = new Date();
                    toastsReport.unshift({
                        tables: data.tables,
                        url: '/admin/reports/' + data.attach,
                        timeBeforeDelete: data.timeBeforeDelete,
                        id: 'toast' + '_' + date.getTime(),
                        time: date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()
                    });
                    this.toasts = toastsReport;
                    console.log(this.toasts);
                }
            );
            $('#report_toasts').bind("DOMSubtreeModified",function(){
                $('.toast').toast('show');
                $('.toast').on('hidden.bs.toast', function () {
                    for(var toast in toastsReport){
                        if (toastsReport[toast].id == $(this).attr('id')) {
                            toastsReport.splice(toast, 1);
                        }
                    }
                });
            });
        }
    }

</script>
