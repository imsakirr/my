@extends('main');
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-3"></div>
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">              
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" name="frmGeneral" action="{{ route('user-store')}}">
                  {{ csrf_field() }}
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                  </div>
                    
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                  </div>
                    
                  <div class="form-group">
                    <label for="exampleInputEmail1">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                  </div>  
                  <div class="form-group">
                    <label for="exampleInputPassword1">Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}">{{$role}}</option>
                        @endforeach
                    </select>
                  </div>  
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
          <!-- right column -->
          
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection  
@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        var _form = null;
        $('form[name="frmGeneral"]').submit(function(event){
            event.preventDefault();
            var post_url = $(this).attr("action");
            var request_method = $(this).attr("method");
            var form_data = new FormData(this);
            var flag = 0;
           if(flag == 0)
           {
              _form =  $.ajax({
                  url : post_url,
                  type: request_method,
                  data : form_data,
                  contentType: false,
                  cache: false,
                  processData:false,
                  dataType:'json',
                  beforeSend:function(response)
                  {
                    if(_form != null){
                      return false;
                    }
                    $('button[name="btnSubmit"]').html('<i class="fa fa-spin fa-spinner"></i>');
                  },
                  success:function(response)
                  {
                    $('button[name="btnSubmit"]').html('LOGIN');
                    _form = null;
                    if(response.status == "success")
                    {
                      $.toast({
                          heading: 'Success',
                          text: [response.userMsg],
                          icon: 'success',
                          position: 'top-right',
                          hideAfter: 5000,
                      });
                        if(response.redirect != ''){
                            document.location.href = response.redirect;
                        }
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
                    $('button[name="btnSubmit"]').html('LOGIN');
                    $.toast({
                        heading: 'Errors',
                        text: 'We are facing error in script.',
                        icon: 'error',
                        position: 'top-right',
                        hideAfter: 5000,
                    })
                    _form = null;
                  }
              });
           }
        });
    });
</script>
@endsection  
