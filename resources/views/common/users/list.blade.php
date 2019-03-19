@extends('common.dash')

@section('content')

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Users</h2>
                    <a href="/common/user-add" class="btn btn-primary pull-right">+ Invite user</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#ID</th>
						  <th>Role</th>
						  <th>Name / Email</th>
						  <th>Status</th>
						  <th>-</th>
                        </tr>
                      </thead>
                      <tbody>
				<? foreach($data as $item){ ?>
                        <tr>
                          <td scope="row">#<?= $item->id ?></td>
						  <td><? if($item->user_type == 2){echo "Manager";}elseif($item->user_type == 3){echo "Member";} ?></td>
						  <td><a href="/common/user-details?id=<?= $item->id ?>"><b><? echo $item->username." / ".$item->email; ?></b></a></td>
						  <td>
                              <input type="checkbox" class="js-switch user-status" data-id="<?= $item->id ?>" data-act="<? if($item->is_active == 1){ echo 1; }else{ echo 0; } ?>" <? if($item->is_active == 1){echo "checked";}else{echo "";} ?> />
                           </td>
						   <td><a href="/admin/login-as-user/?id=<?= $item->id ?>">Login as user</a></td>
                        </tr>
				<? } ?>
                      </tbody>
                    </table>

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