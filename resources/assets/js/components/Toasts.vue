<template>
    <div id="report_toasts">
        <div class="toast vw-100" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" v-for="toast, key in toasts">
          <div class="toast-header">
            <strong class="mr-auto">Внимание!</strong>
            <small>в {{ toast.time }}</small>
            <button type="button" class="ml-2 mb-1 close" :data-dismiss="toast.id" aria-label="Close" @click.prevent="delNotification(key)">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="toast-body">
            {{ toast.message }}
            <a :href="toast.link">Перейти</a>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user', 'data'],
        data() {
            return {
                toasts:[]
            }
        },
        mounted() {
            this.data = this.data.reverse()
            for (let i in this.data) {
                this.toasts.unshift(this.getToast(this.data[i]));
            }
            
            Echo
                .private('App.User.' + this.user)
                .notification((notification) => {
                    this.toasts.unshift(this.getToast(notification));
                })
            ;
            $('#report_toasts').bind("DOMSubtreeModified",function(){
                $('.toast').toast('show');
            });
        },
        
        methods:{
            getToast(notification)
            {
                var date = notification.updated_at ? notification.updated_at.split(' ')[1] : new Date().toLocaleString('ru', {hour: 'numeric', minute: 'numeric', second: 'numeric'});
                var data = notification.data ? notification.data : notification;
                return {
                    message: data.message,
                    link: data.link,
                    id: notification.id,
                    time: date,
                };
            },
            delNotification(key)
            {
                axios
                    .delete('/home/notifications/' + this.toasts[key].id)
                    .then(response => {
                        this.toasts.splice(key, 1);
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    })
                ;
            },
        }
    }

</script>
