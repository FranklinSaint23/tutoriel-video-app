<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->title }} - Saint Tutos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f9f9f9; padding-top: 70px; }
        .video-container { border-radius: 12px; overflow: hidden; background: #000; aspect-ratio: 16/9; }
        .suggestion-card img { width: 160px; height: 90px; object-fit: cover; border-radius: 8px; }
        .suggestion-title { font-size: 14px; font-weight: 600; line-height: 1.2; color: #0f0f0f; text-decoration: none; }
        .video-main-title { font-size: 20px; font-weight: 700; margin-top: 15px; }
        .channel-info img { width: 40px; height: 40px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('videos.index') }}">
            <i class="bi bi-play-btn-fill text-primary fs-3 me-2"></i>
            <span class="fw-bold">SAINT TUTOS</span>
        </a>
    </div>
</nav>

<div class="container-fluid px-lg-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="video-container shadow">
                <video controls class="w-100 h-100">
                    <source src="{{ $video->video_url }}" type="video/mp4">
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
            </div>

            <h1 class="video-main-title">{{ $video->title }}</h1>
            
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary rounded-circle me-3" style="width: 40px; height: 40px;"></div>
                    <div>
                        <div class="fw-bold">Formateur Saint</div>
                        <div class="text-muted small">10k abonnés</div>
                    </div>
                    <button class="btn btn-dark rounded-pill ms-4 px-3 btn-sm">S'abonner</button>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light rounded-pill border"><i class="bi bi-hand-thumbs-up me-2"></i> 128</button>
                    <button class="btn btn-light rounded-pill border"><i class="bi bi-share me-2"></i> Partager</button>
                </div>
            </div>

            <div class="bg-light p-3 rounded-4 mb-4">
                <div class="fw-bold small">1.2k vues • {{ $video->created_at->diffForHumans() }}</div>
                <p class="mt-2">{{ $video->description }}</p>
                <span class="badge bg-white text-dark border">#{{ $video->category->name }}</span>
                <span class="badge bg-white text-dark border">#{{ $video->level }}</span>
            </div>
        </div>

        <div class="col-lg-4">
            <h5 class="mb-3">Vidéos similaires</h5>
            @foreach($suggestions as $suggestion)
            <div class="d-flex mb-3 suggestion-card">
                <a href="{{ route('videos.show', $suggestion->slug) }}">
                    <img src="{{ $suggestion->thumbnail_url }}" alt="{{ $suggestion->title }}">
                </a>
                <div class="ms-2">
                    <a href="{{ route('videos.show', $suggestion->slug) }}" class="suggestion-title d-block">
                        {{ Str::limit($suggestion->title, 50) }}
                    </a>
                    <div class="text-muted small">{{ $suggestion->category->name }}</div>
                    <div class="text-muted small">800 vues • {{ $suggestion->created_at->format('d M Y') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>