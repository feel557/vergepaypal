@extends('member.dash')

@section('content')

	<div class="section">
        <div class="row">
          <div class="column xlarge-4 large-5 medium-7 xmedium-6 medium-centered">
            <div class="section box-shadow box-padding">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/password-reset') }}">
					<div class="form-header"> 
					  <h3 class="light text-center">Reset Password</h3>
					</div>
					<div class="form-body">
					@if(count($errors)>0)
						  <div style="padding-bottom:20px;color:red;">{{ $errors->first() }}</div>
					@endif
                      <input type="hidden" name="token" value="{{ $token }}">
                      {!! csrf_field() !!}
                    <input type="password" name="password" placeholder="New Password">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password">
                  </div>
                  <div class="form-footer">
                    <button class="button expanded" type="submit">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

@endsection