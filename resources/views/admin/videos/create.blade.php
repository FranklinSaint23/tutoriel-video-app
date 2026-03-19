<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Upload - Saint Tutos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow rounded-4" style="max-width: 800px;">
        <h2 class="mb-4">Mettre en ligne un tutoriel</h2>
        
        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Titre de la vidéo</label>
                <input type="text" name="title" class="form-control" placeholder="Ex: Apprendre Laravel en 10min">
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Niveau</label>
                    <select name="level" class="form-select">
                        <option value="Débutant">Débutant</option>
                        <option value="Intermédiaire">Intermédiaire</option>
                        <option value="Avancé">Avancé</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Fichier Vidéo (MP4)</label>
                    <input type="file" name="video" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Miniature (Image)</label>
                    <input type="file" name="thumbnail" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">PUBLIER LA VIDÉO</button>
        </form>
    </div>
</body>
</html>