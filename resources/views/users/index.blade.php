@extends('main')
@section('css')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content plansList">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
            <div class="card">
                <h4 align="center"><div class="submit-loader1" style="display: none">
        <img  src="{{asset('img/spinner.gif')}}" alt="">
                    </div></h4>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Email Verified At</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($users as $user)
                      <tr>
                          <td>{{$user->name}}</td>
                          <td>{{$user->email}}</td>
                          <td>{{$user->email_verified_at}}</td>
                          <td>{{$user->RoleName}}</td>
                          <td><a href="{{ route('user-show',[hash('sha256',$user->id)])}}" class="btn btn-success btn-sm">Edit</a> <a href="{{ route('user-delete',[hash('sha256',$user->id)])}}" class="btn btn-danger btn-sm clsDeletePlan">Delete</a></td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection  
@section('js')
<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click','.clsDeletePlan',function (e){
            e.preventDefault();
            if(confirm("Are you sure! you want to delete?"))
            {
                var url = window.location.href;
                var link = $(this).prop('href');
                console.log(link);
                $('.submit-loader1').show();
                $.get(link).done( function(response, status){
                    if(status == "success")
                    {
                        location.reload();
//                        getArticles(url);
                    }
                });
            }
            else{
                return false;
            }
        });
    });
    function getArticles(url) {
      $('.submit-loader1').show();
        $.ajax({
            url : url  
        }).done(function (data, status)
        {
            $('.submit-loader1').hide();
            $('.plansList').html(data);
            if(status == "success")
            {

            }
        }).fail(function () {
            $('.submit-loader1').hide();
            $.toast({
              heading: 'Error',
              text: 'Something went wrong',
              showHideTransition: 'fade',
              icon: 'error',
              position: 'top-right',
              hideAfter: 3000,
            });
        });
    }
</script>
@endsection  
