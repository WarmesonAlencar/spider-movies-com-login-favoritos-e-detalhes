<?php
session_start();
include('includes/conexao.php');
include('includes/funcoesAPI.php');

$filmes = buscarFilmes('b224536c0803f50498a7118ee808a91e');
$logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Filmes TMDB</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .trailer-btn {
      margin-top: 10px;
      padding: 6px 12px;
      font-weight: bold;
      background-color: #e62429;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .trailer-btn:hover {
      background-color: #b71c1c;
    }
  </style>
</head>
<body>
  <h1>Spider Movies</h1>
  <?php if ($logado): ?>
    <p>Bem-vindo! <a href="logout.php">Sair</a></p>
  <?php else: ?>
    <p><a href="login.php">Login</a> | <a href="registrar.php">Registrar</a></p>
  <?php endif; ?>

  <div class="container">
    <?php foreach ($filmes as $filme): ?>
      <div class="card" data-filme-id="<?= $filme['id'] ?>">
        <img src="https://image.tmdb.org/t/p/w500<?= $filme['poster_path'] ?>" alt="<?= $filme['title'] ?>">
        <div class="trailer-container"></div>
        <button class="trailer-btn" onclick="tocarTrailer(this)">Assistir Trailer</button>
        <h3><?= $filme['title'] ?></h3>
        <p><?= $filme['overview'] ?></p>

        <?php if ($logado): ?>
          <?php
            $usuario_id = $_SESSION['usuario_id'];
            $id_filme = $filme['id'];

            $stmt = $conn->prepare("SELECT * FROM favoritos WHERE usuario_id = ? AND id_filme = ?");
            $stmt->bind_param("ii", $usuario_id, $id_filme);
            $stmt->execute();
            $result = $stmt->get_result();
            $ja_favoritado = $result->num_rows > 0;
          ?>

          <?php if ($ja_favoritado): ?>
            <form action="api/remover_favorito.php" method="post">
              <input type="hidden" name="id_filme" value="<?= $filme['id'] ?>">
              <button type="submit">Remover dos Favoritos</button>
            </form>
          <?php else: ?>
            <form action="api/favoritar.php" method="post">
              <input type="hidden" name="id_filme" value="<?= $filme['id'] ?>">
              <input type="hidden" name="titulo" value="<?= $filme['title'] ?>">
              <input type="hidden" name="poster" value="<?= $filme['poster_path'] ?>">
              <input type="hidden" name="sinopse" value="<?= $filme['overview'] ?>">
              <button type="submit">Favoritar</button>
            </form>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <script>
function tocarTrailer(botao) {
    const container = botao.parentElement;
    const filmeId = container.getAttribute('data-filme-id');

    fetch(`https://api.themoviedb.org/3/movie/${filmeId}/videos?api_key=b224536c0803f50498a7118ee808a91e&language=pt-BR`)
        .then(response => response.json())
        .then(data => {
            const trailer = data.results.find(video => video.type === "Trailer" && video.site === "YouTube");
            if (trailer) {
                container.querySelector('.trailer-container').innerHTML = `
                    <iframe width="300" height="169"
                        src="https://www.youtube.com/embed/${trailer.key}?autoplay=1&mute=0&controls=1"
                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    </iframe>`;
                botao.style.display = "none"; // esconde o botão após o clique
            }
        });
}
</script>

</body>
</html>
