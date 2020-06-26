<template>
    <div id="container">
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-for="error in error">
            <strong>Произошла ошибка!</strong> {{ error.response ? error.response.data.message : error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-dismissible fade show" role="alert" v-for="success in success">
            Запись произведена успешно!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseAuto" aria-expanded="false" aria-controls="collapseAuto" id="collapseAutoButton">
            Автоматическое распределение
        </button>
        <div class="collapse" id="collapseAuto">
            <div class="card card-body">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radios1" id="radios1" value="1" v-model="auto">
                    <label class="form-check-label" for="radios1">
                        Платеж, определенный в периоде, вначале распределить на этот период, остаток - на ранее возникшие периоды
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radios2" id="radios2" value="2" v-model="auto">
                    <label class="form-check-label" for="radios2">
                        Платежи распределить в счет ранее возникших периодов
                    </label>
                </div>
                <button class="btn btn-sm btn-primary" @click="autoProccessPayment()">Распределить автоматически</button>
            </div>
        </div>
        <button class="btn btn-info" @click="saveRelations()">Сохранить</button>
        <button class="btn btn-danger" @click="cancelRelations()">Сбросить</button>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr class="w-auto">
                        <th scope="col">Месяц</th>
                        <th scope="col">Начисления</th>
                        <th scope="col">Оплата</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="period, periodKey in account.balance">
                        <th scope="row">{{ getMonth(period.month) }}</th>
                        <td>
                            <table class="table table-striped table-sm">
                                <tr class="w-auto">
                                    <th scope="col">Услуга</th>
                                    <th scope="col">Начислено</th>
                                    <th scope="col">Оплачено</th>
                                </tr>
                                <tr v-for="service, serviceKey in period.services">
                                    <td>{{ service.name }}</td>
                                    <td>{{ getServiceAmount(service) }}</td>
                                    <td v-if="relations.filter(relation => relation.service_uuid == service.uuid).length">
                                        <table class="table table-striped table-sm">
                                            <tr class="w-auto">
                                                <th scope="col">Платеж</th>
                                                <th scope="col">Принято в оплату</th>
                                                <th scope="col">Остаток от услуги</th>
                                                <th scope="col">Удалить</th>
                                            </tr>
                                            <tr v-for="process, processKey in getProcess(service)">
                                                <td>{{ process.type == 'subZero' ? '-' : '' }} {{ payments.find(payment => payment.uuid == process.payment_uuid).amount }} руб.</td>
                                                <td>
                                                    <div class="input-group mb-3">
                                                      <input type="text" class="form-control w-auto" placeholder="Сумма" aria-label="Сумма" :aria-describedby="'button-addon' + processKey" v-model="process.model">
                                                      <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary btn-sm" type="button" :id="'button-addon' + processKey" @click="changeProccessPayment(process, service)">></button>
                                                      </div>
                                                    </div>
                                                <td> {{ process.serviceBalance }}</td>
                                                <td><button type="button" class="btn btn-danger btn-sm" @click="relations.splice(relations.indexOf(process), 1)">X</button></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td v-else> - </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <div class="alert alert-success" role="alert" v-for="group in getOrderedProcessByServices(period.services)">
                                Применен {{ getPaymentName(group.payment.name)[1] }} на сумму {{ group.payment.type == 'subZero' ? '-' : '' }}{{ group.payment.amount }} руб. в части суммы {{ group.amount }} руб.
                                <button class="btn btn-sm btn-danger" @click="deleteRelations(group.id)">Х</button>
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" @click="currentPeriodKey=periodKey">
                                Выбрать
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Выбор платежа</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <p>Общий баланс услуг для гашения {{ getBalancePeroiod() }} руб.</p>
                            <div class="form-group" v-for="payment, paymentKey in payments">
                                <div v-if="getBalance(payment.uuid, payment.amount) > 0">
                                    <label :for="'paymentInput' + paymentKey">{{ paymentKey + 1 }}) {{ getPaymentName(payment.name)[0] }} на сумму {{ payment.amount + (payment.date ? ' от ' + payment.date : '') }} руб. {{payment.type == 'subZero' ? '(с минусом)' : '' }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Введите сумму платежа" :id="'paymentInput' + paymentKey" :aria-describedby="'paymentInputButton' + paymentKey" v-model="payment.model">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" :id="'paymentInputButton' + paymentKey" @click="proccessPayment(payment)">></button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Для распределения доступно {{ getBalance(payment.uuid, payment.amount) }} руб. {{payment.type == 'subZero' ? '(с минусом)' : '' }}</small>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        props: ['account', 'form'],
        data() {
            return {
                relations: [],
                currentPeriodKey: 0,
                payments: [],
                auto: 1,
                error: [],
                success: [],
            }
        },
        mounted() {
            if (this.account.incoming_saldo < 0) {
                this.payments.push({
                    name: 'incoming_balance',
                    type: 'subZero',
                    amount: Math.abs(this.account.incoming_saldo),
                    model: Math.abs(this.account.incoming_saldo),
                });
            }
            
            for (let i in this.account.balance) {
                let tempPayments = this.account.balance[i].paid;
                tempPayments.map(function(payment){
                    payment.model = payment.amount;
                    payment.name = 'payment';
                    return payment;
                });
                
                let tempRecostings = this.account.balance[i].recosting;
                tempRecostings.map(function(recosting){
                    recosting.name = 'recosting';
                    recosting.type = recosting.amount > 0 ? 'aboveZero' : 'subZero';
                    recosting.amount = Math.abs(recosting.amount);
                    recosting.model = recosting.amount;
                    return recosting;
                });
                this.payments = this.payments.concat(tempPayments, tempRecostings);
            }
            
            this.relations = this.account.payments_relation ? this.account.payments_relation : [];
            for (let i in this.relations) {
                this.relations[i].model = this.relations[i].amount;
            }
            
            console.log(this.payments);
        },
        
        methods:{
            getPaymentName(name)
            {
                switch(name) {
                    case 'payment':
                        return ['Платеж', 'платеж'];
                    case 'recosting':
                        return ['Перерасчет', 'перерасчет'];
                    case 'incoming_balance':
                        return ['Входящее сальдо', 'входящее сальдо'];
                } 
            },
            
            generateUUID()
            {
                var d = new Date().getTime();
                var d2 = (performance && performance.now && (performance.now()*1000)) || 0;
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16;
                    if(d > 0){
                        r = (d + r)%16 | 0;
                        d = Math.floor(d/16);
                    } else {
                        r = (d2 + r)%16 | 0;
                        d2 = Math.floor(d2/16);
                    }
                    return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
                });
            },
            
            getMonth(string)
            {
                let date = string.split('-');
                
                switch(date[1]) {
                    case '01':
                        return 'Январь ' + date[0];
                    case '02':
                        return 'Февраль ' + date[0];
                    case '03':
                        return 'Март ' + date[0];
                    case '04':
                        return 'Апрель ' + date[0];
                    case '05':
                        return 'Май ' + date[0];
                    case '06':
                        return 'Июнь ' + date[0];
                    case '07':
                        return 'Июль ' + date[0];
                    case '08':
                        return 'Август ' + date[0];
                    case '09':
                        return 'Сентябрь ' + date[0];
                    case '10':
                        return 'Октябрь ' + date[0];
                    case '11':
                        return 'Ноябрь ' + date[0];
                    case '12':
                        return 'Декабрь ' + date[0];
                }
            },
            
            round(element, n = 2)
            {
                return element.toFixed(n) * 1;
            },
            
            getServiceAmount(service)
            {
                return this.round(service.amount * service.tariff).toFixed(2) * 1;
                
            },
                        
            getBalance(uuid, amount)
            {
                this.relations.forEach(function(item) {
                    if (item.payment_uuid == uuid || item.service_uuid == uuid) {
                        if (item.service_uuid == uuid && item.type == 'aboveZero') amount += item.amount;
                        else amount -= item.amount;
                    }
                });
                amount = this.round(amount);
                
                return amount < 0 ? 0 : amount;
            },
            
            getBalancePeroiod()
            {
                let balance = 0;
                let services = this.account.balance[this.currentPeriodKey].services;
                for (let i in services) {
                    balance += this.getBalance(services[i].uuid, this.getServiceAmount(services[i]));
                }
                return this.round(balance);
            },
            
            proccessPayment(payment)
            {
                let paymentBalance = this.getBalance(payment.uuid, payment.amount);
                let periodBalance = this.getBalancePeroiod();
                
                let period = this.account.balance[this.currentPeriodKey];
                
                let forDistribution = payment.model > paymentBalance ? paymentBalance : payment.model;
                forDistribution = forDistribution > periodBalance ? periodBalance : forDistribution;
                forDistribution < 0 ? 0 : forDistribution;
                if (periodBalance <= 0 || paymentBalance <= 0 || forDistribution <= 0) return null;
                this.allocatePayment(period.services, payment, forDistribution);
                payment.model = this.getBalance(payment.uuid, payment.amount);
            },
            
            allocatePayment(services, payment, forDistribution, id = this.generateUUID())
            {
                if (services.length < 1) return;
                let periodAmount = 0;
                let balance = 0;
                
                for (let i in services) {
                    periodAmount += this.getBalance(services[i].uuid, this.getServiceAmount(services[i]));
                }
                
                forDistribution = forDistribution > periodAmount ? periodAmount : forDistribution;
                
                for (let i in services) {
                    let service = services[i];
                    let serviceAmount = this.getServiceAmount(service);
                    let serviceBalance = this.getBalance(service.uuid, serviceAmount);
                    if (serviceBalance > 0) {
                        let koef = serviceBalance / periodAmount;
                        let amount = services.length - 1 > i ? koef * forDistribution : forDistribution - balance;
                        amount = this.round(amount);
                        
                        this.relations.push({
                            payment_uuid: payment.uuid,
                            service_uuid: service.uuid,
                            amount: amount,
                            id: id,
                            model: amount,
                            name: payment.name,
                            type: payment.type,
                        });
                        balance += amount;
                    }
                }
            },
            
            getServicesByUuid(uuid)
            {
                for (let i in this.account.balance) {
                    for (let j in this.account.balance[i].services) {
                        if (this.account.balance[i].services[j].uuid == uuid) return this.account.balance[i].services[j];
                    }
                }
            },
            
            changeProccessPayment(process, service)
            {
                let payment = this.payments.find(payment => payment.uuid == process.payment_uuid);
                let forDistribution = process.model;
                let relation = this.relations.find(relation => relation == process);
                let servicesToRecalculate = [];
                
                for (let i in this.relations) {
                    if (this.relations[i] != process && this.relations[i].id == process.id) {
                        servicesToRecalculate.push(this.getServicesByUuid(this.relations[i].service_uuid));
                        this.relations.splice(i, 1);
                    }
                }
                relation.amount = 0;
                
                let serviceBalance = this.getBalance(service.uuid, this.getServiceAmount(service));
                let paymentBalance = this.getBalance(payment.uuid, payment.amount);
                
                forDistribution = forDistribution > paymentBalance ? paymentBalance : forDistribution;
                forDistribution = forDistribution > serviceBalance ? serviceBalance : forDistribution;
                forDistribution = forDistribution < 0 ? 0 : forDistribution;
                
                relation.amount = forDistribution;
                relation.model = forDistribution;
                relation.name = payment.name;
                relation.type = payment.type;
                
                this.allocatePayment(servicesToRecalculate, payment, this.round(paymentBalance - forDistribution), process.id);
                
            },
            
            getProcess(service)
            {
                let serviceAmount = this.getServiceAmount(service);
                let relations = this.relations.filter(relation => relation.service_uuid == service.uuid).map(function(process){
                    if (process.type == 'aboveZero') serviceAmount += process.amount;
                    else serviceAmount -= process.amount;
                    process.serviceBalance = serviceAmount.toFixed(2) * 1;
                    return process;
                });
                                
                return relations;
            },
            
            getOrderedProcessByServices(services)
            {
                let ids = [];
                let result = [];
                
                for (let i in services) {
                    for (let j in this.relations) {
                        if (services[i].uuid == this.relations[j].service_uuid && ids.indexOf(this.relations[j].id) == -1) {
                            ids.push(this.relations[j].id);
                        }
                    }
                }
                
                for (let i in ids) {
                    let amount = 0;
                    this.relations.filter(process => process.id == ids[i]).forEach((process) => {amount += process.amount * 1; amount = amount.toFixed(2) * 1;});
                    result.push({
                        payment: this.payments.find(payment => payment.uuid == this.relations.find(process => process.id == ids[i]).payment_uuid),
                        id: ids[i],
                        amount: amount,
                    });
                }
                return result;
            },
            
            deleteRelations(id)
            {
                this.relations = this.relations.filter(process => process.id != id);
            },
            
            autoProccessPayment()
            {
                if (this.auto == 1) this.autoProccessPaymentFirst();
                else this.autoProccessPaymentSecond();
            },
            
            autoProccessPaymentFirst()
            {
                let isNeedToAutoProccessPaymentSecond = false;
                
                for (let i in this.account.balance) {
                    this.currentPeriodKey = i;
                    let period = this.account.balance[i];
                    for (let j in period.paid) {
                        let payment = period.paid[j];
                        payment.model = this.getBalance(payment.uuid, payment.amount);
                        this.proccessPayment(payment);
                        if (this.getBalance(payment.uuid, payment.amount) > 0) isNeedToAutoProccessPaymentSecond = true;
                    }
                }
                if (isNeedToAutoProccessPaymentSecond) this.autoProccessPaymentSecond(true);
                $('#collapseAutoButton').click();
                
            },
            
            autoProccessPaymentSecond(isRobo = false)
            {
                for (let i in this.payments) {
                    let payment = this.payments[i];
                    payment.model = this.getBalance(payment.uuid, payment.amount);
                    let paymentBalance = this.getBalance(payment.uuid, payment.amount);
                    
                    for (let j in this.account.balance) {
                        this.currentPeriodKey = j;
                        this.proccessPayment(payment);
                    }
                }
                if (! isRobo) $('#collapseAutoButton').click();
            },
            
            cancelRelations()
            {
                if (confirm('Вы уверены?')) this.relations = [];
            },
            
            saveRelations()
            {
                let data = this.relations.map(function(process){
                    return {
                        id: process.id,
                        payment_uuid: process.payment_uuid,
                        service_uuid: process.service_uuid,
                        amount: process.amount,
                        name: process.name,
                        type: process.type,
                    };
                });
                axios
                    .post(this.form.action, {
                        relations: data
                    })
                    .then(response => {
                        if (response.errors) this.error = response;
                        else this.success.push(response.data.result);
                    })
                    .catch(err => {
                        this.error.push(err);
                    });    
                ;
            },
        }
    }

</script>
