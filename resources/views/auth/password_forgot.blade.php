@extends('member.dash')

@section('content')

 <div class="section">
        <div class="row">
          <div class="column xlarge-4 large-5 medium-7 xmedium-6 medium-centered">
            <div class="section box-shadow box-padding">
              <form method="POST" action="{{ url('/auth/password-forgot') }}">
				{!! csrf_field() !!}
                <div class="form-header"> 
                  <h3 class="light text-center">Forgotten Password</h3>
                </div>
                <div class="form-body">
				
				@if(count($errors)>0)
						  <div style="padding-bottom:20px;color:red;">{{ $errors->first() }}</div>
					@endif
				
                  <input type="email" placeholder="Email" name="email" value="{{ $email or old('email') }}">
                </div>
                <div class="form-footer">
                  <button class="button expanded" type="submit">Reset</button>
                  <!-- mixin secure-group begin-->
                  <div class="secure-group-container">
                    <div class="secure-group"><img class="secure-group-img" src="/member/img/template/lock.png" alt="" width="12"/>
                      <h6 class="form-text-small">100% secure. Your e-mail stays only with us!</h6>
                    </div>
                  </div>
                  <!-- mixin secure-group end-->
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

@endsection