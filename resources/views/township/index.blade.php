@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Townships")}}</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{route('schedules.index')}}">{{ __("Schedules")}}</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        @if(session('successMsg') != NULL)
          <div class="alert alert-success alert-dismissible fade show myalert" role="alert">
              <strong> ✅ SUCCESS!</strong>
              {{ session('successMsg') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
        @endif
        <div class="tile">
          


          <div class="bs-component">
            
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Yangon</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Gate</a>
              </li>
              
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                {{-- start here --}}
                 <div class="row">
                    <div class="col-md-12">
                      {{-- @if(session('successMsg') != NULL)
                        <div class="alert alert-success alert-dismissible fade show myalert" role="alert">
                            <strong> ✅ SUCCESS!</strong>
                            {{ session('successMsg') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                      @endif --}}
                      <div class="tile">
                        <h3 class="tile-title d-inline-block">{{ __("Township List")}}</h3>
                        <a href="{{route('townships.create')}}" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus" aria-hidden="true"></i> {{ __("Add New")}}</a>
                        <div class="table-responsive">
                          <table class="table dataTable">
                            <thead>
                              <tr>
                                <th>{{ __("#")}}</th>
                                <th>{{ __("Name")}}</th>
                               {{--  <th>{{ __("City")}}</th> --}}
                                <th>{{ __("Delivery Fees")}}</th>
                                <th>{{ __("Actions")}}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php $i=1; @endphp
                               @foreach($townships as $row)
                              <tr>
                                <td>{{$i++}}</td>
                                <td>{{$row->name}}</td>
                                {{-- <td>{{$row->city->name}}</td> --}}
                                <td>{{number_format($row->delivery_fees)}} Ks</td>
                                <td>
                                  <a href="{{route('townships.edit',$row->id)}}" class="btn btn-sm btn-warning">{{ __("Edit")}}</a>
                                  <form action="{{ route('townships.destroy',$row->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">

                                    @csrf
                                    @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger">{{ __("Delete")}}</button>
                                </form>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                {{-- end here --}}
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                {{-- start here --}}
                 <div class="row">
                    <div class="col-md-12">
                      {{-- @if(session('successMsg') != NULL)
                        <div class="alert alert-success alert-dismissible fade show myalert" role="alert">
                            <strong> ✅ SUCCESS!</strong>
                            {{ session('successMsg') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                      @endif --}}
                      <div class="tile">
                        <h3 class="tile-title d-inline-block">{{ __("Township Gate List")}}</h3>
                        <a href="{{route('gatetownships.create')}}" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus" aria-hidden="true"></i> {{ __("Add New")}}</a>
                        <div class="table-responsive">
                          <table class="table dataTable">
                            <thead>
                              <tr>
                                <th>{{ __("#")}}</th>
                                <th>{{ __("Name")}}</th>
                               {{--  <th>{{ __("City")}}</th> --}}
                                <th>{{ __("Sender Gate")}}</th>
                                <th>{{ __("Actions")}}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php $i=1; @endphp
                               @foreach($gatetownships as $row)
                              <tr>
                                <td>{{$i++}}</td>
                                <td>{{$row->name}}</td>
                                {{-- <td>{{$row->city->name}}</td> --}}
                                <td>{{($row->SenderGate->name)}} </td>
                                <td>
                                  <a href="{{route('gatetownships.edit',$row->id)}}" class="btn btn-sm btn-warning">{{ __("Edit")}}</a>
                                  <form action="{{ route('gatetownships.destroy',$row->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">

                                    @csrf
                                    @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger">{{ __("Delete")}}</button>
                                </form>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                {{-- end here --}}
              </div>
              
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection 
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    //alert("ok");
    setTimeout(function(){ $('.myalert').hide(); showDiv2() },3000);
  })
  
</script>
@endsection