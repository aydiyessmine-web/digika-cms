<!DOCTYPE html>
<html>
<head>
    <title>Media CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Upload Media</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="/media" enctype="multipart/form-data">
    @csrf

    <input type="text" name="title" class="form-control mb-2" placeholder="Title">

    <input type="file" name="file" class="form-control mb-2">

    <button class="btn btn-primary">Upload</button>
</form>

<hr>

<h3>Gallery</h3>

<div class="row">
@foreach($media as $m)
    <div class="col-md-3 mb-3">
        @if($m->type == 'image')
            <img src="{{ asset('storage/'.$m->file_path) }}" class="img-fluid">
        @else
            <video width="100%" controls>
                <source src="{{ asset('storage/'.$m->file_path) }}">
            </video>
        @endif
        <p>{{ $m->title }}</p>
    </div>
@endforeach
</div>

</body>
</html>