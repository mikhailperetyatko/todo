<template>
    <div>
        <div class="form-group">
            <label for="teamSelect">Команда</label>
            <select class="form-control" id="teamSelect" name="team" v-model="teamId">
                <option v-for="team, key in teams" :value="team.id">{{ team.name }}</option>
            </select>
        </div>
        <div class="card mb-2">
            <div class="card-body">    
                <div class="form-group">
                    <label for="formControlFilter">Допущенные участники команды</label>
                    <input type="text" class="form-control" id="formControlFilter" placeholder="Фильтр пользователей" v-model="filter">
                </div>
                <div class="custom-control custom-switch" v-for="user, key in getFilteredUsers()">
                    <input type="checkbox" class="custom-control-input" :id="'customSwitch' + key" :name="'users[' + key + ']'" v-model="members" :value="user.id">
                    <label class="custom-control-label" :for="'customSwitch' + key">{{ user.name }}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['teams', 'project'],
        data() {
            return {
                teamId:'',
                filter:'',
                members: [],
                owner: '',
            }
        },
        mounted() {
            if (this.project) {
                this.teamId = this.project.team_id;
                this.members = this.project.members.map(function(member){
                    return member.id;
                });
            } else {
                this.teamId = this.teams[0].id;
            }
        },
        
        methods:{
            getFilteredUsers()
            {
                if (! this.teamId) return [];
                let search = this.filter.toLowerCase();
                let team = this.teams.filter(team => team.id == this.teamId).shift();
                return search ? team.users.filter(user => user.name.toLowerCase().indexOf(search) > -1) : team.users;
            },
        }
    }

</script>
