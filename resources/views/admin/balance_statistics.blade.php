@extends('layouts.app')

@section('styles')

@include('includes.user_profile_styles')
<style>
    h2, p
    {
        color: #ffffff;
    }
</style>
@endsection

@section('content')

<div class="container">

    <div class="row">
    <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2 class="pt-2"> Venue Balance Statistics. </h2><hr />
        <div class="row mb-5">
            <div class="col-12 col-md-6 m-0 p-0">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
            <div class="col-12 col-md-6 m-0 p-0">
                <canvas id="vodPurchases" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/chart.js/dist/Chart.min.js')}}"></script>

 <script>
    $(document).ready(function()
    {
        var data_paid = "<?php echo($credit_invoices_paid) ?>";
        var data_owed = "<?php echo($credit_invoices_owed) ?>";

        var ctx = $("#myChart");
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Paid", "Owed"],
                datasets: [{
                    label: '# of Credits',
                    data: [data_paid, data_owed],
                    backgroundColor: [
                        'rgba(191, 63, 63, 0.2)',
                        'rgba(191, 127, 63, 0.2)'
                    ],
                    borderColor: [
                        'rgba(191, 63, 63,1)',
                        'rgba(191, 127, 63, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                title: {
                        display: true,
                        text: 'Credits Owed to Paperclip SA'
                    },
                
            }
        });

        var jan_amount = "<?php echo($statistics_jan) ?>";
        var feb_amount = "<?php echo($statistics_feb) ?>";
        var mar_amount = "<?php echo($statistics_mar) ?>";
        var apr_amount = "<?php echo($statistics_apr) ?>";
        var may_amount = "<?php echo($statistics_may) ?>";
        var jun_amount = "<?php echo($statistics_jun) ?>";
        var jul_amount = "<?php echo($statistics_jul) ?>";
        var aug_amount = "<?php echo($statistics_aug) ?>";
        var sep_amount = "<?php echo($statistics_sep) ?>";
        var oct_amount = "<?php echo($statistics_oct) ?>";
        var nov_amount = "<?php echo($statistics_nov) ?>";
        var dec_amount = "<?php echo($statistics_dec) ?>";

        var ctx_vod = $("#vodPurchases");
        var vodPurchases = new Chart(ctx_vod, {
            type: 'bar',
            data: {
                labels: ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
                datasets: [{
                    label: '# of Credits',
                    data: [jan_amount *0.1,feb_amount *0.1,mar_amount *0.1,apr_amount *0.1,may_amount *0.1,jun_amount *0.1,jul_amount *0.1,aug_amount *0.1,sep_amount *0.1,oct_amount *0.1,nov_amount *0.1,dec_amount *0.1],
                    backgroundColor: [
                        'rgba(191, 63, 63, 0.2)',
                        'rgba(191, 127, 63, 0.2)',
                        'rgba(191, 191, 63, 0.2)',
                        'rgba(127, 191, 63, 0.2)',
                        'rgba(63, 191, 63, 0.2)',
                        'rgba(63, 191, 127, 0.2)',
                        'rgba(63, 191, 191, 0.2)',
                        'rgba(63, 127, 191, 0.2)',
                        'rgba(63, 63, 191, 0.2)',
                        'rgba(127, 63, 191, 0.2)',
                        'rgba(191, 63, 191, 0.2)',
                        'rgba(191, 63, 127, 0.2)'

                    ],
                    borderColor: [
                        'rgba(191, 63, 63, 1)',
                        'rgba(191, 127, 63, 1)',
                        'rgba(191, 191, 63, 1)',
                        'rgba(127, 191, 63, 1)',
                        'rgba(63, 191, 63, 1)',
                        'rgba(63, 191, 127, 1)',
                        'rgba(63, 191, 191, 1)',
                        'rgba(63, 127, 191, 1)',
                        'rgba(63, 63, 191, 1)',
                        'rgba(127, 63, 191, 1)',
                        'rgba(191, 63, 191, 1)',
                        'rgba(191, 63, 127, 1)'


                    ],
                    borderWidth: 1
                }]
            },
            options: {
                title: {
                        display: true,
                        text: 'Video on-demand purchase value'
                    },
                    scales: {
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'credits (rands)'
                            }
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'month'
                            }
                        }]
                    }
            },

        });

        });
 </script>
@endsection
