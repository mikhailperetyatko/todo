<template>
    <div class="container mt-2 mb-2 p-0">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Выберите маркер" v-model="marker_name" aria-label="Маркер" aria-describedby="button-marker-addon" readonly data-toggle="collapse" href="#collapseChooseMarker" role="button" aria-expanded="false" aria-controls="collapseChooseMarker">
            <input type="hidden" name="marker_id" v-model="marker_id">
            <div class="input-group-append" id="button-marker-addon">
                <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" href="#collapseChooseMarker" role="button" aria-expanded="false" >..</button>
                <button class="btn btn-outline-secondary" type="button" @click.prevent="marker_radio=''; marker_id=''; marker_name=''">X</button>
            </div>
        </div>
        <div class="collapse" id="collapseChooseMarker">
            <div class="card card-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="errors != ''">
                    <strong>Внимание! </strong>Произошла непредвиденная ошибка: {{ errors.message }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="text" class="form-control" placeholder="Фильтр" v-model="filter" autofocus>
                <div class="accordion" id="accordionMarkers">
                    <div class="card text m-2 shadow rounded" v-for="marker, key in filtred()">
                        <div class="card-header p-0" :id="'heading' + key" data-toggle="collapse" :data-target="'#collapse_marker' + key" :aria-expanded="filter != '' && marker.markers.length" :aria-controls="'collapse_marker' + key">
                            <div class="form-check m-1">
                                <input type="radio" name="_marker" v-model="marker_radio" class="form-check-input" @click="marker_id=marker.id; marker_name=marker.name; " :id="'markerRadio' + key" :value="marker.id">
                                <label class="form-check-label" :for="'markerRadio' + key">
                                    {{ marker.name }} ({{ marker.markers.length }} шт.)
                                </label>
                            </div>
                        </div>
                        <div :id="'collapse_marker' + key" :class="'collapse' + (filter != '' && marker.markers.length ? ' show' : '')" aria-labelledby="'heading' + key" data-parent="#accordionMarkers">
                            <div class="card-body">
                                <a href="#" role="button" v-if="marker.team_id" class="btn btn-sm btn-info" @click.prevent="editMarker(marker)" data-toggle="modal" data-target="#modalLongEditMarker" >Исправить</a>
                                <a href="#" role="button" v-if="marker.team_id" class="btn btn-sm btn-danger" @click.prevent="deleteMarker(marker)">Удалить</a>
                                <a class="btn btn-sm btn-secondary" data-toggle="collapse" href="#collapseChooseMarker" role="button" aria-expanded="false" aria-controls="collapseChooseMarker">Свернуть</a>
                                <div class="dropdown-divider"></div>
                                
                                <b>Доступные маркеры:</b>
                                <div v-for="submarker in marker.markers">
                                    <input class="d-inline" type="radio" name="_marker" v-model="marker_radio"  @click="marker_id=submarker.id; marker_name=submarker.name" :value="submarker.id"> {{ submarker.name }} 
                                    <span class="badge badge-info" @click.prevent="editMarker(submarker)" data-toggle="modal" data-target="#modalLongEditMarker">Исправить</span>
                                    <span class="badge badge-danger" @click.prevent="deleteMarker(submarker)">Х</span>
                                </div>
                                <div v-if="!marker.markers.length">Нет маркеров</div>
                                <button type="button" class="btn btn-outline-secondary btn-sm mt-2" data-toggle="modal" data-target="#modalLongEditMarker" @click="addSubmarker(marker)">
                                    Новый маркер
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm mt-2" data-toggle="modal" data-target="#modalLongEditMarker" @click="addMarker()">
                    Новая категория
                </button>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="modalLongEditMarker" tabindex="-1" role="dialog" aria-labelledby="modalLongEditMarkerTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Меню редактирования</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="errors != ''">
                            <strong>Внимание! </strong>Произошла непредвиденная ошибка: {{ errors.message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="form-group">
                            <label for="elementName">Название</label>
                            <input type="text" :class="'form-control' + (errors.errors && errors.errors.name ? ' is-invalid' : '')" id="elementName" aria-describedby="Название" v-model="elementToChange.name">
                            <div class="invalid-feedback" v-if="errors.errors && errors.errors.name">
                                {{ errors.errors.name.join('; ') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="elementDescription">Описание</label>
                            <textarea :class="'form-control' + (errors.errors && errors.errors.description ? ' is-invalid' : '')" id="elementDescription" rows="3" v-model="elementToChange.description"></textarea>
                            <div class="invalid-feedback" v-if="errors.errors && errors.errors.description">
                                {{ errors.errors.description.join('; ') }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" ref="modalEditClose" data-dismiss="modal" @click="elementToChange = getTemplate()">Закрыть</button>
                        <button type="button" class="btn btn-primary" @click="elementToChange.callable">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['project_id', 'checked'],
        data() {
            return {
                markers: [],
                filter: '',
                elementToChange: this.getTemplate(),
                errors: [],
                marker_id: '',
                marker_name: '',
                marker_radio: '',
            }
        },
        mounted() {
            this.getMarkers();
            if (this.checked) {
                this.marker_id = this.checked.id;
                this.marker_name = this.checked.name;
                this.marker_radio = this.checked.id;
            }
        },
        
        methods:{
            getTemplate()
            {
                return {
                    name: '',
                    description: '',
                    url: '/home/projects/' + this.project_id + '/markers',
                    id: '',
                    values: [],
                    callable: this.saveElement,
                };
            },
            
            getAxios(url, values, callable)
            {
                this.errors = [];
                axios
                    .post(url, values.join('&'))
                    .then(response => {
                        callable(response.data);
                        this.elementToChange = this.getTemplate();
                        this.$refs['modalEditClose'].click();
                    })
                    .catch(error => {
                        this.errors = error.response.data;
                    })
                ;
            },
            
            getMarkers()
            {
                this.getAxios(
                    '/home/projects/' + this.project_id + '/markers',
                    ['_method=GET'],
                    this.setMarkers,
                );
            },
            
            saveElement(){
                this.elementToChange.values.push('name=' + this.elementToChange.name);
                this.elementToChange.values.push('description=' + this.elementToChange.description);
                this.getAxios(
                    this.elementToChange.url,
                    this.elementToChange.values,
                    this.getMarkers,
                );
            },
            
            filtred()
            {
                if (this.markers == []) return [];
                if (! this.filter) return this.markers;
                console.log(this.markers);
                let search = this.filter.toLowerCase();
                
                return this.markers.map(function (marker) {
                    return {
                        created_at: marker.created_at,
                        description: marker.description,
                        id: marker.id,
                        marker_id: marker.marker_id,
                        markers: marker.markers.filter(submarker => submarker.name.toLowerCase().indexOf(search) > -1),
                        name: marker.name,
                        project_id: marker.project_id,
                        team_id: marker.team_id,
                        updated_at: marker.updated_at,
                    }
                }).filter(marker => marker.markers.length);
            },
            
            addMarker()
            {
                this.elementToChange.values.push('_method=POST');
            },
            
            addSubmarker(marker)
            {
                this.addMarker();
                this.elementToChange.values.push('marker_id=' + marker.id);
            },
            
            editMarker(marker)
            {
                this.elementToChange.url += '/' + marker.id;
                this.elementToChange.values.push('_method=PATCH');
                this.elementToChange.name = marker.name;
                this.elementToChange.description = marker.description;
                this.elementToChange.id = marker.id;
            },
            
            deleteMarker(marker){
                if (confirm('Точно удалить?')) 
                    this.getAxios(
                        '/home/projects/' + this.project_id + '/markers/' + marker.id,
                        ['_method=DELETE'],
                        this.getMarkers,
                    );
            },
            
            setMarkers(data)
            {
                this.markers = data;
            }
        }
    }

</script>
