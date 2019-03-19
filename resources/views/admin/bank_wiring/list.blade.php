@extends('common.dash')

@section('content')

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Bank Wiring Data List</h2>
					<a href="/admin/bank-data-add" class="btn btn-primary pull-right">+ Add</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
                            <th>Bank name</th>
                            <th>Address</th>
							<th>Account number</th>
                            <th>Routing number</th>
							<th>Swift</th>
                            <th>Edit</th>
                          </tr>
                      </thead>
                      <tbody>
						<? foreach($data as $item){ ?>
                          <tr>
                          <td><?= $item->bank_name ?></td>
                          <td><?= $item->address ?></td>
                          <td><?= $item->account_number ?></td>
						  <td><?= $item->routing_number ?></td>
						  <td><?= $item->swift ?></td>
						  <td><a href="/admin/bank-data-edit?id=<? echo $item->id ?>">Edit</a></td>
                          </tr>
						<? } ?>
                      </tbody>
                    </table>
					<? //echo $data->render(); ?>
                  </div>
                </div>
              </div>

@endsection


@section('footer')

<script>


</script>

@endsection