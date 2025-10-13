<?php

$db = new PDO('pgsql:host=localhost;port=5432;dbname=app', 'app', 'app', [PDO::ATTR_PERSISTENT => true]);
