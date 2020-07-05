<template>
    <div>
        <div class="form-group" v-if="preinstaller_tasks">
            <label for="selectPreinstallerTasks">Предустановленные задачи</label>
            <select class="form-control" v-model="preinstallerTaskModel" id="selectPreinstallerTasks" @change="preinstall()">
                <option disabled selected>...</option>
                <option v-for="task, key in preinstaller_tasks" :value="key">{{ task.name }}</option>
            </select>
        </div>
        <div class="accordion mt-1 mb-1 shadow" id="accordion">
            <div class="card" v-for="subtask, key in model">
                <div class="card-header p-1" :id="'heading' + key">
                    <h6 class="mb-0 pl-2">
                        <a href="#" data-toggle="collapse" :data-target="'#collapse' + key" :aria-controls="'collapse' + key" aria-expanded="false">
                            {{ key + 1 }}) {{ subtask.description ? subtask.description : 'Новая задача' }}
                            <span class="badge badge-warning" v-if="!subtask.description">!</span>
                        </a>
                        <button type="button" class="btn btn-danger m-0 btn-sm" @click="deleteModelHolder(key)">X</button>
                    </h6>
                </div>
                <div :id="'collapse' + key" class="collapse p-2" :aria-labelledby="'heading' + key" data-parent="#accordion">
                    <div class="form-group">
                        <label :for="'subtaskDescription' + key">Описание задачи</label>
                        <textarea :class="'form-control' + (getError(key, 'description') ? ' is-invalid' : '')" :id="'subtaskDescription' + key" rows="3" :name="'subtasks[' + key + '][description]'" required v-model="subtask.description"></textarea>
                        <div class="invalid-feedback">
                        {{ getError(key, 'description') }}
                        </div>
                    </div>
                    <div v-if="users">
                        <label :for="'subtaskExecutor' + key">Исполнитель</label>
                        <div class="input-group mb-3">
                            <input class="form-control" data-toggle="modal" :data-target="'#chooseExecutor' + key" type="text" :id="'subtaskExecutor' + key" :value="(subtask.user_executor != '' ? users.find(element => element.id == subtask.user_executor).name : 'Выберите исполнителя')" readonly :aria-describedby="'button-addon-executor' + key">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" :id="'button-addon-executor' + key" @click="subtask.user_executor = ''">Х</button>
                            </div>
                            <input type="hidden" :name="'subtasks[' + key + '][user_executor]'" v-model="subtask.user_executor">
                            <div class="invalid-feedback">
                                {{ getError(key, 'user_executor') }}
                            </div>
                        </div>
                        <div class="modal fade" :id="'chooseExecutor' + key" tabindex="-1" role="dialog" :aria-labelledby="'chooseExecutor' + key + 'Title'" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" :id="'chooseExecutor' + key + 'Title'">Выбор исполнителя</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <input v-model="filterUser[key]" class="form-control" placeholder="Отфильтровать пользователей">
                                <div class="custom-control custom-radio" v-for="user, keyUser in filteredUsers(key)">
                                  <input type="radio" :id="'customRadioExecutor' + key + keyUser" class="custom-control-input" v-model="subtask.user_executor" :value="user.id">
                                  <label class="custom-control-label" :for="'customRadioExecutor' + key + keyUser">{{ user.name }}</label>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <label :for="'subtaskValidator' + key">Контроллер</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" :id="'subtaskValidator' + key" :value="(subtask.user_validator != '' ? users.find(element => element.id == subtask.user_validator).name : 'Выберите контроллера')" readonly :aria-describedby="'button-addon-validator' + key" data-toggle="modal" :data-target="'#chooseValidator' + key">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" :id="'button-addon-validator' + key" @click="subtask.user_validator = ''">Х</button>
                            </div>
                            <input type="hidden" :name="'subtasks[' + key + '][user_validator]'" v-model="subtask.user_validator">
                            <div class="invalid-feedback">
                                {{ getError(key, 'user_validator') }}
                            </div>
                        </div>
                        <div class="modal fade" :id="'chooseValidator' + key" tabindex="-1" role="dialog" :aria-labelledby="'chooseValidator' + key + 'Title'" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" :id="'chooseValidator' + key + 'Title'">Выбор контроллера</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <input v-model="filterUser[key]" class="form-control" placeholder="Отфильтровать пользователей">
                                <div class="custom-control custom-radio" v-for="user, keyUser in filteredUsers(key)">
                                  <input type="radio" :id="'customRadioValidator' + key + keyUser" class="custom-control-input" v-model="subtask.user_validator" :value="user.id">
                                  <label class="custom-control-label" :for="'customRadioValidator' + key + keyUser">{{ user.name }}</label>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <p>Дата и время выполнения задачи</p>
                    <nav>
                        <div class="nav nav-pills" :id="'subtask_' + key + '_nav-tab'" role="tablist">
                            <a class="nav-item nav-link active" :id="'subtask_' + key + 'nav-first-tab'" data-toggle="tab" :href="'#subtask_' + key + 'nav-first'" role="tab" :aria-controls="'subtask_' + key + 'nav-first'" aria-selected="true">Расчет</a>
                            <a class="nav-item nav-link" :id="'subtask_' + key + 'nav-second-tab'" data-toggle="tab" :href="'#subtask_' + key + 'nav-second'" role="tab" :aria-controls="'subtask_' + key + 'nav-second'" aria-selected="false">Вручную</a>
                        </div>
                    </nav>
                    <div class="tab-content" :id="'subtask_' + key + 'nav-tabContent'">
                        <div class="tab-pane fade show active" :id="'subtask_' + key + 'nav-first'" role="tabpanel" :aria-labelledby="'subtask_' + key + 'nav-first-tab'">
                            <div class="form-group">
                                <label :for="'subtaskDelay' + key">Сдвинуть срок исполнения на</label>
                                <input type="text" :class="'form-control' + (getError(key, 'delay') ? ' is-invalid' : '')" :id="'subtaskDelay' + key" :name="'subtasks[' + key + '][delay]'" placeholder="Введите значение интервала" v-model="subtask.delay">
                                <div class="invalid-feedback">
                                    {{ getError(key, 'delay') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label :for="'delayIntervalsSelect' + key">Тип интервала</label>
                                <select :class="'form-control' + (getError(key, 'delay_interval') ? ' is-invalid' : '')" :id="'delayIntervalsSelect' + key" :name="'subtasks[' + key + '][delay_interval]'" v-model="subtask.delay_interval" :required="subtask.delay != ''">
                                    <option disabled selected>Выберите тип интервала...</option>
                                    <option v-for="interval in selects.intervals" :value="interval.value">{{ interval.name }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    {{ getError(key, 'delay_interval') }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" :id="'subtask_' + key + 'nav-second'" role="tabpanel" :aria-labelledby="'subtask_' + key + 'nav-second-tab'">
                            <div class="form-group">
                                <label>Дата</label>
                                <input type="date" :class="'form-control' + (getError(key, 'date') ? ' is-invalid' : '')" :name="'subtasks[' + key + '][date]'" v-model="subtask.date">
                                <div class="invalid-feedback">
                                    {{ getError(key, 'date') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Время</label>
                                <input type="time" :class="'form-control' + (getError(key, 'time') ? ' is-invalid' : '')" :name="'subtasks[' + key + '][time]'" v-model="subtask.time">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" :name="'subtasks[' + key + '][id]'" v-model="subtask.id">
                    <tags :subtask_key="key" :tags="tags" :subtask_tags='subtask.tags' :key="subtask.counter">Подождите...</tags>
                    <a class="btn btn-primary btn-sm" data-toggle="collapse" role="button" :href="'#collapseExt' + key" aria-expanded="false" :aria-controls="'collapseExt' + key">
                        Дополнительные параметры
                    </a>
                    <div :class="'collapse'" :id="'collapseExt' + key">
                        <div class="card card-body">
                            <div class="form-group">
                                <label :for="'subtaskShowableBy' + key">Включить в повестку за ... (дни) до срока выполнения задачи</label>
                                <input type="text" :class="'form-control' + (getError(key, 'showable_by') ? ' is-invalid' : '')" :id="'subtaskShowableBy' + key" :name="'subtasks[' + key + '][showable_by]'" placeholder="Введите значение интервала" v-model="subtask.showable_by">
                                <div class="invalid-feedback">
                                    {{ getError(key, 'showable_by') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label :for="'difficulty' + key">Сложность</label>
                                <select :class="'form-control' + (getError(key, 'difficulty') ? ' is-invalid' : '')" :id="'difficulty' + key" :name="'subtasks[' + key + '][difficulty]'" v-model="subtask.difficulty">
                                    <option disabled selected>Выберите сложность...</option>
                                    <option v-for="difficulty in selects.difficulties" :value="difficulty.value">{{ difficulty.name }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    {{ getError(key, 'difficulty') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label :for="'priority' + key">Приоритет</label>
                                <select :class="'form-control' + (getError(key, 'priority') ? ' is-invalid' : '')" :id="'priority' + key" :name="'subtasks[' + key + '][priority]'" v-model="subtask.priority">
                                    <option disabled selected>Выберите приоритет...</option>
                                    <option v-for="priority in selects.priorities" :value="priority.value">{{ priority.name }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    {{ getError(key, 'priority') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label :for="'subtaskLocation' + key">Место выполнения задачи</label>
                                <input type="text" :class="'form-control' + (getError(key, 'location') ? ' is-invalid' : '')" :id="'subtaskLocation' + key" :name="'subtasks[' + key + '][location]'" placeholder="Введите значение интервала" v-model="subtask.location">
                                <div class="invalid-feedback">
                                    {{ getError(key, 'location') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label :for="'subtaskScore' + key">Количество очков</label>
                                <input type="text" :class="'form-control' + (getError(key, 'score') ? ' is-invalid' : '')" :id="'subtaskScore' + key" :name="'subtasks[' + key + '][score]'" placeholder="Введите колчество очков" v-model="subtask.score">
                                <div class="invalid-feedback">
                                    {{ getError(key, 'score') }}
                                </div>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" v-model="subtask.not_delayable" :class="'custom-control-input' + (errors['not_delayable'] ? ' is-invalid' : '')" :id="'customSwitchSubtask' + key" :name="'subtasks[' + key + '][not_delayable]'">
                                <label class="custom-control-label" :for="'customSwitchSubtask' + key">Задачу отложить нельзя</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-info mb-4 mt-2 btn-sm btn-block" @click="newModelHolder()">Добавить новую задачу</button>
    </div>
</template>
<script>
export default {
        props: ['selects', 'old', 'errors', 'load', 'users', 'preinstaller_tasks', 'tags'],
        data() {
            return {
                model: [],
                filterUser: [],
                preinstallerTaskModel: '',
                counter: 0,
            }
        },
        mounted() {
            if (this.load) {
                for (let i in this.load.subtasks) {
                    let subtask = this.load.subtasks[i];
                    let executionDateTime = subtask.execution_date.split(' ');
                    subtask.date = executionDateTime[0];
                    subtask.time = executionDateTime[1].split(':');
                    subtask.time = subtask.time[0] + ':' + subtask.time[1];
                    subtask.delay_interval = this.selects.intervals.find(item => item.id == subtask.reference_interval_id).value;
                    subtask.difficulty = this.selects.difficulties.find(item => item.id == subtask.reference_difficulty_id).value;
                    subtask.priority = this.selects.priorities.find(item => item.id == subtask.reference_priority_id).value;
                    subtask.user_executor = subtask.executor_id;
                    subtask.user_validator = subtask.validator_id;
                    this.model.push(this.getModelTemplate(subtask));
                }
            }
            
            for (let i in this.old.subtasks) {
                let subtask = this.old.subtasks[i];
                if (! this.model[i]) {
                    this.model.push(this.getModelTemplate(subtask));
                } else {
                    this.model[i] = this.getModelTemplate(subtask);
                }
            }
            if (this.model.length == 0) this.newModelHolder();
        },
        
        methods:{
            newModelHolder()
            {
                this.model.push(this.getModelTemplate(new Array()));
            },
            deleteModelHolder(index)
            {
                if (confirm('Вы уверены?')) {
                    this.model.splice(index, 1);
                    for (let i in this.errors) {
                        if (i.indexOf('subtasks.' + index + '.') > -1) delete this.errors[i];
                    }
                    if (this.model.length == 0) this.newModelHolder();
                }
            },
            getValue(val) 
            {
                return val ? val : '';
            },
            getClass(subtask)
            {
                if (subtask.delay || subtask.delayInterval || subtask.difficulty ||subtask.priority || subtask.location || subtask.score || subtask.delayable) return ' show';
                return '';
            },
            getError(index, key)
            {
                return this.errors['subtasks.' + index + '.' + key];
            },
            getModelTemplate(values)
            {
                return {
                    'description': this.getValue(values['description']),
                    'id': this.getValue(values['id']),
                    'delay': this.getValue(values['delay']),
                    'delay_interval': this.getValue(values['delay_interval']),
                    'date': this.getValue(values['date']),
                    'time': this.getValue(values['time']),
                    'difficulty': this.getValue(values['difficulty']),
                    'priority': this.getValue(values['priority']),
                    'location': this.getValue(values['location']),
                    'score': this.getValue(values['score']),
                    'not_delayable': this.getValue(values['not_delayable']),
                    'user_executor': this.getValue(values['user_executor']),
                    'user_validator': this.getValue(values['user_validator']),
                    'showable_by': this.getValue(values['showable_by']),
                    'tags': this.getValue(values['tags']),
                    'counter': this.counter++,
                };
            },
            filteredUsers(key)
            {
                return this.filterUser[key] == undefined ? this.users : this.users.filter(user => user.name.toLowerCase().indexOf(this.filterUser[key].toLowerCase()) > -1);
            },
            preinstall()
            {
                if (! confirm('Вы уверены?')) return null;
                this.model = [];
                for (let i in this.preinstaller_tasks[this.preinstallerTaskModel].subtasks) {
                    let subtask = this.preinstaller_tasks[this.preinstallerTaskModel].subtasks[i];
                    subtask.delay_interval = this.selects.intervals.find(item => item.id == subtask.reference_interval_id).value;
                    subtask.difficulty = this.selects.difficulties.find(item => item.id == subtask.reference_difficulty_id).value;
                    subtask.priority = this.selects.priorities.find(item => item.id == subtask.reference_priority_id).value;
                    this.model.push(this.getModelTemplate(subtask));
                }
                let taskRepeatabilityComponent = this.$root.$children.find(child => child.$options._componentTag == 'task-repeatability');
                if (typeof taskRepeatabilityComponent == 'object') {
                    let repeatabilityModel = taskRepeatabilityComponent.model;
                    let repeatabilityPreinstaller = this.preinstaller_tasks[this.preinstallerTaskModel]
                    repeatabilityModel.repeatability = repeatabilityPreinstaller.repeatability;
                    repeatabilityModel.type = taskRepeatabilityComponent.$options.propsData.intervals.find(interval => interval.id == repeatabilityPreinstaller.reference_interval_id).value;
                    repeatabilityModel.value = repeatabilityPreinstaller.interval_value;
                }
            },
        }
    }

</script>
