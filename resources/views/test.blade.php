<!DOCTYPE html>
<html>
<head>
  <title>Laravel 8 Uploading Image</title>
 
  <meta name="csrf-token" content="{{ csrf_token() }}">
 
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 
</head>
<body>
 
<div class="container mt-5">
 
  @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif
 
  <div class="card">
 
    <div class="card-header text-center font-weight-bold">
      <h2>Laravel 8 Upload Image Tutorial</h2>
    </div>
 
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="upload-image" action="/type" >
            @csrf
                   
            <div class="row">
 
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="file" name="photo" placeholder="Choose image" id="image">
                        
                    @error('image')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                    </div>
                    <input type="text" name="type_name" placeholder="type_name" id="type_name"><br>
                    <input type="text" name="price" placeholder="price" id="price"><br>
                    <input type="text" name="desc" placeholder="desc" id="desc"><br>
                </div>
                   
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                </div>
            </div>     
        </form>
 
    </div>
 
  </div>
 
</div>  
</body>
</html>