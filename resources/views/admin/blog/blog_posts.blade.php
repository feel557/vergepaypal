@extends('common.dash')

@section('content')




              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?= $name ?></h2>
                    <a href="/admin/blog-page-edit" class="btn btn-primary pull-right">Add new +</a>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
						  <th>Name</th>
						  <th>Edit</th>
						  <th>View</th>
                        </tr>
                      </thead>
                      <tbody>
				<? foreach($data as $item){ ?>
                        <tr>
                          <th scope="row"><?= $item->id ?></th>
						  <td><a href="/admin/blog-page-edit?id=<?= $item->id ?>"><?= $item->name ?></a></td>
                          <td><a href="/admin/blog-page-edit?id=<?= $item->id ?>">Edit</a></td>
						  <td><a href="/pages/<?= $item->url ?>">View</a></td>
                        </tr>
				<? } ?>
						
                      </tbody>
                    </table>

					
					
                  </div>
                </div>
              </div>


@endsection