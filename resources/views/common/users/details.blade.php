@extends('common.dash')

@section('content')

<div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12">
			<div class="x_panel">
                 <div class="x_title">
					<h2>User <? if(isset($data[0])){ echo $data[0]->username." / ".$data[0]->email; } ?></h2>
				<a href="/common/user-new-invite?id=<?= $data[0]->id ?>" class="btn btn-primary pull-right">Send new password via email</a>
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
			
				
					
					
					<form action="/common/user-edit" method="post" enctype="multipart/form-data">
					
						{{ csrf_field() }}
					
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label>Email* (required)</label>
								<input type="text" class="form-control" name="email" placeholder="email" value="<?= $data[0]->email ?>">
							   
							</div>
						</div>
						
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label>Name</label>
								<input type="text" class="form-control" name="name" placeholder="Name" value="<?= $data[0]->username ?>">
							   
							</div>
						</div>
						
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label>Phone</label>
								<input type="text" class="form-control" name="phone" placeholder="phone" value="<?= $data[0]->phone ?>">
							</div>
						</div>
						
						<div class="form-group col-sm-12 col-md-12">
							<input type="hidden" name="id" value="<? if(isset($data[0])){echo $data[0]->id;}?>"/>
							<input type="submit" class="btn btn-primary waves-effect waves-light" value="Save"/>
						</div>

					</form>
					
			
			
				</div>
			</div>
		</div>

</div>

@endsection

@section('footer')



@endsection