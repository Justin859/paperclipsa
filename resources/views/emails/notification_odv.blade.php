@extends('beautymail::templates.widgets')
<style scoped>

        .button-success,
        .button-error,
        .button-warning,
        .button-secondary {
            color: white;
            border-radius: 4px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        }

        .button-success {
            background: rgb(28, 184, 65); /* this is a green */
            padding: 5px;
        }

        .button-error {
            background: rgb(202, 60, 60); /* this is a maroon */
        }

        .button-warning {
            background: rgb(223, 117, 20); /* this is an orange */
            padding: 5px;
        }

        .button-secondary {
            background: rgb(66, 184, 221); /* this is a light blue */
        }

        a {
            text-decoration: none;
        }

    </style>
@section('content')

	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>Hello {{$name}}</strong></h4>
		<p>{{$main_message}}</p>
        <a href="{{$url_link}}" class="button-success">Watch Now</a>
        <br />
        <p>Before watching the video you will need a minimum of 5 credits in your account to purchase access to view.</p>
        <a href="http://www.paperclipsa.co.za/user-profile/buy-credit" class="button-warning">Buy Credit</a>
        
	@include('beautymail::templates.widgets.articleEnd')

	@include('beautymail::templates.widgets.newfeatureStart')
        <p>You are receiving this notification because it is enabled in your profile.</p>
        <p>To disable this notification cancel it from your profile <a href="http://www.paperclipsa.co.za/user-profile">here</a></p>
    @include('beautymail::templates.widgets.newfeatureEnd')

    @include('beautymail::templates.widgets.articleStart')
		<h5>We have a variety of content to offer you:</h5>

		<ul>
			<li>See all our content that is <a href="http://www.paperclipsa.co.za/live-now">Live-Now</a></li>
			<li>Check out the various <a href="http://www.paperclipsa.co.za/channels">CHANNELS</a> on our website</li>
			<li>Purchase Credits or take a look at our Cost-Effective Monthly Subscriptions when you <a href="http://www.paperclipsa.co.za/user-profile/buy-credit">LOGIN</a> to your profile</li>
		</ul>
		<p>If you have any questions or queries, please do not hesitate to communicate with us via our <a href="http://www.paperclipsa.co.za/contact">CONTACT</a> form.</p>
		<p>The PaperclipSA Team wishes you many happy viewing hours at www.paperclipsa.co.za</p>
	@include('beautymail::templates.widgets.articleEnd')
@stop