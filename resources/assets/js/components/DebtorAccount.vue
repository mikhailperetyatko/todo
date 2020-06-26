<template>
    <div id="container">
        <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="account.payments_relation">
            <strong>Внимание!</strong> После внесения изменений в лицевой счет ранее сохраненные связи оплат и начислений будут удалены
        </div>
        <form method="post" :action="form.action" id="myForm" v-on:submit.capture="beforeSubmit()">
            <input type="hidden" name="_method" value="patch">
            <input type="hidden" name="data" v-model="data">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="address">Адрес</label>
                        <input type="text" class="form-control" id="address" aria-describedby="Адрес жилого помещения" v-model="address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="slug">Номер лицевого счета</label>
                        <input type="text" class="form-control" id="slug" aria-describedby="Номер лицевого счета" v-model="slug" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="saldo">Входящее сальдо (положительное сальдо не будет участвовать в расчете)</label>
                        <input type="text" class="form-control" id="saldo" aria-describedby="Входящее сальдо" v-model="saldo">
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr class="w-auto">
                            <th scope="col">Месяц</th>
                            <th scope="col">Начисления</th>
                            <th scope="col">Оплата</th>
                            <th scope="col">Перерасчет</th>
                            <th scope="col">Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="period in periods">
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <select class="form-control w-auto" id="month" v-model="period.accountMonth" required>
                                            <option v-for="month in ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12']" :value="month">{{ getMonth(month) }}</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Год" v-model="period.accountYear" required>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="w-auto">
                                                <th scope="col">Название</th>
                                                <th scope="col">Тариф</th>
                                                <th scope="col">Количество</th>
                                                <th scope="col">Единица измерения</th>
                                                <th scope="col">Только для собственника</th>
                                                <th>Удалить</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="service in period.services">
                                                <td>
                                                    <input type="text" class="form-control w-auto" placeholder="Название" v-model="service.name" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control w-auto" placeholder="Тариф" v-model="service.tariff" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control w-auto" placeholder="Количество" v-model="service.amount" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control w-auto" placeholder="Единица измерения" v-model="service.amount_title" required>
                                                </td>
                                                <td>
                                                    <select class="form-control w-auto" id="month" v-model="service.onlyOwner" required>
                                                        <option :value="true">Да</option>
                                                        <option :value="false">Нет</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-class" @click="period.services.splice(period.services.indexOf(service), 1)">X</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-primary btn-sm" @click="period.services.push(getTemplate('service'))">Добавить услугу</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="w-auto">
                                                <th scope="col">Дата</th>
                                                <th scope="col">Сумма</th>
                                                <th>Удалить</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="payment in period.paid">
                                                <td>
                                                    <input type="date" class="form-control w-auto" placeholder="Дата" v-model="payment.date">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control w-auto" placeholder="Сумма" v-model="payment.amount" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-class" @click="period.paid.splice(period.paid.indexOf(payment), 1)">X</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-primary btn-sm" @click="period.paid.push(getTemplate('payment'))">Добавить оплату</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="w-auto">
                                                <th scope="col">Дата</th>
                                                <th scope="col">Сумма</th>
                                                <th>Удалить</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="recosting in period.recosting">
                                                <td>
                                                    <input type="date" class="form-control w-auto" placeholder="Дата" v-model="recosting.date">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control w-auto" placeholder="Сумма" v-model="recosting.amount" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-class" @click="period.recosting.splice(period.recosting.indexOf(recosting), 1)">X</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-primary btn-sm" @click="period.recosting.push(getTemplate('payment'))">Добавить перерасчет</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-class" @click="periods.splice(periods.indexOf(period), 1)">X</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <button type="button" class="btn btn-primary btn-sm" @click="periods.push(getTemplate('period'))">Добавить период</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-success btn-block">Сохранить</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['account', 'form'],
        data() {
            return {
                address: this.account.address,
                slug: this.account.slug,
                saldo: this.account.incoming_saldo,
                periods: {},
                error: [],
                success: [],
                data: {},
            }
        },
        mounted() {
            this.periods = this.account.balance.map(function(period){
                let date = period.month.split('-');
                period.accountYear = date[0];
                period.accountMonth = date[1];
                return period;
            });
            
            console.log(this.periods);
        },
        
        methods:{
            getMonth(month)
            {   
                switch(month) {
                    case '01':
                        return 'Январь';
                    case '02':
                        return 'Февраль';
                    case '03':
                        return 'Март';
                    case '04':
                        return 'Апрель';
                    case '05':
                        return 'Май';
                    case '06':
                        return 'Июнь';
                    case '07':
                        return 'Июль';
                    case '08':
                        return 'Август';
                    case '09':
                        return 'Сентябрь';
                    case '10':
                        return 'Октябрь';
                    case '11':
                        return 'Ноябрь';
                    case '12':
                        return 'Декабрь';
                }
            },
            
            getTemplate(element)
            {
                switch(element) {
                    case 'service':
                        return {
                            amount: '',
                            amount_title: '',
                            name: '',
                            onlyOwner: false,
                            tariff: '',
                        };
                    case 'payment':
                        return {
                            date: '',
                            amount: '',
                        };
                    case 'period':
                        return {
                            accountMonth: '',
                            accountYear: '',
                            month: '',
                            paid: [this.getTemplate('payment')],
                            recosting: [this.getTemplate('payment')],
                            services: [this.getTemplate('service')],
                        };
                }
            },
            
            beforeSubmit()
            {
                this.data = JSON.stringify({
                    address: this.address,
                    slug: this.slug,
                    saldo: this.saldo,
                    periods: this.periods,
                });
                alert(this.data);
            },
            
            round(element, n = 2)
            {
                return element.toFixed(n) * 1;
            },
        }
    }

</script>
