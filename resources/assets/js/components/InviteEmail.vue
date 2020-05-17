<template>
    <div>
        <div class="btn btn-secondary btn-block" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Члены команды - {{ this.members.length }}
        </div>
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <div class="table-responsive">
                    <table width="100%">
                        <tr v-for="member,key in members">
                            <td>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control" placeholder="Email пользователя" aria-label="Email пользователя" :aria-describedby="'button-addon' + member.id" :name='member.isNew ? "news[" + key + "][email]" : "members[" + key + "][email]"' v-model="models[member.id]" v-bind:class="[member.errors ? 'is-invalid' : '']" :readonly='! member.isNew' required>
                                  <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" :id="'button-addon' + member.id" @click="del(key)" :disabled="member.id == owner.id">Х</button>
                                  </div>
                                  <div class="input-group-append">
                                    <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#exampleModalCenter" @click="helper = (member.isNew ? 'Ожидает согласие от пользователя на вступление в группу. Исправление email приведет к аннулированию ранее направленного приглашения' : 'Действительный член команды')" data-placement="top">?</button>
                                  </div>
                                  <div class="invalid-feedback" v-if="member.errors">
                                    {{ member.errors.join(', ') }}
                                  </div>
                                </div>
                            </td>
                            <td align="rigth">
                                <div class="input-group mb-3">
                                    <select class="custom-select" :disabled='member.id == owner.id' v-model="userRole[member.id]" :name='member.isNew ? "news[" + key + "][role]" : "members[" + key + "][role]"' required>
                                        <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                                    </select>
                                    <input v-if="member.id == owner.id" type='hidden' :name='"members[" + key + "][role]"' :value="userRole[member.id]">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#exampleModalCenter" @click="helper = (userRole[member.id] ? roles.filter(role => role.id == userRole[member.id])[0].description : 'Установите роль в команде')">?</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" v-model="newEmail" placeholder="Email нового пользователя" aria-label="Email нового пользователя" aria-describedby="button-addon-new" id="newEmail">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="button-addon-new" @click="put()">+</button>
                    </div>
                    <div class="input-group-append">
                    <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#exampleModalCenter" @click="helper = 'Введите email пользователя, которого хотите пригласить в группу, и нажмите +'">?</button>
                  </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Подсказка</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                {{ helper }}
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
        props: ['users', 'errors', 'news', 'invited', 'roles', 'owner'],
        data() {
            return {
                members: [],
                newEmail: '',
                models: [],
                userRole: [],
                counter: 0,
                helper: '',
            }
        },
        mounted() {
            this.members = this.users;
            for (let i in this.members) {
                this.members[i].isNew = false;
                this.models[this.members[i].id] = this.members[i].email;
                this.userRole[this.members[i].id] = this.members[i].pivot.role_id;
            }
            
            for (let i in this.invited) {
                this.invited[i].id += '_invite';
                this.invited[i].isNew = true;
                this.models[this.invited[i].id] = this.invited[i].email;
                this.userRole[this.invited[i].id] = this.invited[i].role_id;
                this.members.push(this.invited[i]);
            }
            
            for (let i in this.news) {
                let id = 'new_' + this.counter
                this.members.push({
                    isNew: true,
                    id: id,
                    errors: this.errors['news.' + i + '.email'],
                });
                this.models[id] = this.news[i].email;
                this.userRole[id] = this.news[i].role;
                this.counter++;
            }
            
            console.log(this.errors);
        },
        
        methods:{
            del(key)
            {
                if (confirm('Точно удалить?')) this.members.splice(key, 1);
            },
            put(key)
            {
                if (this.newEmail) {
                    if (this.models.indexOf(this.newEmail) == -1) {
                        this.counter++;
                        let id = 'new_' + this.counter;
                        let newUser = {
                            isNew: true,
                            id: id,
                        };
                        this.models[id] = this.newEmail;
                        this.newEmail = '';
                        this.members.push(newUser);   
                    } else {
                        alert('Такой email уже введен ранее');
                    }
                } else {
                    alert('Введите email');
                }
            },
        }
    }

</script>
