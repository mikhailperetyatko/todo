<template>
    <div class="container">
        <div v-for="month, monthNumber in dateRange" class="shadow p-1 col-lg-7">
            <div class="card shadow">
                <h5 class="card-header">{{ monthName[monthNumber] }} {{ year }} года</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ПН</th>
                                    <th scope="col">ВТ</th>
                                    <th scope="col">СР</th>
                                    <th scope="col">ЧТ</th>
                                    <th scope="col">ПТ</th>
                                    <th scope="col">СБ</th>
                                    <th scope="col">ВС</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="week, weekNumber in month">
                                    <th scope="row">{{ weekNumber }}</th>
                                    <td v-for="day in [1, 2, 3, 4, 5, 6, 7]" :class="week[day] && week[day].is_holiday ? 'text-danger shadow-sm' : ''">
                                        <div v-if="week[day]" @click="dateRange[monthNumber][weekNumber][day].is_holiday = ! week[day].is_holiday">
                                            {{ week[day].day }}
                                            <input type="hidden" :name="'days[' + monthNumber + weekNumber + day + '][weekend]'" v-model="dateRange[monthNumber][weekNumber][day].is_holiday">
                                            <input type="hidden" :name="'days[' + monthNumber + weekNumber + day + '][date]'" :value="dateRange[monthNumber][weekNumber][day].date">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['range', 'year'],
        data() {
            return {
                dateRange: [],
                monthName: {
                    1: 'Январь',
                    2: 'Февраль',
                    3: 'Март',
                    4: 'Апрель',
                    5: 'Май',
                    6: 'Июнь',
                    7: 'Июль',
                    8: 'Август',
                    9: 'Сентябрь',
                    10: 'Октябрь',
                    11: 'Ноябрь',
                    12: 'Декабрь',
                },
            }
        },
        mounted() {
            this.dateRange = this.range;
        },
        
        methods:{
        }
    }

</script>
