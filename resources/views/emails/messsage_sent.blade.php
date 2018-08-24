@extends('beautymail::templates.widgets')

@section('content')

	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>Dear {{$name}}</strong></h4>
		<p>Thank you for getting in touch with us.</p>
        <p>We appreciate your feedback. We strive to offer the best Live Streaming & On-Demand Platform the public has ever seen.</p>
        <p>Please wait at least 24 - 48 hours for a response from our team</p>

	@include('beautymail::templates.widgets.articleEnd')
		<h4 class="secondary"><strong>Your Query</strong></h4>
		<p>{{$user_query}}</p>
	@include('beautymail::templates.widgets.newfeatureStart')
		<p>We have received your query and the support team shall be in touch with you between 24-48 Hours.</p>
		<p>If you still haven't heard from us please contact info@paperclipsa.co.za directly</p>
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