<template>
    <div class="container">
        <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="errors">
            <strong>Внимание! </strong>Произошла непредвиденная ошибка: {{ errors.message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="form-group">
            <label for="formControlBody">Ваш комментарий</label>
            <textarea :class="'form-control' + (errors.errors && errors.errors.body ? ' is-invalid' : '')" id="formControlBody" ref="comment_body" rows="3" v-model="body"></textarea>
            <small id="bodyHelp" class="form-text text-muted">Не менее 5 символов. Можно добавить еще {{65535 - body.length}} симв.</small>
            <div class="invalid-feedback" v-if="errors.errors && errors.errors.body">
                {{ errors.errors.body.join('; ') }}
            </div>
        </div>
        <div class="alert alert-info alert-dismissible fade show" role="alert" v-if="refer_comment != ''">
            Ответ на комментарий "{{ comments.data.filter(element => element.id == refer_comment)[0].body }}"
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="refer_comment=''">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div v-if="attachments.length">
            Вложения:
            <ul>
                <li v-for="attachment, pos in attachments">
                    <a target="_blank" :href="'/home/subtasks/' + subtask_id + '/files/' + attachment.id">{{ attachment.name }}</a><span class="badge badge-danger m-1" @click="unCheckedFile(pos)">X</span>
                </li>
            </ul>
        </div>
        <button type="button" class="btn btn-primary btn-sm" @click="attachComment()">Отправить</button>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#chooseFiles">
            Выбрать файл
        </button>
        
        <div class="dropdown-divider"></div>
        <div class="btn-group" role="group" v-if="comments.next_page_url || comments.prev_page_url">
            <button type="button" class="btn btn-secondary btn-sm" @click="currentPage--;loadPage()"><</button>
            <button type="button" class="btn btn-secondary btn-sm">Страница #{{ comments.current_page }} ({{ comments.from }} - {{ comments.to }})</button>
            <button type="button" class="btn btn-secondary btn-sm" @click="currentPage++;loadPage()">></button>
        </div>
        <div class="card mb-2" v-for="comment in comments.data">
            <comment :comment="comment" :user="user"></comment>
        </div>
        <div class="btn-group" role="group" v-if="(comments.next_page_url || comments.prev_page_url)">
            <button type="button" class="btn btn-secondary btn-sm" @click="currentPage--;loadPage()"><</button>
            <button type="button" class="btn btn-secondary btn-sm">Страница #{{ currentPage }}</button>
            <button type="button" class="btn btn-secondary btn-sm" @click="currentPage++;loadPage()">></button>
        </div>
        <p v-if="! comments.data">
            Пока комментариев нет
        </p>
        
        <!-- Modal -->
        <div class="modal fade" id="chooseFiles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Выбор файла</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <file :subtask='subtask_id' :checkbox="true" ref="commentFiles">Подождите...</file>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="attachFiles()">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['subtask_id', 'user'],
        data() {
            return {
                'comments': {},
                'errors': '',
                'body': '',
                'refer_comment': '',
                'currentPage': 1,
                'commentToChange': '',
                'valuesToAxios': [],
                'urlToAxios': '',
                'attachments': [],
            }
        },
        mounted() {
            this.loadPage();
        },
        
        methods:{
            send()
            {
                this.errors = '';
                this.attachments = [];
                axios
                    .post(this.urlToAxios, this.valuesToAxios.join('&'))
                    .then(response => {
                        this.comments = [];
                        this.refer_comment = '';
                        this.body = '';
                        this.commentToChange = '';
                        this.valuesToAxios = [];
                        this.urlToAxios = '';
                        this.loadPage();
                    })
                    .catch(error => {
                        this.errors = error.response.data;
                    })
                ;
                for (let i in this.$refs.commentFiles.files) {
                    this.$refs.commentFiles.files[i].checked = false;
                }
            },
            attachComment()
            {
                if (! this.valuesToAxios.length)
                {
                    this.currentPage = 1;
                    this.valuesToAxios.push('_method=POST');
                    this.urlToAxios = '/home/subtasks/' + this.subtask_id + '/comments';
                }
                this.valuesToAxios.push('body=' + this.body);
                for (let i in this.attachments) {
                    this.valuesToAxios.push('files[]=' + this.attachments[i].id);
                }
                
                this.send();
            },
            editComment()
            {
                this.valuesToAxios = [];
                this.valuesToAxios.push('_method=PATCH');
                this.urlToAxios = '/home/subtasks/' + this.subtask_id + '/comments/' + this.commentToChange.id;
                this.$refs['comment_body'].focus();
            },
            replyComment()
            {
                this.valuesToAxios = [];
                this.valuesToAxios.push('_method=POST');
                this.urlToAxios = '/home/subtasks/' + this.subtask_id + '/comments';
                this.valuesToAxios.push('refer_comment=' + this.refer_comment);
                this.$refs['comment_body'].focus();
            },
            loadPage()
            {
                if (this.comments) {
                    if (! this.comments.next_page_url) this.currentPage--;
                    if (this.currentPage < 1) this.currentPage = 1;
                }
                this.refer_comment = '';
                if (! this.comments || this.comments.current_page != this.currentPage)
                    axios
                        .get('/home/subtasks/' + this.subtask_id + '/comments?page=' + this.currentPage)
                        .then(response => {
                            this.comments = response.data.comments;
                            console.log(this.comments);
                        })
                        .catch(error => this.errors = error.response.data)
                    ;
            },
            attachFiles()
            {
                this.attachments = [];
                let files = this.$refs.commentFiles.files;
                for (let i in files) {
                    if (files[i].checked) {
                        this.attachments.push(files[i]);
                    }
                }
            },
            unCheckedFile(key)
            {
                this.attachments[key].checked = false;
                this.attachments.splice(key, 1);
            }
        }
    }

</script>
