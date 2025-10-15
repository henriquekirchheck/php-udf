<?php $title = 'Home'; ?>
<?php require_once '../../lib/layout.php'; ?>
<?php require_once '../../src/db.php'; ?>
<?php require_once '../../src/models/Note.php'; ?>
<?php require __DIR__ . '/../../vendor/autoload.php'; ?>

<?php

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

if (!isset($_GET["id"])) die("No id specified");

$htmlSanitizer = new HtmlSanitizer(
  (new HtmlSanitizerConfig())->allowSafeElements()
);


$id = intval($_GET["id"]);

$note_model = new Note\Model($db);

$curr = $note_model->getOne($id);

$title = $htmlSanitizer->sanitize($curr->title);
$content = $htmlSanitizer->sanitize($curr->content);

?>

<article>
  <header>
    <h1><?= $title ?></h1>
  </header>
  <main><?= $content ?></main>
  <footer><a href="/">Voltar</a></footer>
</article>