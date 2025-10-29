<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <link rel="stylesheet" href="/assets/css/pico.min.css">
  <script src="/assets/js/htmx.min.js"></script>

  <title><?php echo $title; ?></title>
</head>

<body>
  <main id="main" class="container" hx-target="this" hx-swap="innerHTML" hx-boost="true">
    <?php echo $content; ?>
  </main>
</body>

</html>