<template>
    <div class="container">
        
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
