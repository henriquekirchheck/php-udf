<?php $title = 'Home'; ?>
<?php require_once '../../lib/layout.php'; ?>
<?php require_once '../../src/db.php'; ?>
<?php require_once '../../src/models/Note.php'; ?>

<?php
if (!isset($_GET["id"])) die("No id specified");

$id = intval($_GET["id"]);

$note_model = new Note\Model($db);

$curr = $note_model->getOne($id);

if (isset($_POST["title"]) && isset($_POST["content"])) {
  $title = $_POST["title"];
  if ($title == "") $title = null;
  $content = $_POST["content"];
  if ($content == "") $content = null;

  $note_model->modify($id, new Note\Update($title, $content));

  header("Location: /");
  return;
}

?>

<article>
  <header>Modifique sua nota!</header>
  <form action="/modify/?id=<?= $id ?>" method="post">
    <input type="text" placeholder="Titulo" aria-label="Titulo" name="title" required value="<?= $curr->title ?>">
    <textarea aria-label="Conteudo" name="content" required><?= $curr->content ?></textarea>
    <input type="submit" value="Modificar Nota">
  </form>
</article>