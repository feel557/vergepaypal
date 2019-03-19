@extends('admin.dash')

@section('content')

<div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12">
			<div class="x_panel">
                 <div class="x_title">
					<h2><a href="/admin/blog-category"><u>Категории</u></a> -> Редактирование</h2>
					<div class="clearfix"></div>
				</div>
                <div class="x_content">
				
				<form action="/admin/blog-add-category" method="post" enctype="multipart/form-data">
			
				{{ csrf_field() }}
            
				<? if(isset($data[0])){?>
				<input type="hidden" name="id" value="{{ $data[0]->id }}">
				<?}?>
				
				
                <div class="col-sm-12 col-md-12">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" value="<? if(isset($data[0])){?>{{ Request::old('name') ? : $data[0]->title }}<?}?>" placeholder="Title">
                       
                    </div>
                </div>
				
                <div class="col-sm-12 col-md-12">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>Название</label>
                        <input type="text" class="form-control" name="name" value="<? if(isset($data[0])){?>{{ Request::old('name') ? : $data[0]->name }}<?}?>" placeholder="Name">
                       
                    </div>
                </div>
				
                <div class="col-sm-12 col-md-12">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>Description</label>
                        <textarea class="form-control" name="description" style="height:80px;"><? if(isset($data[0])){ echo $data[0]->description; } ?></textarea>
                    </div>
                </div>
				
                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Keywords</label>
                        <textarea class="form-control" name="keywords" style="height:80px;"><? if(isset($data[0])){ echo $data[0]->keywords; } ?></textarea>
                    </div>
                </div>
				
				
				<div class="col-sm-12 col-md-12">
					<div class="form-group ">
						<label for="description">Text</label>
						
						<textarea name="text" id="editor1" style="border: 1px solid #ccc;width:100%;height:200px;display:block;"><? if(isset($data[0])){?>{{ $data[0]->text }}<?}?></textarea>
						
						
					</div>
				</div>
				
                <div class="col-sm-12 col-md-12">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>URL</label>
                        <input type="text" class="form-control" name="url" value="<? if(isset($data[0])){?>{{ Request::old('name') ? : $data[0]->url }}<?}?>" placeholder="url">
                       
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

			
			<a href="/admin/blog-category-del?id=<?= $data[0]->id ?>" class="btn btn-danger waves-effect waves-light">Caution! Delete Page?</a>
			
			
<? } ?>
		

@endsection

@section('footer')

<script src="/js/ckeditor/ckeditor.js"></script>
<script>
	CKEDITOR.replace( 'editor1',{
	filebrowserUploadUrl: '/upload/wa'
	} );
</script>

@endsection
