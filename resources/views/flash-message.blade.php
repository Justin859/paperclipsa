@if ( Session::has('success') )

	<div class="alert alert-success alert-dismissible fade show" role="alert">
	<strong>SUCCESS!</strong> {{ Session::get('success') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	</div>	
  
@endif

@if ( Session::has('error') )

	<div class="alert alert-danger alert-dismissible fade show" role="alert">
	<strong>ERROR!</strong> {{ Session::get('error') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	</div>		

@endif

@if ( Session::has('warning'))

	<div class="alert alert-warning alert-dismissible fade show" role="alert">
	<strong>WARNING!</strong> {{ Session::get('warning') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	</div>		

@endif
