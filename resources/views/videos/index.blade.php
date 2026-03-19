<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saint Tutos - Vidéo Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f9f9f9; }
        /* Barre latérale fixe */
        .sidebar {
            position: fixed; top: 56px; left: 0; bottom: 0;
            width: 240px; background: white; padding: 12px;
            overflow-y: auto; z-index: 1000;
        }
        .main-content { margin-left: 240px; padding-top: 80px; }
        .nav-link { color: #0f0f0f; border-radius: 10px; margin-bottom: 2px; }
        .nav-link:hover, .nav-link.active { background-color: #f2f2f2; font-weight: 500; }
        
        /* Style des vignettes YouTube */
        .video-thumbnail {
            position: relative; border-radius: 12px; overflow: hidden;
            aspect-ratio: 16/9; background: #000;
        }
        .video-thumbnail img { width: 100%; height: 100%; object-fit: cover; }
        .duration {
            position: absolute; bottom: 8px; right: 8px;
            background: rgba(0,0,0,0.8); color: white;
            padding: 2px 4px; font-size: 12px; border-radius: 4px;
        }
        .video-title {
            font-size: 16px; font-weight: 600; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; overflow: hidden;
            margin-top: 10px; color: #0f0f0f; text-decoration: none;
        }
        .video-meta { font-size: 14px; color: #606060; }
        .navbar { z-index: 1001; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="bi bi-play-btn-fill text-primary fs-3 me-2"></i>
            <span class="fw-bold border-1">SAINT TUTOS</span>
        </a>
        
        <form action="{{ route('videos.index') }}" method="GET" class="d-flex mx-auto" style="width: 50%; max-width: 600px;">
            <div class="input-group">
                <input type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="form-control rounded-start-pill ps-4" 
                    placeholder="Rechercher un tutoriel...">
                <button class="btn btn-outline-secondary rounded-end-pill px-4 bg-light" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <div class="d-flex align-items-center">
            <i class="bi bi-camera-video fs-4 me-4"></i>
            <i class="bi bi-bell fs-4 me-4"></i>
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 35px; height: 35px;">
                F
            </div>
        </div>
    </div>
</nav>

<div class="sidebar d-none d-md-block border-end shadow-sm">
    <nav class="nav flex-column">
        <a class="nav-link active" href="#"><i class="bi bi-house-door me-3"></i> Accueil</a>
        <a class="nav-link" href="#"><i class="bi bi-collection-play me-3"></i> Abonnements</a>
        <hr>
        <p class="text-muted small ps-3 fw-bold mb-1">Catégories</p>
        <a class="nav-link" href="#"><i class="bi bi-code-slash me-3"></i> Programmation</a>
        <a class="nav-link" href="#"><i class="bi bi-palette me-3"></i> Design</a>
        <a class="nav-link" href="#"><i class="bi bi-database me-3"></i> Big Data</a>
    </nav>
</div>

<div class="main-content px-4">
    <div class="row">
        @foreach($videos as $video)
        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
            <div class="card border-0 bg-transparent">
                <div class="video-thumbnail">
                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                    <span class="duration">12:45</span>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex mt-2">
                        <div class="me-3">
                            <div class="bg-secondary rounded-circle" style="width: 36px; height: 36px;"></div>
                        </div>
                        <div>
                            <a href="#" class="video-title d-block">{{ $video->title }}</a>
                            <div class="video-meta">
                                <div>{{ $video->category->name }} • <span class="badge bg-light text-dark border">{{ $video->level }}</span></div>
                                <div>1.2k vues • il y a 2 jours</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center py-4">
        {{ $videos->links('pagination::bootstrap-5') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>