<template>
    <div class="container">
        <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="errors.length">
            <strong>Внимание! </strong>Произошла непредвиденная ошибка: {{ errors.message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="form-group" v-if="files != []">
            <input type="text" v-model="search" class="form-control" placeholder="Фильтр">
        </div>
        <div class="container" v-for="fileHandler, key in filtered()" v-if="files != []">
            <div class="custom-control custom-switch d-inline" v-if="checkbox">
                <input type="checkbox" class="custom-control-input" :id="'customSwitch' + key + _uid" v-model="fileHandler.checked">
                <label class="custom-control-label" :for="'customSwitch' + key + _uid"></label>
                <input type="hidden" v-if="fileHandler.checked && fileHandler.id" name="files[]" :value="fileHandler.id">
            </div>
            <a data-toggle="collapse" :href="'#collapseFileHandler' + key + _uid" aria-expanded="false" :aria-controls="'collapseFileHandler' + key + _uid">
                {{ fileHandler.name ? fileHandler.name : 'Новый файл' }}
            </a>
            <div class="collapse mb-2" :id="'collapseFileHandler' + key + _uid">
                <div class="card card-body">
                    <form @submit.prevent="send(key)">
                        <a class="btn btn-secondary btn-sm" data-toggle="collapse" :href="'#collapseFile' + key + _uid" role="button" aria-expanded="false" :aria-controls="'collapseFile' + key + _uid">
                            Дополнительные данные
                        </a>
                        <div class="collapse" :id="'collapseFile' + key + _uid">
                            <div class="card card-body">
                                <div class="form-group">
                                    <label :for="'inputDescription' + key + _uid">Описание файла</label>
                                    <textarea :class="'form-control' + (errors.errors && errors.errors.description ? ' is-invalid' : '')" :id="'inputDescription' + key + _uid" rows="3" v-model="fileHandler.description"></textarea>
                                    <div class="invalid-feedback" v-if="errors.errors && errors.errors.description">
                                        {{ errors.errors.description.join('; ') }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label :for="'inputName' + key + _uid">Название файла</label>
                                    <input type="text" :class="'form-control' + (errors.errors && errors.errors.name ? ' is-invalid' : '')" :id="'inputName' + key + _uid" placeholder="Введите название" v-model="fileHandler.name">
                                    <small class="form-text text-muted">
                                        В случае незаполнения при сохранении файла будет использовано его оригинальное имя
                                    </small>
                                    <div class="invalid-feedback" v-if="errors.errors && errors.errors.name">
                                        {{ errors.errors.name.join('; ') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label :for="'selectStorage' + key + _uid">Выберите хранилище</label>
                            <select :class="'form-control-file' + (errors.errors && errors.errors.storage ? ' is-invalid' : '')" :id="'selectStorage' + key + _uid" v-model="fileHandler.storage_id" required>
                              <option v-for="storage in storages" :value="storage.id">{{ storage.name }}</option>
                            </select>
                            <div class="invalid-feedback" v-if="errors.errors && errors.errors.storage">
                                {{ errors.errors.storage.join('; ') }}
                            </div>
                        </div>
                        <input type="hidden" name="id" v-model="fileHandler.id">
                        <div class="form-group mt-2">
                            <input type="file" ref="file" :class="'form-control-file' + (errors.errors && errors.errors.file ? ' is-invalid' : '')" :id="'uploadFile' + key + _uid" :required="! fileHandler.id">
                            <div class="invalid-feedback" v-if="errors.errors && errors.errors.file">
                                {{ errors.errors.file.join('; ') }}
                            </div>
                        </div>
                        <input class="btn btn-primary btn-sm" type="submit" :value="fileHandler.id ? 'Изменить' : 'Выгрузить'">
                        <a target="_blank" :href="'/home/subtasks/' + subtask + '/files/' + fileHandler.id + '?v=' + getDate()" class="btn btn-success btn-sm" role="button" v-if="fileHandler.id">Скачать</a>
                        <a href="#" class="btn btn-danger btn-sm" role="button" v-if="fileHandler.id" @click="remove(key)">Удалить</a>
                        <div class="progress mt-2 mb-2" v-if="fileHandler.uploadProgress">
                            <div class="progress-bar" role="progressbar" :style="'width:' + fileHandler.uploadProgress + '%;'" :aria-valuenow="fileHandler.uploadProgress" aria-valuemin="0" aria-valuemax="100">{{ fileHandler.uploadProgress }}%</div>
                        </div>
                        <div class="alert alert-success alert-dismissible fade show m-2" role="alert" v-if="fileHandler.title">
                            <strong>Файл "{{ fileHandler.title }}" загружен на сервер успешно и добавлен в очередь на выгрузку в хранилище</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="dropdown-divider"></div>
                    </form>
                </div>
            </div>
        </div>
        <div v-else>
            Загрузка...
        </div>
        <a href="#" role="button" class="btn btn-primary btn-sm" @click.prevent="files.push(getTemplate())">Добавить еще файл</a>
    </div>
</template>

<script>
    export default {
        props: ['subtask', 'checkbox'],
        data() {
            return {
                errors: [],
                files: [],
                storages: [],
                search: '',
                link: '',
            }
        },
        mounted() {
            this.getFiles();
        },
        
        methods:{
            getFiles()
            {
                axios
                    .get('/home/subtasks/' + this.subtask + '/files')
                    .then(response => {
                        this.files = response.data.files;
                        this.storages = response.data.storages;
                        for (let key in this.files) {
                            this.files[key].title = '';
                            //this.files[key].checked = '';
                            this.uploadProgress = '';
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data;
                    })
                ;
            },
            send(key)
            {
                this.errors = [];
                if (! this.storages.length) {
                    alert('У вас нет ни одного хранилища, загружать файлы нельзя');
                    return null;
                }
                this.files[key].title = '';
                let fileHandler = this.files[key];
                let url = '/home/subtasks/' + this.subtask + '/files';
                
                let method = '';
                if (fileHandler.id) {
                    url += '/' + fileHandler.id;
                    method = 'patch';
                } else {
                    method = 'post';
                }
                let formData = new FormData();
                formData.append('name', fileHandler.name);
                formData.append('description', fileHandler.description);
                formData.append('file', this.$refs.file[key].files[0]);
                formData.append('_method', method);
                formData.append('storage', fileHandler.storage_id);
                fileHandler.uploadProgress = '';
                
                axios
                    .post(url, formData, {
                            headers: {'Content-Type': 'multipart/form-data'},
                            onUploadProgress: uploadedEvent => {
                                fileHandler.uploadProgress = Math.round(uploadedEvent.loaded / uploadedEvent.total * 100)
                            }
                        },
                    )
                    .then(response => {
                        fileHandler.uploadProgress = '';
                        this.files[key].id = response.data.id;
                        this.files[key].title = response.data.name;
                        this.files[key].name = response.data.name;
                        this.files[key].checked = true;
                        this.$refs.file[key].files[0] = undefined;
                    })
                    .catch(error => {
                        this.errors = error.response.data;
                        fileHandler.uploadProgress = '';
                    })
                ;
            },
            getTemplate()
            {
                return {
                    name: '',
                    description: '',
                    id: '',
                    storage: '',
                    title: '',
                    uploadProgress: '',
                    checked: '',
                };
            },
            remove(key)
            {
                let fileHandler = this.files[key];
                if (confirm('Точно удалить?') && fileHandler.id) {
                    axios
                        .delete('/home/subtasks/' + this.subtask + '/files/' + fileHandler.id)
                        .then(response => {
                            this.files.splice(key, 1);
                        })
                        .catch(error => {
                            this.errors = error.response.data;
                        })
                    ;
                }
                
            },
            getDate()
            {
                return new Date().getTime()
            },
            
            filtered()
            {
                if (this.files == []) return [];
                return this.files.filter(elem => elem.name.indexOf(this.search) > -1);
            }
        }
    }

</script>
