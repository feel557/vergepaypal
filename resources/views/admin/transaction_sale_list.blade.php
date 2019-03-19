@extends('common.dash')

@section('content')

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Bits Sales Transactions</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
				  <div style="padding:20px 0;">
				  <form action="/admin/transaction-sale-list" method="get">
				  
				  <select class="form-control" name="sign_status" style="display:inline-block;width:200px;">
					<option value="">--All--</option>
					<option value="2">Signed</option>
					<option value="0">Unsigned</option>
				  </select>
				  
				  <select class="form-control" name="sale_status" style="display:inline-block;width:200px;">
					<option value="">--All--</option>
					<option value="1">Ready to sign. Price accepted.</option>
					<option value="2">Completed</option>
					<option value="4">Waiting for admin approval</option>
					<option value="3">Waiting for member approval</option>
					<option value="0">----temporary</option>
				  </select>
				  
				  <input type="submit" class="btn btn-primary" value="Filter">
				  
				  </form>
				  
				  <a href="/admin/transaction-sale-list">Clear filters</a>
				  
				  </div>
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
							<th>#ID</th>
                            <th>Date</th>
                            <th>Resell Details</th>
							<th>User</th>
							<th>Status</th>
							<th>Confirm</th>
							<th>Offer</th>
							<th>Complete</th>
							<th>Log</th>
                          </tr>
                      </thead>
                      <tbody>
				<?
						
						foreach($data as $transaction_item){
						
							$textDescription = "";
					
							$property_data = DB::table('_property_details')
								->where("id","=",$transaction_item->property_id)
								->get();
								
							if(count($property_data)>0){
								
								$address_text = $property_data[0]->address . "<br/> " . $property_data[0]->city . ", " . $property_data[0]->state . " " . $property_data[0]->zip;
						
								$textDescription .= "<a href='/property/item/" . $transaction_item->property_id . "'><u>" . $address_text . "</u></a><br/><br/> ";
								
								
							}
							
							/*
							$transaction_date_started = date("m/d/Y", strtotime($transaction_item->date_started));
							
							$signed_docs_link = "";
							if($transaction_item->docusign_status == 2){
								
								$signed_docs_link = "<a href='/member/signed-docs?transaction_id=".$transaction_item->id."'>Download Offering Statement</a>";
							
							}
							*/
							
							$user_obj = DB::table('users')
							->where("id","=",$transaction_item->user_id)
							->get();	
								
								
						?>
                          <tr>
							<td><?= $transaction_item->id ?></td>
							<td><?= $transaction_item->date ?></td>
							<td><?= $textDescription ?>
								Bits to Resell: <?= $transaction_item->bits_to_sell ?><br>
								Price per Bit: $<?= number_format((float)$transaction_item->price_per_bit, 0, '.', ',') ?><br>
								Total Amount: $<?= number_format((float)$transaction_item->amount_to_sell, 0, '.', ',') ?></td>
							<td><? echo $user_obj[0]->email . " / " . $user_obj[0]->first_name . " " . $user_obj[0]->last_name ?></td>
							<td><?
							
							if($transaction_item->sign_status == 2){ echo "Signed"; }
							if($transaction_item->sign_status == 0){ echo "Not Signed Yet"; }
							
							echo " / ";
							
							if($transaction_item->sale_status == 0){ echo "<br>Review Needed"; }
							if($transaction_item->sale_status == 1){ echo "<br>Ready to sign. Offer Accepted"; }
							if($transaction_item->sale_status == 2){ echo "<br>Offers Accepted - Now Add Bits to Property"; }
							if($transaction_item->sale_status == 3){ echo "<br>Waiting for <b>member</b> approval."; }
							if($transaction_item->sale_status == 4){ echo "<br><span style='color:red;line-height:18px;'>Waiting for <b>admin</b> approval.</span>"; }
							
							
							?></td>
							<td>
							<? if($transaction_item->sale_status == 4){

								?><form action="/admin/transaction-sale-confirm-price" method="post">
									<?= csrf_field(); ?>
									<input type="submit" class="btn btn-primary" value="Confirm Price">
									<input type="hidden" name="id" value="<?= $transaction_item->id ?>">
								</form><?

							} ?>
							</td>
							<td>
							<? if($transaction_item->sale_status == 4){ 

								?><form action="/admin/transaction-sale-offer-price" method="post">
									<?= csrf_field(); ?>
									<input type="text" name="price" value="" class="form-control">
									<input type="hidden" name="id" value="<?= $transaction_item->id ?>"><br/>
									<input type="submit" class="btn btn-primary" value="Send Offer Price">
								</form><?

							} ?>
							</td>
							<td><?
							
							if( $transaction_item->sale_status == 1 && $transaction_item->sign_status == 2 ){
								
								echo "Resale Agreement has been signed by member.</br>";
								
								echo "<a href='/admin/transaction-sale-complete?id=".$transaction_item->id."' class='complete-transaction' data-id='".$transaction_item->id."'style='color:green;'> Mark as Complete </a>";
								
							}elseif( $transaction_item->sale_status == 2){
								
								echo "<span style='color:green;'>Completed</span>";
								
							}
							
							?></td>
							<td style="font-size:12px;">
								<?
								
								$sellRequestItem = $transaction_item;
								
								$offerHistoryData = DB::table('_member_property_bits_sales_offer_log')
									->where("sale_transaction_id", "=", $sellRequestItem->id)
									->orderBy("id", "DESC")
									->get();
								
								echo "<table>
								<thead>
								<th><strong>Date</strong></th>
								<th><strong>Amount</strong></th>
								<th><strong>Side</strong></th>
								</thead>";
								
								foreach($offerHistoryData as $logItem){
									
									if($logItem->operation_code == 4){ $textUserRole = "Investor"; }
									if($logItem->operation_code == 3){ $textUserRole = "BitREI"; }
									
									echo "<tr style='border-bottom:1px solid #eee;'>
									<td style='padding:5px;'>".date("m/d/Y",strtotime($logItem->date))."</td>
									<td style='padding:5px;'>$".number_format((float)$logItem->price_per_bit, 0, '.', ',')."</td>
									<td style='padding:5px;'>".$textUserRole."</td>
									</tr>";
						  
								}
								
								echo "</table>";
								
								?>
								
							</td>
                          </tr>
							<? } ?>
						  
                      </tbody>
                    </table>
<?
					
					// status=1&project_id=1&_theme_category=0&task_type=0
					$pagination_array = array();
					
					//if(isset($_GET['status'])){$pagination_array['status'] = $_GET['status'];}
					//if(isset($_GET['project_id'])){$pagination_array['project_id'] = $_GET['project_id'];}
					
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