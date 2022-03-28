<template>
    <div class="container-fluid">
        <div class="col-sm-8 ml-auto mr-auto mt-5" style="background-color: white; padding: 30px 20px"
             v-if="Object.entries(details).length > 0">
            <a href="/">
                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> Back to search </button>
            </a>
            <hr>
            <h5>Trip Details</h5>
            <hr>
            <div class="row">
                <div class="col-sm-6">
                    <h5>{{details.pickup_date|myDatetime}}</h5>
                </div>
                <div class="col-sm-6">
                    <span class="float-right"><i class="fas fa-money-bill-alt" style="color: blue"></i> {{details.cost}} {{details.cost_unit}}</span>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <span style="font-size: 20px;"><i class="fas fa-map-marker-alt"
                                     style="color: #007d80"></i> {{details.pickup_location}}</span><br>
                        </div>
                        <div class="col-sm-6">
                            <span class="float-right" style="font-size: 16px;"><i class="fas fa-calendar-alt mr-2"
                                                         style="color: #007d80"></i>{{details.pickup_date|myDatetime}}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <span style="font-size: 20px;"><i class="fas fa-map-marker-alt"
                                     style="color: green"></i> {{details.dropoff_location}}</span><br>
                        </div>
                        <div class="col-sm-6">
                            <span class="float-right" style="font-size: 16px;"><i class="fas fa-calendar-alt mr-2"
                                                                                  style="color: green"></i>{{details.dropoff_date|myDatetime}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <img :src="details.car_pic" alt="car_pic" style="width: 200px; border-radius: 3px"><br>
                            <span>{{details.car_make}} {{details.car_model}}</span><br>
                            <span>Number: {{details.car_number}}</span><br>
                            <span>Year: {{details.car_year}}</span><br>
                        </div>
                        <div class="col-sm-4">
                            <span>Distance:   {{details.distance}} {{details.distance_unit}}</span><br>
                            <span>Duration:   {{details.duration}} {{details.duration_unit}}</span><br>
                            <span>Sub Total: {{details.cost}}  {{details.cost_unit}}</span><br>
                        </div>
                        <div class="col-sm-4">
                            <span>{{details.driver_name}}</span><br>
                            <img :src="details.driver_pic" alt="driver_pic" style="width: 200px; border-radius:  3px;"><br>
                            <star-rating v-bind:increment="0.5" :read-only="true"
                                         :rating="details.driver_rating" v-bind:star-size="30"></star-rating>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="vld-parent">
                <loading name="loader" :active.sync="isLoading"
                         :can-cancel="false"
                         :is-full-page="fullPage"></loading>
            </div>
            <div>
                <GmapMap
                :center="{lat:1.26, lng:36.8}"
                :zoom="12"
                map-type-id="roadmap"
                style="width: 940px; height: 400px"
                 ref="mapRef"
                >
                <GmapMarker
               :key="index"
               v-for="(m, index) in markers"
               :position="m.position"
               :clickable="true"
               :draggable="true"
               @click="center=m.position"
                     />
                </GmapMap>
            </div>
            
        </div>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
   
  
    export default {
        name: "TripDetails",
        components: {
            Loading,
        },
        data() {
            return {
                tripId: this.$route.params.tripId,
                details: {},
                isLoading: false,
                fullPage: true,
        
            }
        },
        methods: {
            tripDetails() {
                this.isLoading = true;
                axios.get("/api/details/" + this.tripId).then(({data}) => {
                    this.details = data;
                    this.isLoading = false;
                });
            }
        },
         mounted () {
              // At this point, the child GmapMap has been mounted, but
                 // its map has not been initialized.
                 // Therefore we need to write mapRef.$mapPromise.then(() => ...)
 
             this.$refs.mapRef.$mapPromise.then((map) => {
              map.panTo({lat: details.pickup_lat, lng: details.pickup_lng})
          })
         },
        created() {
            this.tripDetails();
        }
    }
</script>

<style scoped>
    #map {
        width: 100%;
        height: 500px;
    }
</style>