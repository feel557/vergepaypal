@extends('common.dash')

@section('content')




              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Property list</h2>
                    <a href="/admin/property-add" class="btn btn-primary pull-right">Add new +</a>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
						  <th>Price</th>
						  <th>State</th>
						  <th>ZIP</th>
						  <th>Address</th>
						  <!--<th>Investors</th>-->
						  <!--<th>Pledged amount</th>-->
						  <!--<th>Percents invested</th>-->
						  <th>Edit</th>
						  <th>View</th>
                        </tr>
                      </thead>
                      <tbody>
				<? foreach($data as $item){ ?>
                        <tr>
                          <td><?= $item->id ?></td>
						  <td>$<?= number_format((float)$item->property_price, 0, '.', ',') ?></td>
						  <td><?= $item->state ?></td>
						  <td><?= $item->zip ?></td>
						  <td><?= $item->address ?></td>
						  <!--<td>Investors</td>-->
						  <!--<td>Pledged amount</td>-->
						  <!--<td>Percents invested</td>-->
                          <td><a href="/admin/property-details?id=<?= $item->id ?>">Edit</a></td>
						  <td><a href="/property/item/<?= $item->id ?>">View</a></td>
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