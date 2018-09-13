@extends('layouts.app')

@section('styles')

@include('includes.user_profile_styles')
<style>
    h2, p
    {
        color: #ffffff;
    }

    .table {
        margin: auto;
    }

    td, th {
        vertical-align: middle !important;
    }

    .pagination
    {
        font-size: 24px;
    }
    .fa-step-forward, .fa-step-backward, .fa-fast-forward, .fa-fast-backward 
    {
        color: rgba(208, 0, 0);
    }

    .pagination-number
    {
        color: #FFFFFF !important;
        background-color: rgba(208, 0, 0) !important;
    }

    .pagination-skip, .pagination-number-active
    {
        background-color: #181818 !important;
    }
</style>
@endsection

@section('content')

<div class="container">

    <div class="row">
        
    <div class="col-12 col-md-9 order-md-2">
        <br />
        <h2 class="pt-2"> {{$venue->name}} <small style="color: #FFCC00">Analytics</small></h2><hr />
        <div class="row">
            <div class="col-12 col-md-6 m-0 p-0">
                <canvas id="vodPurchases" width="400" height="400"></canvas>
            </div>
            <div class="col-12 col-md-6 m-0 p-0">
                <canvas id="InvoicedChart" width="400" height="400"></canvas>
            </div>
        </div>
        <br />
        <table class="table table-bordered table-dark table-responsive-sm mb-5">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Month</th>
            <th scope="col">Invoices</th>
            <th scope="col">Profit to venue</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>January</td>
            <td>
                @if(!$invoiced_jan)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_jan}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_jan * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">2</th>
            <td>February</td>
            <td>
                @if(!$invoiced_feb)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_feb}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_feb * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">3</th>
            <td>March</td>
            <td>
                @if(!$invoiced_mar)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_mar}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_mar * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">4</th>
            <td>April</td>
            <td>
                @if(!$invoiced_apr)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_apr}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_apr * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">5</th>
            <td>May</td>
            <td>
                @if(!$invoiced_may)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_may}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_may * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">6</th>
            <td>June</td>
            <td>
                @if(!$invoiced_jun)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_jun}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_jun * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">7</th>
            <td>July</td>
            <td>
                @if(!$invoiced_jul)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_jul}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_jul * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">8</th>
            <td>August</td>
            <td>
                @if(!$invoiced_aug)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_aug}} worth of invoices pending</span>
                @endif
            </td>
            <td>{{$statistics_aug * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">9</th>
            <td>September</td>
            <td>
                @if(!$invoiced_sep)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_sep}} worth of invoices pending</span>
                @endif               
            </td>
            <td>{{$statistics_sep * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">10</th>
            <td>October</td>
            <td>
                @if(!$invoiced_oct)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_oct}} worth of invoices pending</span>
                @endif                
            </td>
            <td>{{$statistics_oct * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">11</th>
            <td>November</td>
            <td>
                @if(!$invoiced_nov)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_nov}} worth of invoices pending</span>
                @endif               
            </td>
            <td>{{$statistics_nov * 0.1}}</td>
            </tr>
            <tr>
            <th scope="row">12</th>
            <td>December</td>
            <td>
                @if(!$invoiced_dec)
                    <span class="text-success"><i class="fas fa-check-circle"></i></span>
                @else
                    <span class="text-danger"><i class="fas fa-times-circle"></i>&nbsp;&nbsp; R{{$invoiced_dec}} worth of invoices pending</span>
                @endif                
            </td>
            <td>{{$statistics_dec * 0.1}}</td>
            </tr>
        </tbody>
        </table>
        <h2 class="pt-2"> Confirm Venue Payment </h2><hr />
        <form action="/user-profile/superuser/confirm/venue-paid" method="post" class="mb-5" name="paymentUpdateForm">
        @csrf
        <input type="hidden" name="venue_id" value="{{$venue->id}}">
            <div class="form-row">
            <div class="col-12 col-md-4">
                <label for="monthInvoices">Month</label>
                <select name="month" id="monthInvoices" name="month" class="form-control">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label for="payment">Payment Status</label>
                <select name="payment_status" id="payment" class="form-control">
                    <option value="paid" selected="selected">Paid</option>
                    <option value="outstanding">Outstanding</option>
                </select>
            </div>
            <div class="col-12 col-md-4 mt-5">
                <button type="submit" class="btn btn-block btn-outline-info mt-2">Submit</button>
            </div>
                
            </div>

        </form>
    </div>
    
    @include('includes.user_side_panel')<!-- side panel -->
    </div>
</div>

@endsection

@section('modal')

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('node_modules/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('node_modules/chart.js/dist/Chart.min.js')}}"></script>
<script>
    $(document).ready(function() {
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
            type: 'line',
            data: {
                labels: ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
                datasets: [{
                    label: '# of Credits',
                    data: [jan_amount * 0.1,feb_amount * 0.1,mar_amount * 0.1,apr_amount * 0.1,may_amount * 0.1,jun_amount * 0.1,jul_amount * 0.1,aug_amount * 0.1,sep_amount * 0.1,oct_amount * 0.1,nov_amount * 0.1,dec_amount * 0.1],
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
                        'rgba(191, 63, 63,1)',
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
                                labelString: 'credits'
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
        var invoiced_jan = "<?php echo($invoiced_jan) ?>";
        var invoiced_feb = "<?php echo($invoiced_feb) ?>";
        var invoiced_mar = "<?php echo($invoiced_mar) ?>";
        var invoiced_apr = "<?php echo($invoiced_apr) ?>";
        var invoiced_may = "<?php echo($invoiced_may) ?>";
        var invoiced_jun = "<?php echo($invoiced_jun) ?>";
        var invoiced_jul = "<?php echo($invoiced_jul) ?>";
        var invoiced_aug = "<?php echo($invoiced_aug) ?>";
        var invoiced_sep = "<?php echo($invoiced_sep) ?>";
        var invoiced_oct = "<?php echo($invoiced_oct) ?>";
        var invoiced_nov = "<?php echo($invoiced_nov) ?>";
        var invoiced_dec = "<?php echo($invoiced_dec) ?>";

        var ctx_vod = $("#InvoicedChart");
        var vodPurchases = new Chart(ctx_vod, {
            type: 'horizontalBar',
            data: {
                labels: ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
                datasets: [{
                    label: '# of Credits',
                    data: [invoiced_jan,invoiced_feb,invoiced_mar,invoiced_apr,invoiced_may,invoiced_jun,invoiced_jul,invoiced_aug,invoiced_sep,invoiced_oct,invoiced_nov,invoiced_dec],
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
                        'rgba(191, 63, 63,1)',
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
                        text: 'Venue invoiced credits'
                    },
                    scales: {
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: 'credits'
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
