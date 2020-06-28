<template>
    <div class="mt-2 mb-2">
        <button type="button" class="btn btn-secondary " data-toggle="modal" data-target="#staticBackdrop">
            Тэги к задаче ({{ subtaskTags.length }})
        </button>
        
        <!-- Scrollable modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Выбор тэгов к задаче</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-for="error in axiosErrors">
                            <div class="alert alert-danger" role="alert" v-if="error">
                                {{ error.data.errors ? error.data.errors.name.join(', ') : error.data.message }}                                
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div v-for="success in axiosSuccesses">
                            <div class="alert alert-success" role="alert">
                                Операция завершена удачно
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="filteredTags">Фильтр тэгов</label>
                            <input type="text" class="form-control" id="filteredTags" aria-describedby="Фильтр тэгов" v-model="filter" @change="getFilteredTags()">
                        </div>
                        <div class="mb-2" v-for="tag, key in getFilteredTags()">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox" aria-label="Отметьте тэг" :name="'tags[' + key + ']'" v-model="subtaskTags" :value="tag.id">
                                    </div>
                                </div>
                                <input type="text" class="form-control" aria-label="Имя тэга" placeholder="Имя тэга" v-model="tag.name" required>
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" @click.prevent="saveTag(tag)" :disable="buttonsInProgress.indexOf('edit' + tag.id) > -1">
                                        Сохранить
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="buttonsInProgress.indexOf('edit' + tag.id) > -1"></span>
                                    </button>
                                    <button class="btn btn-danger tagsHolder" @click.prevent="deleteTag(tag)" :disable="buttonsInProgress.indexOf('delete' + tag.id) > -1">
                                        X
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="buttonsInProgress.indexOf('delete' + tag.id) > -1"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <a href="#" role="button" class="btn btn-secondary btn-sm" @click="filteredTags.push({id: ('new_' + counter), name: ''}); subtaskTags.push('new_' + counter++);">Добавить новй тэг</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['tags', 'subtask_tags'],
        data() {
            return {
                subtaskTags: [],
                tagsName: [],
                counter: 0,
                filteredTags: [],
                filter: '',
                axiosErrors: [],
                axiosSuccesses: [],
                buttonsInProgress: [],
            }
        },
        mounted() {
            this.filteredTags = this.tags;
            this.subtaskTags = this.subtask_tags.map(function(tag){
                return tag.id;
            });
        },
        
        methods:{
            getFilteredTags()
            {
                return this.filteredTags.filter(tag => tag.name.toLowerCase().indexOf(this.filter.toLowerCase()) > -1);
            },
            
            axiosPost(tag, url, callable, method = 'POST')
            {
                let button = (method == 'POST' || method == 'PATCH' ? 'edit' : 'delete') + tag.id;
                this.buttonsInProgress.push(button);
                
                axios.post(url, {   
                    name: tag.name,
                    _method: method,
                })
                .then(response => {callable(response, tag); this.axiosSuccesses.push({}); this.buttonsInProgress.splice(this.buttonsInProgress.indexOf(button))})
                .catch(e => {this.axiosErrors.push(e.response ? e.response : e.request); this.buttonsInProgress.splice(this.buttonsInProgress.indexOf(button))})
            },
            
            deleteTag(tag)
            {
                if (typeof tag.id == 'string') this.filteredTags.splice(this.filteredTags.indexOf(tag), 1);
                else if (confirm('Вы уверены?')) {
                    this.axiosPost(tag, '/home/tags/' + tag.id, this.delTag, 'DELETE');
                }
            },
            
            saveTag(tag)
            {
                if (typeof tag.id == 'string') {
                    this.axiosPost(tag, '/home/tags', this.updateTag);
                } else {
                    this.axiosPost(tag, '/home/tags/' + tag.id, this.updateTag, 'PATCH');
                }
            },
            
            delTag(response, tag)
            {
                if (response.data.result) this.filteredTags.splice(this.filteredTags.indexOf(tag), 1);
            },
            
            updateTag(response, tag)
            {
                this.filteredTags[this.filteredTags.indexOf(tag)] = response.data;
                this.subtaskTags.push(response.data.id);
            },
        }
    }

</script>
