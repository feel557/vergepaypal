@extends('member.dash')

@section('content')

@if ($errors->has('msg'))
	<div class="section">
        <div class="row">
          <div class="column xmedium-11 xmedium-centered">
            
            <!-- mixin callouts begin-->
            <div class="callouts-container section">
			
              <!-- mixin callout begin-->
              <div class="callout box-white box-padding-sm section" data-closable="data-closable">
                <div class="row">
                  <div class="column"><img class="callout-close" src="/member/img/template/close.png" alt="" data-close="data-close">
                    <h4 class="callout-title">Notification</h4>
                    <div class="callout-body">
                      <p>{{ $errors->first('msg') }}</p>
					  
					  
					@if ($errors->has('msg_link'))
						<p><a href="{{ $errors->first('msg_link') }}">Resend activation email</a></p>
					@endif
					  
                    </div>
                  </div>
                </div>
              </div>
              <!-- mixin callout end-->
              
            </div>
            <!-- mixin callouts end-->
          </div>
        </div>
      </div>
@endif

@if ($errors->has('success'))
	<div class="section">
        <div class="row">
          <div class="column xmedium-11 xmedium-centered">
            
            <!-- mixin callouts begin-->
            <div class="callouts-container section">
			
              <!-- mixin callout begin-->
              <div class="callout box-white box-padding-sm section" data-closable="data-closable">
                <div class="row">
                  <div class="column"><img class="callout-close" src="/member/img/template/close.png" alt="" data-close="data-close">
                    <h4 class="callout-title">Successfull Email Verification!</h4>
                    <div class="callout-body">
                      <p>{{ $errors->first('success') }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <!-- mixin callout end-->
              
            </div>
            <!-- mixin callouts end-->
          </div>
        </div>
      </div>
@endif


      <div class="section">
        <div class="row">
          <div class="column xmedium-11 xmedium-centered">
            <div class="box-shadow box-padding section">
              <div class="row" data-equalizer data-equalize-on="medium">
                <div class="column medium-6">
                  <div class="row">
                    <div class="column xlarge-9 large-10 medium-11 medium-centered" data-equalizer-watch>
                      <div class="vertical-parent">
                        <div class="vertical-middle">
                          <form class="form-box section" role="form" method="POST" action="/auth/login">
                            {!! csrf_field() !!}
                            <div class="form-header"> 
                              <h3 class="light text-center">Login</h3>
							<?
						
						if ($errors->has('login')){
							
							echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'>".$errors->first('login')."</div>";
							
						}
						
						?>
							<?/*
								@if(count($errors)>0)
								@foreach ($errors->all() as $error)
								<div class='alert alert-danger alert-dismissible fade in' role='alert'>{{ $error }}</div>
								@endforeach
								@endif
							*/?>
								
								
						
                            </div>
                            <div class="form-body">
                              <input type="text" name="email" placeholder="Email">
                              <input type="password" name="password" placeholder="Password">
                              <div class="forgot-password"><a href="/auth/password-forgot">Forgot password?</a></div>
                            </div>
                            <div class="form-footer">
							
							<?
							
							$failedLoginCount = \Session::get('failedLoginCount');
							
							//var_dump($failedLoginCount);
		
							if(isset($failedLoginCount) && $failedLoginCount > 1){
							
							?>
							<div class="form-group col-md-6 col-sm-6 col-xs-12" style="padding:20px 0;">
								<div class="g-recaptcha" data-sitekey="6LcO4jYUAAAAAM1sjrA1o87ftlbY0RzxD4I3YXzd"></div>
							</div>
							<? } ?>
						
                            <button class="button expanded" type="submit">Login</button>
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
                </div>
                <div class="column medium-6">
                  <div class="form-divider-container">
                    <div class="row">
                      <div class="column xlarge-9 large-10 medium-11 medium-centered" data-equalizer-watch>
                        <div class="vertical-parent">
                          <div class="vertical-middle">
                            <form class="form-box section" role="form" method="POST" action="/auth/register">
                            {!! csrf_field() !!}
                              <div class="form-header">
                                <h3 class="light text-center">Create An Account</h3>
                              </div>
                              <div class="form-body">
									  
								@if ($errors->has('errors'))
								<div class="alert alert-danger alert-dismissible fade in" style="color:red;padding-bottom:20px;">
									{{ $errors->first('errors') }}
								</div>
								@endif
								
                                <input type="text" name="first_name" placeholder="First Name" value="<? if(isset($_GET['first_name'])){ echo $_GET['first_name']; }else{ ?>{{ old('first_name') }}<? } ?>">
                                <input type="text" name="last_name" placeholder="Last Name" value="<? if(isset($_GET['last_name'])){ echo $_GET['last_name']; }else{ ?>{{ old('last_name') }}<? } ?>">
                                <input type="email" name="email" placeholder="Email" value="<? if(isset($_GET['email'])){ echo $_GET['email']; }else{ ?>{{ old('email') }}<? } ?>">
								<input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                                <input type="password" name="password" placeholder="Password">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password">
								
                              </div>
                              <div class="checkbox">
                                <input id="checkbox-1" type="checkbox" name="agree" value="1" checked>
                                <label for="checkbox-1"><span class="form-text-small">I have read and agree to the <a href="#0"> Terms & Conditions</a></span></label>
                              </div>
							  <div class="checkbox">
                                <input id="checkbox-2" type="checkbox" name="accredited" value="1">
                                <label for="checkbox-2"><span class="form-text-small">I am an accredited investor <a href="javascript:void(0);" onclick="alert('verified ivestor info');">?</a></span></label>
                              </div>
                              <button class="button expanded" type="submit">Create</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-divider"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<?



// first save all emails for further promotion
		
		$email = Illuminate\Support\Facades\Input::get('email');
		
		if(isset($email) && $email != ""){

			$check_promo_email = \DB::table('__promotion_emails')->where('email', '=', $email)->get();
			
			if(count($check_promo_email)<1){
				
				\DB::table('__promotion_emails')->insert(array('email' => $email));
				
			}
			
		}







?>
@endsection
