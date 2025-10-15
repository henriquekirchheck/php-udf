<?php $title = 'Home'; ?>
<?php require_once '../../lib/layout.php'; ?>
<?php require_once '../../src/db.php'; ?>
<?php require_once '../../src/models/Note.php'; ?>

<?php

if (isset($_POST["title"]) && isset($_POST["content"])) {
  $note_model = new Note\Model($db);

  $title = $_POST["title"];
  $content = $_POST["content"];

  $note_model->insert(new Note\Insert($title, $content));

  header("Location: /");
  return;
}

?>

<article>
  <header>Escreva sua nota!</header>
  <form action="/add" method="post">
    <input type="text" placeholder="Titulo" aria-label="Titulo" name="title" required>
    <textarea aria-label="Conteudo" name="content" required></textarea>
    <input type="submit" value="Adicionar Nota">
  </form>
</article>