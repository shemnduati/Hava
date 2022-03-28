<template>
    <div class="container-fluid mt-4">
          <div class="container-fluid mt-4">
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
                                     style="color: #007d80"></i>{{trip.pickup_location}}</h5> 
                                      <p class="card-text" style="color: blue"><i class="far fa-circle"
                                     style="color: #007d80"></i>{{trip.dropoff_location}}</p>
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