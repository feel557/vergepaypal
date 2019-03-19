@extends('common.dash')

@section('content')

<div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12">
			<div class="x_panel">
                 <div class="x_title">
					<h2>Add user</h2>
					<div class="clearfix"></div>
				</div>
                <div class="x_content">
				
			
					@if ($errors->has('email'))
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						{{ $errors->first('email') }}
					  </div>
					@endif
			
					@if ($errors->has('success'))
						<div class="alert alert-success alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						{{ $errors->first('success') }}
					  </div>
					@endif
			
			
			
				
				
					<form action="/common/user-add" method="post" enctype="multipart/form-data">
				
					{{ csrf_field() }}
				
					<div class="col-sm-12 col-md-12">
						<div class="form-group">
							<label>Email* (required)</label>
							<input type="text" class="form-control" name="email" placeholder="email">
						   
						</div>
					</div>
					<div class="col-sm-12 col-md-12">
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" name="name" placeholder="Name">
						   
						</div>
					</div>
					
					
					<div class="form-group col-sm-12 col-md-12">
						<input type="submit" class="btn btn-primary waves-effect waves-light" value="Invite"/>
					</div>

				</form>
				
			
				</div>
			</div>
		</div>

</div>

@endsection

@section('footer')



@endsection