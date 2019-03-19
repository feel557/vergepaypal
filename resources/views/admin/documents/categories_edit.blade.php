@extends('common.dash')

@section('content')

	<div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12">
			<div class="x_panel">
                 <div class="x_title">
					<h2><a href="/admin/documents-categories"><u>Categories</u></a> -> Edit/Add</h2>
					<div class="clearfix"></div>
				</div>
                <div class="x_content">
				
				<form action="/admin/documents-categories-add" method="post" enctype="multipart/form-data">
			
				{{ csrf_field() }}
            
				<? if(isset($data[0])){?>
				<input type="hidden" name="id" value="{{ $data[0]->id }}">
				<?}?>
				
                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="<? if(isset($data[0])){?>{{ Request::old('name') ? : $data[0]->name }}<?}?>" placeholder="Name">
                       
                    </div>
                </div>
				
                <div class="form-group col-sm-12 col-md-12">
                    <input type="submit" class="btn btn-primary waves-effect waves-light" value="Save"/>
                </div>

            </form>
			</div>
			</div>
		</div>

	</div>





<? if(isset($data[0]) && isset($data[0]->id)){ ?>

			
	<a href="/admin/documents-categories-del?id=<?= $data[0]->id ?>" class="btn btn-danger waves-effect waves-light">Caution! Delete?</a>
			
			
<? } ?>
		

@endsection