<template>
    <div class="container-fluid mt-4">
        <div class="row" v-if="!isSearch">
            <div class="col-sm-6 ml-auto mr-auto mt-2" style="background-color:#c4c0be; padding: 12px;">
                <h2 class="text-center"><b>Trip Search</b></h2>
                <hr style="height:2px; width:50%; border-width:0; color:black; background-color:black">
                <form @submit.prevent="getResult">
                    <div class="form-group">
                        <label>Keyword</label>
                        <input v-model="form.keyword" type="text" class="form-control" name="keyword"
                               placeholder="Keyword"
                               :class="{ 'is-invalid': form.errors.has('keyword') }">
                        <has-error :form="form" field="keyword"></has-error>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" v-model="form.canceled" value="1">
                        <label class="form-check-label">Include canceled trip</label>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <h5>Distance</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.distance"
                                       name="exampleRadios" value="0"
                                       :class="{ 'is-invalid': form.errors.has('distance') }">
                                <label class="form-check-label">
                                    Any
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.distance"
                                       name="exampleRadios" value="1"
                                       :class="{ 'is-invalid': form.errors.has('distance') }">
                                <label class="form-check-label">
                                    Under 3 Km
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.distance"
                                       name="exampleRadios" value="2"
                                       :class="{ 'is-invalid': form.errors.has('distance') }">
                                <label class="form-check-label">
                                    3 to 8 Km
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.distance"
                                       name="exampleRadios" value="3" :class="{ 'is-invalid': form.errors.has('distance') }">
                                <label class="form-check-label">
                                    8 to 15 Km
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.distance"
                                       name="exampleRadios" value="4"
                                       :class="{ 'is-invalid': form.errors.has('distance') }">
                                <label class="form-check-label">
                                    More than 15 Km
                                </label>
                                <has-error :form="form" field="distance"></has-error>
                            </div>
                        </div>
                        <div class="col">
                            <h5>Time</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.time" name="exampleRadios"
                                       value="0" :class="{ 'is-invalid': form.errors.has('time') }">
                                <label class="form-check-label">
                                    Any
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.time" name="exampleRadios"
                                       value="1" :class="{ 'is-invalid': form.errors.has('time') }">
                                <label class="form-check-label">
                                    Under 5 min
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.time" name="exampleRadios"
                                       value="2" :class="{ 'is-invalid': form.errors.has('time') }">
                                <label class="form-check-label">
                                    5 to 10 min
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.time" name="exampleRadios"
                                       value="3" :class="{ 'is-invalid': form.errors.has('time') }">
                                <label class="form-check-label">
                                    10 t0 20 min
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" v-model="form.time" name="exampleRadios"
                                       value="4" :class="{ 'is-invalid': form.errors.has('time') }">
                                <label class="form-check-label">
                                    More than 20 min
                                </label>
                                <has-error :form="form" field="time"></has-error>
                            </div>
                        </div>
                    </div>
                    <hr style="height:2px; width:50%; border-width:0; color:black; background-color:black">
                    <div class="form-group mt-3 text-center">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="vld-parent">
            <loading name="loader" :active.sync="isLoading"
                     :can-cancel="false"
                     :is-full-page="fullPage"></loading>
        </div>
        <div class="container-fluid mt-4" v-if="isSearch">
            <div class="row">
                <div class="col-sm-8 ml-auto mr-auto">
                    <a href="/">
                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back to search </button>
                    </a>
                    <hr>
                    <h5>Trips({{Object.keys(trips).length}})</h5>
                    <a :href="'tripdetails/' + trip.id" v-for="trip in trips" :key="trip.id">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title float-left" style="font-size: 15px">
                                    {{trip.pickup_date|myDatetime}}</h3>
                                <span class="float-right">{{trip.cost}} {{trip.cost_unit}}</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                         <h5 class="card-title"><i class="fas fa-circle"
                                     style="color: green"></i>&nbsp;{{trip.pickup_location}}</h5> 
                                      <p class="card-text" style="color: blue"><i class="fas fa-circle"
                                     style="color: red"></i>&nbsp;{{trip.dropoff_location}}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-12 rating">
                                            <star-rating class="float-right" v-bind:increment="0.5" :read-only="true"
                                                         :rating="trip.driver_rating" v-bind:star-size="30"
                                                         style="margin: 0"></star-rating>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 status">
                                        <p v-if="trip.status == 'COMPLETED'" class="lead float-right"
                                           style="color: green;">{{trip.status}}<i class="fas fa-check-circle ml-2"></i>
                                        </p>
                                        <p v-if="trip.status == 'CANCELED'" class="lead float-right"
                                           style="color: red;">{{trip.status}}<i class="fas fa-ban ml-2"></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        name: "landing",
        components:{
            Loading
        },
        data() {
            return {
                isLoading: false,
                fullPage: true,
                isSearch: false,
                trips: {},
                form: new Form({
                    keyword: '',
                    canceled: false,
                    distance: '',
                    time: ''
                })
            }
        },
        methods: {
            getResult() {
                this.isSearch = true;
                this.isLoading = true;
                this.form.post('/api/search')
                    .then(({data}) => {
                        this.trips = data;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        this.isSearch = false;
                        this.errors = error.response.data.errors;
                        Swal.fire({
                            type: 'error',
                            title: 'Error!!',
                            text: error.response.data.msg,

                        })
                    })
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
