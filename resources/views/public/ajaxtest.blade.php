@extends('layouts.app')

@section('content')
      <div id = 'msg'>This message will be replaced using Ajax. 
         Click the button to replace the message.</div>
      <button class="somebutton">Get Message</button>
@endsection

@section('scripts')

      <script>
       $('.somebutton').click(function() { 
            $.ajax({
                type:'POST',
                url:'/api/getmsg',
                data_type: 'json',
                data:'_token = <?php echo csrf_token() ?>',
                success:function(data){
                    console.log("test");
                    $("#msg").html(data.msg);
                    
                },
                error:function(error) {
                    console.log(error);
                }
            });
        });
      </script>

@endsection