<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon App de Tutoriels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .video-card img { height: 200px; object-fit: cover; }
        .badge-level { position: absolute; top: 10px; right: 10px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">SAINT TUTOS</a>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Derniers Tutoriels Vidéo</h2>
    
    <div class="row">
        @foreach($videos as $video)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm video-card">
                <span class="badge bg-primary badge-level">{{ $video->level }}</span>
                <img src="{{ $video->thumbnail_url }}" class="card-img-top" alt="{{ $video->title }}">
                <div class="card-body">
                    <h5 class="card-title text-truncate">{{ $video->title }}</h5>
                    <p class="text-muted small">{{ $video->category->name }}</p>
                    <p class="card-text text-muted">{{ Str::limit($video->description, 80) }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-3">
                    <a href="#" class="btn btn-outline-dark w-100">Regarder le tuto</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $videos->links('pagination::bootstrap-5') }}
    </div>
</div>

</body>
</html>