@extends('common.dash')

@section('content')

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Documents</h2>
                    <a href="/admin/documents-add" class="btn btn-primary pull-right">Add new</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
						  <th>Category</th>
						  <th>Property</th>
						  <th>Member</th>
						  <th>Edit</th>
                        </tr>
                      </thead>
                      <tbody>
						<? foreach($data as $item){ 
							
							$category_obj = DB::table('_member_documents_categories')
							->where("id", "=", $item->category_id)
							->get(); 
							
							$user_obj = DB::table('users')
							->where("id", "=", $item->user_id)
							->get();
							
							$property_obj = DB::table('_property_details')
							->where("id", "=", $item->property_id)
							->get();
							
							$property_item = $property_obj[0];
							
							$address_text = $property_item->address . ", " . $property_item->city . ", " . $property_item->state . " " . $property_item->zip;
							
						?>
                        <tr>
                          <th scope="row"><?= $item->id ?></th>
						  <td><a href="/admin/documents-edit?id=<?= $item->id ?>"><?= $category_obj[0]->name ?></a></td>
						  <td><a href="/property/item/<?= $property_item->id ?>"><?= $address_text ?></a></td>
						  <td><a href="/admin/documents-edit?id=<?= $item->id ?>"><? echo $user_obj[0]->email . " / " . $user_obj[0]->first_name . " " . $user_obj[0]->last_name ?></a></td>
                          <td><a href="/admin/documents-edit?id=<?= $item->id ?>">Edit document</a></td>
                        </tr>
						<? } ?>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>

@endsection