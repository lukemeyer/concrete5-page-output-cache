<?php
	//Load the PageOutputCache class
	$classes = array(
		'PageOutputCache' => array('library', 'pageoutputcache', null)
	);
	Loader::registerAutoload($classes);

	//get the requested path
	$request = Request::get();
	$path = trim($request->getRequestPath(),'\/');

	//don't load from cache when sending data
	if ( empty($_GET) && empty($_POST)  ){
		//get cache id from cookie or use public id
		$cacheId = isset($_COOKIE[PageOutputCache::CONFIG_KEY]) ? $_COOKIE[PageOutputCache::CONFIG_KEY] : PageOutputCache::PUBLIC_CACHE_ID;

		//check for cache of rendered html
		$cachedPage = Cache::get(PageOutputCache::CACHE_TYPE, $cacheId . ':' . $path);
		if ( $cachedPage !== false ) {
			//output cached page
			echo $cachedPage;
			//echo "Fetched from PageOutputCache: " . $cacheId . ':' . $path;
			exit;
		}
	}
