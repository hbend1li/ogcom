<?php 

reset($_GET);
require_once(key($_GET).'.json');
