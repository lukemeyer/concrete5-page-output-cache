<?php
class PageOutputCache {

	const CONFIG_KEY = 'cacheid';
	const CACHE_TYPE = 'PageOutputCachePage';
	const PUBLIC_CACHE_ID = 'public';
	const CACHE_LIFESPAN = 21600; //6 hours

	public function savePageToCache ( $pageContent ) {
		if ( !isset($_COOKIE[PageOutputCache::CONFIG_KEY]) ){
			PageOutputCache::setCacheId();
		}

		$c = Page::getCurrentPage();
		$path = trim($c->cPath,'\/');
		$u = new User();
		if ( $c->cIsSystemPage !== '1' ){
			//Cache page
			Cache::set(PageOutputCache::CACHE_TYPE, PageOutputCache::getCacheId() . ':' . $path, $pageContent, PageOutputCache::CACHE_LIFESPAN);
		}
	}

	public static function setCacheId( $return = false ) {

		$cacheId = PageOutputCache::PUBLIC_CACHE_ID;

		//save to user if logged in
		$u = new User();
		if ( $u->isLoggedIn() ){

			//get cache id from user record
			$cacheId = $u->config(PageOutputCache::CONFIG_KEY);
			if ( empty($cacheId) ) {
				//make a new cache id
				$bytes = openssl_random_pseudo_bytes(4);
				$cacheId = bin2hex($bytes);

				$u->saveConfig(PageOutputCache::CONFIG_KEY,$cacheId);
			}
		}

		setcookie(PageOutputCache::CONFIG_KEY,$cacheId,time()+60*60*24*30,'/');

		if ( $return ) {
			return $cacheId;
		}
	}

	public static function getCacheId() {
		$u = new User();
		if ( $u->isLoggedIn() ){
			//get cache id from user record
			$cacheId = $u->config(PageOutputCache::CONFIG_KEY);

			//check cookie
			if ( empty($cacheId) && isset($_COOKIE[PageOutputCache::CONFIG_KEY]) ){
				$cacheId = $_COOKIE[PageOutputCache::CONFIG_KEY];
			}

			if ( empty($cacheId) ) {
				//make a new id
				$cacheId = PageOutputCache::setCacheId(true);
			}

			return $cacheId;

		} else {
			//public cache
			return PageOutputCache::PUBLIC_CACHE_ID;
		}

	}
}
