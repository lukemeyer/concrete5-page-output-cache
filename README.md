concrete5 Page Output Cache
===========================

## How it works

### Cache IDs
Each user is assigned a random cache id the first time a page is cached while they are logged in, this is stored in their user account and set in a cookie. This allows the caching of customized pages. There is a default "public" cache id that all non logged-in users will use so all public users see the same cached pages.

### Caching
When a page is rendered `site_events.php` hooks the `on_page_output` event and stores the entire page in the concrete5 cache using the user's cache id + the requested path as the key. This currently ignores the cache settings for blocks and pages, so the whole page is always cached.

### Reading
When a request is made `site_post_autoload.php` is called from the dispatcher very early (right after the core classes are loaded), it checks the cache for a page matching the cache id + requested path and outputs it if found.

## Installing
1. Copy libraries/pageoutputcache.php into your libraries directory
2. Copy config/site_events.php and config/site_post_autoload.php into your config directory (or copy their contents if you already have these files)

## Configuration
You can edit the class constants in libraries/pageoutputcache.php to customize your install.

```PHP
const CONFIG_KEY = 'cacheid';
const CACHE_TYPE = 'PageOutputCachePage';
const PUBLIC_CACHE_ID = 'public';
const CACHE_LIFESPAN = 21600;
```
`CONFIG_KEY` - the string used as the key for the user config and cookie
`CACHE_TYPE` - string used for "type" when calling concrete5's Cache::set method
`PUBLIC_CACHE_ID` - string used as the default cache id for non logged-in users
`CACHE_LIFESPAN` - time in seconds before a cached page expires (6 hrs default)

## Notes
- Requests with GET or POST values are never served from the cache

