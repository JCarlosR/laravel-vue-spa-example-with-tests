<template>
    <card :title="$t('card_title_history')">
        <div class="row">
            <div class="col-md-3">
                <select class="form-control" v-model="filterType">
                    <option value="sevenDays" selected>Last 7 days</option>
                    <option value="thisMonth">This month</option>
                    <option value="custom">Custom range</option>
                </select>        
            </div>
            
            <div class="col-md-2">
                <v-button native-type="button" type="success" small title="Search" 
                          @click.native="fetchDates" :loading="loadingDates">
                    <fa icon="search" fixed-width/>
                </v-button>
                <v-button native-type="button" small title="Export results" 
                          @click.native="exportFile" :loading="loadingDates">
                    <fa icon="file-export" fixed-width/>
                </v-button>
            </div>

            <div v-if="filterType === 'custom'" class="col-md-8">
                <form class="form-inline mt-2">
                    <label class="my-1 mr-2" for="fromDate">From</label>
                    <input type="date" class="form-control my-1 mr-sm-2" id="fromDate" v-model="dateFrom">

                    <label class="my-1 mr-2" for="toDate">To</label>
                    <input type="date" class="form-control my-1 mr-sm-2" id="toDate" v-model="dateTo">
                </form>
            </div>    
        </div>    
        
        <p v-if="!user.working_hours">
            <strong>Note:</strong>
            You have not configured your preferred working hours per day yet.
        </p>
        
        <table class="table table-bordered mt-2">
            <thead>
            <tr>
                <th>Date</th>
                <th># Tasks</th>
                <th>Total minutes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="taskDate in  taskDates" :class="taskDate.trClass">
                <td>{{ taskDate.date }}</td>
                <td>{{ taskDate.count }}</td>
                <td>{{ taskDate.totalMinutes }}</td>
                <td>
                    <router-link :to="{ name: 'tasks.date', params: {date: taskDate.date} }" class="btn btn-secondary btn-sm">
                        <fa icon="info" fixed-width/>
                    </router-link>             
                </td>
            </tr>
            </tbody>
        </table>
    </card>
</template>

<script>
    import axios from "axios";
    import VButton from "../../../components/Button";
    import {mapGetters} from "vuex";

    export default {
        components: {VButton},
        
        data() {
            return {
                filterType: 'sevenDays',
                dateFrom: '',
                dateTo: '',
                loadingDates: true,
                taskDates: [/*
                    {
                        date: '2020-06-29',
                        count: 3,
                        totalMinutes: 120
                    },*/
                ]
            };
        },
        
        middleware: 'auth',

        metaInfo() {
            return {
                title: this.$t('history')
            };
        },

        computed: mapGetters({
            user: 'auth/user'
        }),
        
        async mounted() {
            await this.fetchDates();
            
            // set initial values
            if (!this.dateFrom && !this.dateTo && this.taskDates.length > 0) {
                this.dateFrom = this.taskDates[this.taskDates.length -1].date;
                this.dateTo = this.taskDates[0].date;
            }
        },
        
        methods: {
            async fetchDates() {
                this.loadingDates = true;

                let url = `/api/dates?filterType=${this.filterType}`;
                
                if (this.filterType === 'custom') {
                    // since we show in desc order
                    url += `&from=${this.dateFrom}&to=${this.dateTo}`;     
                }
                
                const {data} = await axios.get(url);
                
                // determine whether the user achieved his/her goal or not
                this.taskDates = data.map((date) => {                    
                    date.trClass = 
                        (date.totalMinutes >= this.user.working_hours * 60) 
                            ? 'table-success' 
                            : 'table-danger';
                    
                    return date;
                });
                
                this.loadingDates = false;
            },
            
            exportFile() {
                const url = `/api/export?from=${this.dateFrom}&to=${this.dateTo}&format=html`;
                window.open(url,'_blank');
            }
        }
    }
</script>
