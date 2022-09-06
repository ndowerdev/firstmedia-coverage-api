<?php

require 'vendor/autoload.php';


Flight::route('/api/search', array('FirstMedia', 'searchArea'));


Flight::start();
