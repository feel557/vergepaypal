@extends('common.dash')

@section('content')

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Transaction History</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
							<th>#ID</th>
                            <th>Date</th>
                            <th>Property</th>
							<th>User</th>
							<th>Status</th>
							<th>Complete</th>
                            <th>Amount</th>
                          </tr>
                      </thead>
                      <tbody>
				<?
						
						foreach($data as $transaction_item){
						
							$property_data_array_json = $transaction_item->property_data_array;
							
							$textDescription = "";
							
							if(!is_null($property_data_array_json)){
								
								$property_data_array = json_decode($property_data_array_json, true);
								
								$textDescription = "";
								
								
								foreach($property_data_array as $transaction_property_item){
									
									$current_property_id = $transaction_property_item["property_id"];
									$current_property_invest_amount = $transaction_property_item["invest_amount"];
									$current_property_invest_ownership_percentage = $transaction_property_item["invest_ownership_percentage"];
									
									$property_data = DB::table('_property_details')
										->where("id","=",$current_property_id)
										->get();
										
									if(count($property_data)>0){
										
										$address_text = $property_data[0]->address . "<br/> " . $property_data[0]->city . ", " . $property_data[0]->state . " " . $property_data[0]->zip . "<br/>";
								
										$textDescription .= "<tr><td>" .$current_property_invest_ownership_percentage . "% of </td><td style='padding-left: 10px;'> <a href='/property/item/" . $current_property_id . "'><u>" . $address_text . "</u></a><br/></td></tr>";
										
										
									}
									
								}
								
							}
								
								$transaction_date_started = date("m/d/Y", strtotime($transaction_item->date_started));
								
								$signed_docs_link = "";
								if($transaction_item->docusign_status == 2){
									
									$signed_docs_link = "<a href='/member/signed-docs?transaction_id=".$transaction_item->id."'>Download Offering Statement</a>";
								
								}
								
								$user_obj = DB::table('users')
								->where("id","=",$transaction_item->user_id)
								->get();	
								
								
						?>
                          <tr>
						  <td><?= $transaction_item->id ?></td>
                            <td>
                              <?= $transaction_date_started ?>
                            </td>
                            
                            <td>
                            	<table>
									<?= $textDescription ?>
                                </table>
                           	</td>
							
							<td><a href="<?= '/common/user-details?id='.$transaction_item->user_id ?>"><? echo $user_obj[0]->email . " / " . $user_obj[0]->first_name . " " . $user_obj[0]->last_name ?></td>
							
							<td><?
							
							if($transaction_item->status == 2){
								
								echo "Completed";
								
							}elseif($transaction_item->status == 4){
								
								echo "Pending Funding";
								
							}elseif($transaction_item->status == 1){
								
								echo "In Process";
								
							}elseif($transaction_item->status == 9){
								
								echo "Cancelled";
								
							}
							
							?></td>
							<td><?
							
							if( $transaction_item->status == 4 ){
								
								echo "<center><a class='btn btn-primary waves-effect waves-light' href='/admin/complete-transaction?id=".$transaction_item->id."' class='complete-transaction' data-id='".$transaction_item->id."'style='color:white;'> Mark as Completed </a></center>";
								
							}
							
							?><?
							
							if(
								$transaction_item->status != 2 &&
								$transaction_item->status != 9 
							){
								
								echo "<br/><center>-or-</center><br/><center><a href='/admin/cancel-transaction?id=".$transaction_item->id."' style='color:red;'>Cancel Transaction</a></center>";
								
							}
							
							?></td>
                            <td>Total Bits Cost: $<?= number_format((float)$transaction_item->invest_amount, 0, '.', ',') ?><br/>Total Closing Costs: $<?= number_format((float)$transaction_item->total_closing_costs, 0, '.', ',') ?><br/>Total Reserves: $<?= number_format((float)$transaction_item->total_reserves, 0, '.', ',') ?><br/><br/><hr><br/><strong>Total Funds Expected: $<?= number_format((float)($transaction_item->invest_amount + $transaction_item->total_reserves + $transaction_item->total_closing_costs), 0, '.', ',') ?></strong></td>
                          </tr>
							<? } ?>
						  
                      </tbody>
                    </table>
<?
					
					// status=1&project_id=1&_theme_category=0&task_type=0
					$pagination_array = array();
					
					//if(isset($_GET['status'])){$pagination_array['status'] = $_GET['status'];}
					//if(isset($_GET['project_id'])){$pagination_array['project_id'] = $_GET['project_id'];}
					//if(isset($_GET['_theme_category'])){$pagination_array['_theme_category'] = $_GET['_theme_category'];}
					//if(isset($_GET['task_type'])){$pagination_array['task_type'] = $_GET['task_type'];}
					
					//if(!isset($_GET['search'])){

						echo $data->appends($pagination_array)->render();

					//}
					
					?>
                  </div>
                </div>
              </div>

@endsection


@section('footer')

<script>

$(document).ready(function(){

	$("input.user-status").on("change",function(){

		//Checks the switch
		//setSwitchery(mySwitch, true);
		//Unchecks the switch
		//setSwitchery(mySwitch, false);

		var dataId = $(this).attr("data-id");
		var dataAct = $(this).attr("data-act");
		if(dataAct == 0){var act = 1;}else{var act = 0;}
		
		alert(act);
		//$(".user-status[data-act='"+act+"']").attr("data-act", act);
		$(this).attr("data-act", act);

		$.ajax({
			type: "GET",
			url: "/common/update-manager-status/",
			data: {
			"id": dataId,
			"act": act
			},
			cache: false,
			success: function(response){
				
			}
		});

	});

});


</script>

@endsection