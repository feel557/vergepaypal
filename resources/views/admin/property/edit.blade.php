@extends('common.dash')

@section('content')




        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
				
				@if(count($errors)>0)
				@foreach ($errors->all() as $error)
				<div class='alert alert-danger alert-dismissible fade in' role='alert'>{{ $error }}</div>
				@endforeach
				@endif
				
				
					<? if(isset($data[0])){ ?>
					
						<h2>Update/Edit Property</h2>
						<a href="/admin/property-details?id=<? if(isset($data[0])){ ?>{{ $data[0]->id }}<? } ?>" class="btn btn-primary pull-right"><< Back to Property Details</a>
						
						
					<? }else{ ?>
					
						<h2>Add new property</h2>
					
					<? } ?>
					<div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
					<form action="/admin/property-add-update" method="post" enctype="multipart/form-data">
					
						{{ csrf_field() }}
				
						<? if(isset($data[0])){ ?>
						<input type="hidden" name="id" value="{{ $data[0]->id }}">
						<?}?>
						
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<h3>Address details</h3>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Address</label>
								<input type="text" class="form-control" name="address" value="<? if(isset($data[0])){?>{{ Request::old('address') ? : $data[0]->address }}<?}?>" placeholder="address">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="city" value="<? if(isset($data[0])){?>{{ Request::old('city') ? : $data[0]->city }}<?}?>" placeholder="city">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>State</label>
								<input type="text" class="form-control" name="state" value="<? if(isset($data[0])){?>{{ Request::old('state') ? : $data[0]->state }}<?}?>" placeholder="state">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Zip</label>
								<input type="text" class="form-control" name="zip" value="<? if(isset($data[0])){?>{{ Request::old('zip') ? : $data[0]->zip }}<?}?>" placeholder="zip">
							</div>
						</div>
						
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label>Google Map Url <a href="https://www.google.com/maps/" target="_blank">(visit Google Maps)</a></label>
								<input type="text" class="form-control" name="map_url" value="<? if(isset($data[0])){?>{{ Request::old('map_url') ? : $data[0]->map_url }}<?}?>" placeholder="map_url">
							</div>
						</div>
						
						<? /* ----- */ ?>
						<p>&nbsp;</p>
                        <p>&nbsp;</p>
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<h3>Property Details</h3>
							</div>
						</div>
						
						<div class="col-sm-3 col-md-3">
							<div class="form-group">
								<label>Bedroom</label>
								<input type="text" class="form-control inpumask_integer" name="bedroom" value="<? if(isset($data[0])){?>{{ Request::old('bedroom') ? : $data[0]->bedroom }}<?}?>" placeholder="bedroom">
							</div>
						</div>
						
						<div class="col-sm-3 col-md-3">
							<div class="form-group">
								<label>Bathroom</label>
								<input type="text" class="form-control inpumask_integer" name="bathroom" value="<? if(isset($data[0])){?>{{ Request::old('bathroom') ? : $data[0]->bathroom }}<?}?>" placeholder="bathroom">
							</div>
						</div>
						
						<div class="col-sm-3 col-md-3">
							<div class="form-group">
								<label>Sq.ft.</label>
								<input type="text" class="form-control inpumask_integer" name="sq_ft" value="<? if(isset($data[0])){?>{{ Request::old('sq_ft') ? : $data[0]->sq_ft }}<?}?>" placeholder="sq_ft">
							</div>
						</div>
						
						<div class="col-sm-3 col-md-3">
							<div class="form-group">
								<label>Year House Built</label>
								<input type="text" class="form-control" name="year_house_built" value="<? if(isset($data[0])){?>{{ Request::old('year_house_built') ? : $data[0]->year_house_built }}<?}?>" placeholder="year_house_built">
							</div>
						</div>
						
						<? /* ----- */ ?>
						
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<h3>Financial Details</h3>
                                <span style="color:#E80003; font-size: 14px;">(Please don't leave any values empty in this section)</span>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Property Price</label>
								<input type="text" class="form-control inpumask_integer" name="property_price" value="<? if(isset($data[0])){?>{{ Request::old('property_price') ? : $data[0]->property_price }}<?}?>" placeholder="(Example: $300,250)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Current Rent (Monthly)</label>
								<input type="text" class="form-control inpumask_integer" name="current_rent" value="<? if(isset($data[0])){?>{{ Request::old('current_rent') ? : $data[0]->current_rent }}<?}?>" placeholder="(Example: $3,200)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Market Rent (Monthly)</label>
								<input type="text" class="form-control inpumask_integer" name="estimated_market_rent" value="<? if(isset($data[0])){?>{{ Request::old('estimated_market_rent') ? : $data[0]->estimated_market_rent }}<?}?>" placeholder="(Example: $3,200)">
							</div>
						</div>
						
					
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Percentage Available for Investors</label>
								<input type="text" class="form-control inpumask_integer" name="available_percent_to_invest" value="<? if(isset($data[0])){?>{{ Request::old('available_percent_to_invest') ? : $data[0]->available_percent_to_invest }}<?}else{ echo "20"; }?>" placeholder="(Example: 80%)">
							</div>
						</div>
						
						
						
						
						<!-- new data /* -->
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>CAPEX (Annual)</label>
								<input type="text" class="form-control inpumask_integer" name="_default_cap_ex" value="<? if(isset($data[0])){?>{{ Request::old('_default_cap_ex') ? : $data[0]->_default_cap_ex }}<?}?>" placeholder="(Example: $1,200)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Vacancy Rate</label>
								<input type="text" class="form-control inpumask_decimal" name="_default_vacancy_rate" value="<? if(isset($data[0])){?>{{ Request::old('_default_vacancy_rate') ? : $data[0]->_default_vacancy_rate }}<?}?>" placeholder="(Example: 3%)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Forecast Growth Rate: First Year <a href="https://www.zillow.com" target="_blank">(Find this on Zillow)</a></label>
								<input type="text" class="form-control inpumask_decimal" name="_forecast_growth_rate_1year" value="<? if(isset($data[0])){?>{{ Request::old('_forecast_growth_rate_1year') ? : $data[0]->_forecast_growth_rate_1year }}<?}?>" placeholder="(Example: 12.6%)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Estimated Appreciation (Years 2 thru 5)</label>
								<input type="text" class="form-control inpumask_decimal" name="_default_appreciation" value="<? if(isset($data[0])){?>{{ Request::old('_default_appreciation') ? : $data[0]->_default_appreciation }}<?}?>" placeholder="(Example: 3.2%)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Taxes (Annual)</label>
								<input type="text" class="form-control inpumask_integer" name="_default_taxes" value="<? if(isset($data[0])){?>{{ Request::old('_default_taxes') ? : $data[0]->_default_taxes }}<?}?>" placeholder="(Example: $1,700)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Insurance (Annual)</label>
								<input type="text" class="form-control inpumask_integer" name="_default_insurance" value="<? if(isset($data[0])){?>{{ Request::old('_default_insurance') ? : $data[0]->_default_insurance }}<?}?>" placeholder="(Example: $1,220)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Maintenance Estmate (Annual)</label>
								<input type="text" class="form-control inpumask_integer" name="_default_maintenance" value="<? if(isset($data[0])){?>{{ Request::old('_default_maintenance') ? : $data[0]->_default_maintenance }}<?}?>" placeholder="(Example: $770)">
							</div>
						</div>
						
						
						<!-- property Management -->
						<!-- BitREI Trustee -->
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Property Management Fees (Annual)</label>
								<input type="text" class="form-control inpumask_integer" name="_default_property_management" value="<? if(isset($data[0])){?>{{ Request::old('_default_property_management') ? : $data[0]->_default_property_management }}<?}?>" placeholder="(Example: $1,200)">
							</div>
						</div>
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>BitREI Trustee (Annual)</label>
								<input type="text" class="form-control inpumask_integer" name="_default_bitrei_trustee" value="<? if(isset($data[0])){?>{{ Request::old('_default_bitrei_trustee') ? : $data[0]->_default_bitrei_trustee }}<?}?>" placeholder="(Example: $1,200)">
							</div>
						</div>
						
						
						
						
						
						
						<!-- new data */ -->
						
						
					
						
						
						
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label>Closing Date</label>
								<input type="text" class="form-control" id="closing_date" name="closing_date" value="<? if(isset($data[0])){?>{{ Request::old('closing_date') ? : $data[0]->closing_date }}<?}?>" placeholder="closing_date">
							</div>
						</div>
						
						<? /* ----- */ ?>
						<p>&nbsp;</p>
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<h3>Other</h3>
							</div>
						</div>
						
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label>Title</label>
								<input type="text" class="form-control" name="title" value="<? if(isset($data[0])){?>{{ Request::old('title') ? : $data[0]->title }}<?}?>" placeholder="title">
							</div>
						</div>
						
						<div class="col-sm-12 col-md-12">
							<div class="form-group ">
								<label for="description">Text</label>
								
								<textarea name="description" id="editor1" style="border: 1px solid #ccc;width:100%;height:200px;display:block;"><? if(isset($data[0])){?>{{ $data[0]->description }}<?}?></textarea>
								
							</div>
						</div>
						
						<? if(!isset($data[0])){ ?>
						<div class="col-sm-12 col-md-12">
							<div class="form-group">
								<label>Thumbnail</label>
								<input type="text" class="form-control" name="thumbnail" value="<? if(isset($data[0])){?>{{ Request::old('thumbnail') ? : $data[0]->thumbnail }}<?}?>" placeholder="thumbnail">
							</div>
						</div>
						<? } ?>
						
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
                            <p>&nbsp;</p>
								<label>Display this property on homepage? </label>
								<input type="checkbox" name="is_homepage" value="1" <? if(isset($data[0]) && $data[0]->is_homepage == 1){ echo "checked='checked'"; }?>>
							</div>
						</div>
						
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label>Homepage sort order</label>
								<input type="text" name="sort" class="form-control" value="<? if(isset($data[0])){?>{{ Request::old('sort') ? : $data[0]->sort }}<?}?>">
							</div>
						</div>
						
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label>Visibility Status</label>
								<select name="display_status" class="form-control">
									<option value="1" <? if(isset($data[0]) && $data[0]->display_status == 1){ echo "selected"; }?>>Visible in List View</option>
									<option value="0" <? if(isset($data[0]) && $data[0]->display_status == 0){ echo "selected"; }?>>Hidden from List View</option>
								</select>
							</div>
						</div>
						
						<? if(!isset($data[0])){ ?>
						<div class="form-group col-sm-12 col-md-12" style="color:red;">
						ATTENTION: After creating this property, please visit Klaviyo and create a new segment for this property.
						</div>
						<? } ?>
						
						<div class="form-group col-sm-12 col-md-12">
                        <p>&nbsp;</p>
							<input type="submit" class="btn btn-primary waves-effect waves-light" value="Save" style="width: 100%;"/>
						</div>

					</form>
					
					
					<? if(isset($data[0])){ ?>
					
						<br>
						<br>
						<br>
					
						<form action="/admin/delete-property" method="post" enctype="multipart/form-data">
						
						{{ csrf_field() }}
					
						<input type="hidden" name="id" value="{{ $data[0]->id }}">
						
						<div class="form-group col-sm-12 col-md-12">
							<input type="submit" class="btn btn-default waves-effect waves-light" value="Permanently Delete This Property"/>
						</div>

						</form>
					<? } ?>
					
                </div>
            </div>
        </div>


@endsection

@section('footer')
       
<script type="text/javascript" src="/js/inputmask/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		
		$(".inpumask_decimal").inputmask("decimal", {
			prefix: "",
			groupSeparator: ',',
			autoGroup: true,
			placeholder: '0',
			"rightAlign":false
		});
		
		$(".inpumask_integer").inputmask("integer", {
			prefix: "",
			groupSeparator: ',',
			autoGroup: true,
			//placeholder: '0',
			"rightAlign":false
		});
		
	});
	
	$(function() {
		
		<? if(isset($data[0])){ $strtotimeD1 = strtotime($data[0]->closing_date); ?>
		
		$('#closing_date').daterangepicker({
			timePicker: false,
			timePicker24Hour: false,
			singleDatePicker: true,
			locale: {
				format: 'MM/DD/YYYY'
			},
			startDate: '<?= date("m", $strtotimeD1); ?>/<?= date("d", $strtotimeD1); ?>/<?= date("Y", $strtotimeD1); ?>',
		});
		
		<? }else{ ?>
		
		$('#closing_date').daterangepicker({
			timePicker: false,
			timePicker24Hour: false,
			singleDatePicker: true,
			locale: {
				format: 'MM/DD/YYYY'
			}
		});
		
		<? } ?>
	
		
		
	});
	
	</script>
 
@endsection
