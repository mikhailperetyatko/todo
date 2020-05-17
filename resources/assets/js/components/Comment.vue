<template>
    <div>
        <div class="card-header">
            {{ comment.owner.name }} / {{ comment.updated_at }}
        </div>
        <div class="card-body">
            {{ comment.body }}
            <div v-if="comment.files.length">
                <div class="dropdown-divider"></div>
                <u>Вложения:</u>
                <ul>
                    <li v-for="file in comment.files">
                        <a target="_blank" :href="'/home/subtasks/' + $parent.subtask_id + '/files/' + file.id">{{ file.name }}</a>
                    </li>
                </ul>
            </div>
            <br />
            <div class="container" v-if="comment.refer_comment" >
                <u>в ответ на:</u>
                <div class="card mb-2"> 
                    <comment :comment="comment.refer_comment" :user="user"></comment>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <button v-if="user == comment.owner_id" class="btn btn-sm btn-primary" @click="edit()">Изменить</button>
            <button type="button" class="btn btn-sm btn-primary" @click="reply()">Ответить</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['comment', 'user'],
        data() {
            return {
            }
        },
        mounted() {
        },
        
        methods:{
            getCommentsVue() 
            {
                return this.$root.$children[0];
            },
            edit()
            {
                let commentsVue = this.getCommentsVue();
                commentsVue.commentToChange = this.comment; 
                commentsVue.refer_comment = ''; 
                commentsVue.body = this.comment.body; 
                commentsVue.editComment();
                this.$parent.attachments = [];
                for (let i in this.comment.files) {
                    let fileToCheck = this.$parent.$refs.commentFiles.files.find(elem => elem.id == this.comment.files[i].id);
                    if (fileToCheck) {
                        fileToCheck.checked = true;
                        this.$parent.attachments.push(fileToCheck);
                    }
                }
            },
            reply()
            {
                let commentsVue = this.getCommentsVue();
                commentsVue.commentToChange = ''; 
                commentsVue.body = ''; 
                commentsVue.refer_comment = this.comment.id;
                commentsVue.replyComment();
            },
        }
    }

</script>
