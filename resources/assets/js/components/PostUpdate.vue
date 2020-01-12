<template>
    <div id="container_toasts">
        <div class="container" v-for="toast in toasts">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" :id="toast.id">
              <div class="toast-header">
                <strong class="mr-auto">Изменения в статье #{{ toast.postId }}</strong>
                <small>{{ toast.time }}</small>
                <button type="button" class="ml-2 mb-1 close" :data-dismiss="toast.id" aria-label="Close" @click.prevent="del(toast.id)">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="toast-body">
                <p>
                    В статье "{{ toast.title }}" изменились следующие поля: {{ toast.modifedFields.join(', ') }}
                </p>
                <a :href="toast.url">Перейти к статье</a>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                toasts:[]
            }
        },
        mounted() {
            Echo
                .private('PostUpdate')
                .listen('PostUpdate', (data) => {
                    var date = new Date();
                    this.toasts.unshift({
                        postId: data.postId,
                        modifedFields: data.modifedFields,
                        url: '/posts/' + data.slug,
                        title: data.title,
                        id: 'toast' + data.postId + '_' + date.getTime(),
                        time: date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()
                    });
                    console.log(this.toasts);
                }
            );
            $('#container_toasts').bind("DOMSubtreeModified",function(){
                $('.toast').toast('show');
            });
        },
        
        methods:{
            del(id) {
                for(var key in this.toasts) {
                    if (this.toasts[key].id == id) this.toasts.splice(key, 1);
                }    
            }
        }
    }

</script>
