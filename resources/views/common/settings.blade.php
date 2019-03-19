@extends('common.dash')

@section('content')

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Profile Settings</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

				  <form class="form-horizontal" role="form" method="POST" action="/common/edit-settings">
                            {!! csrf_field() !!}
            
					<div class="col-sm-12 col-md-12">
						<div class="form-group">
							<label>Email</label>
							<div class="input-group">
								<input type="text" class="form-control" name="email" value="{{ Auth::user()->email }}">
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 col-md-12">
						<div class="form-group">
							<label>Password</label>
							<div class="input-group">
								<input type="password" name="password" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 col-md-12">
						<div class="form-group">
							<label>Confirm Password</label>
							<div class="input-group">
								<input type="password" name="password_2" autocomplete="off" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 col-md-12">
						<div class="form-group">
							<input type="submit" class="btn btn-primary" value="Save">
						</div>
					</div>
					
					</form>

                  </div>
                </div>
              </div>
			  

@endsection