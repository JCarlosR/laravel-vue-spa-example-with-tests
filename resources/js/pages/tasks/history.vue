<template>
    <card :title="$t('card_title_history')">
        <div class="row mb-2">
            <div class="col-md-3">
                <select class="form-control" v-model="filterType">
                    <option value="sevenDays" selected>Last 7 days</option>
                    <option value="thisMonth">This month</option>
                    <option value="custom">Custom range</option>
                </select>        
            </div>
            <div class="col-md-8">
                <form v-if="filterType === 'custom'" class="form-inline">
                    <label class="my-1 mr-2" for="fromDate">From</label>
                    <input type="date" class="form-control my-1 mr-sm-2" id="fromDate" v-model="dateFrom">

                    <label class="my-1 mr-2" for="toDate">To</label>
                    <input type="date" class="form-control my-1 mr-sm-2" id="toDate" v-model="dateTo">
                </form>
            </div>
            <div class="col-md-1">
                <a href="" class="btn btn-primary btn-sm">
                    <fa icon="file-export" fixed-width/>
                </a>
            </div>
        </div>        
        
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Date</th>
                <th>#</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="taskDate in  taskDates">
                <td>{{ taskDate.date }}</td>
                <td>{{ taskDate.count }}</td>
                <td>
                    <a :href="taskDate.id" class="btn btn-secondary btn-sm">
                        <fa icon="info" fixed-width/>
                    </a>             
                </td>
            </tr>
            </tbody>
        </table>
    </card>
</template>

<script>
    export default {
        components: {},
        data() {
            return {
                filterType: 'sevenDays',
                dateFrom: '2020-07-17',
                dateTo: '2020-08-17',
                taskDates: [
                    {
                        id: 1,
                        date: '2020-07-17',
                        count: 3
                    },
                    {
                        id: 2,
                        date: '2020-07-16',
                        count: 5
                    }
                ]
            };
        },
        middleware: 'auth',

        metaInfo() {
            return {
                title: this.$t('history')
            };
        }
    }
</script>
