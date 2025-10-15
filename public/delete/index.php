<?php $title = 'Home'; ?>
<?php require_once '../../lib/layout.php'; ?>
<?php require_once '../../src/db.php'; ?>
<?php require_once '../../src/models/Note.php'; ?>

<?php
if (!isset($_GET["id"])) die("No id specified");

$id = intval($_GET["id"]);

$note_model = new Note\Model($db);

$curr = $note_model->getOne($id);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $note_model->delete($id);
  header("Location: /");
  return;
}

?>

<article>
  <header>Você tem certeza? (Isso é irreversivel)</header>
  <form action="/delete/?id=<?= $id ?>" method="post">
    <fieldset role="group">
      <a role="button" href="/">Voltar</a>
      <input type="submit" class="contrast" value="DELETAR">
    </fieldset>
  </form>
</article>