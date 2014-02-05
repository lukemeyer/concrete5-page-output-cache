<?php
Events::extend('on_page_output', 'PageOutputCache', 'savePageToCache', 'libraries/pageoutputcache.php');
Events::extend('on_user_login', 'PageOutputCache', 'setCacheId', 'libraries/pageoutputcache.php');
