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

    </style>
@section('content')

	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>Click the link below to verify your email address</strong></h4>
        <a href="http://paperclipsa.local/verify-email/123/123" class="button-success">Complete Verification</a>
	@include('beautymail::templates.widgets.articleEnd')


	@include('beautymail::templates.widgets.newfeatureStart')

		<p>If you did not request to verify your email address you can igore this email.</p>

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