<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Registration</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index2.html"><b>Forgot Password</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <!--<p class="login-box-msg"></p>-->
        <form name="frmLogin" action="{{ route('forgot-password-check')}}" method="post">
          {{ csrf_field() }}
        
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email">
          
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
              <button type="submit" name="btnLogin" class="btn btn-primary btn-block">Submit</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js" integrity="sha512-Y+cHVeYzi7pamIOGBwYHrynWWTKImI9G78i53+azDb1uPmU1Dz9/r2BLxGXWgOC7FhwAGsy3/9YpNYaoBy7Kzg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var _login = null;
        $('form[name="frmLogin"]').submit(function(event){
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData(this);
            var flag = 0;
           if(flag == 0)
           {
              _login =  $.ajax({
                  url : post_url,
                  type: request_method,
                  data : form_data,
                  contentType: false,
                  cache: false,
                  processData:false,
                  dataType:'json',
                  beforeSend:function(response)
                  {
                    if(_login != null){
                      return false;
                    }
                    $('button[name="btnLogin"]').html('<i class="fa fa-spin fa-spinner"></i>');
                  },
                  success:function(response)
                  {
                    $('button[name="btnLogin"]').html('LOGIN');
                    _login = null;
                    if(response.status == "success")
                    {
                      $.toast({
                          heading: 'Success',
                          text: [response.userMsg],
                          icon: 'success',
                          position: 'top-right',
                          hideAfter: 5000,
                      });
                        document.location.href = "{{route('dashboard')}}";
                      
                    }
                    else
                    {
                      if(response.userMsg!='')
                      {
                        $.toast({
                            heading: 'Error',
                            text: [response.userMsg],
                            icon: 'error',
                            position: 'top-right',
                            hideAfter: 5000,
                        })
                      }
                      if(response.errors != '')
                      {
                          var errorsHtml = '';
                          $.each(response.errors, function( key, value ) {
                              errorsHtml += value+"<br>";
                          });
                          $.toast({
                              heading: 'Errors',
                              text: [errorsHtml],
                              icon: 'error',
                              position: 'top-right',
                              hideAfter: 5000,
                          })
                      }
                    }
                  },
                  error:function(response)
                  {
                    $('button[name="btnLogin"]').html('LOGIN');
                    $.toast({
                        heading: 'Errors',
                        text: 'We are facing error in script.',
                        icon: 'error',
                        position: 'top-right',
                        hideAfter: 5000,
                    })
                    _login = null;
                  }
              });
           }
        });
    });
</script>
</body>
</html>
