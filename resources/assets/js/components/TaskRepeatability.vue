<template>
    <div>
        <div class="custom-control custom-switch">
            <input type="checkbox" data-toggle="collapse" v-model="model.repeatability" href="#collapseIntervals" :class="'custom-control-input' + (errors['repeatability'] ? ' is-invalid' : '')" id="customSwitch1" name="repeatability">
            <label class="custom-control-label" for="customSwitch1">Повторять мероприятие</label>
            <div class="invalid-feedback">
                {{ errors['repeatability'] }}
            </div>
        </div>
        <div v-if="model['repeatability']" :class="'collapse' + (model['repeatability'] ? ' show' : '')" id="collapseIntervals">
            <div class="card card-body">
                <div class="form-group">
                    <label for="inputIntervalValue">Повторять каждые</label>
                    <input type="text" :class="'form-control' + (errors['interval_value'] ? ' is-invalid' : '')" id="inputIntervalValue" name="interval_value" placeholder="Введите значение интервала" :required="model['repeatability']" v-model="model.value">
                    <div class="invalid-feedback">
                        {{ errors['interval_value'] }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="intervalsSelect">Тип интервала</label>
                    <select :class="'form-control' + (errors['interval'] ? ' is-invalid' : '')" id="intervalsSelect" name="interval" :required="model['repeatability']" v-model="model.type">
                        <option disabled selected>Выберите тип интервала...</option>
                        <option v-for="interval in intervals" :value="interval.value">{{ interval.name }}</option>
                    </select>
                    <div class="invalid-feedback">
                        {{ errors['interval'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
        props: ['intervals', 'old', 'errors', 'load'],
        data() {
            return {
                model:{
                    repeatability: this.getOld('repeatability'),
                    value: this.getOld('intervalValue'),
                    type: this.getOld('interval'),
                },
            }
        },
        mounted() {
        },
        
        methods:{
            getOld(name) {
                let loaded = this.load ? this.getLoad(name) : '';
                return this.old[name] ? this.old[name] : loaded;
            },
            getLoad(name)
            {
                if (name == 'intervalValue') return this.load.interval_value;
                if (name == 'interval') return this.intervals.find(item => item.id == this.load.reference_interval_id).value;
                if (name == 'repeatability') return this.load.repeatability;
            }
        }
    }

</script>
