<!-- DO NOT EDIT THIS FILE; it is auto-generated from readme.txt -->
# Topher's WordPress.tv Importer

Fetches an RSS feed from WordPress.tv and injects items into the Post type.

**Contributors:** [topher1kenobe](http://profiles.wordpress.org/topher1kenobe)  
**Tags:** [video](http://wordpress.org/plugins/tags/video)  
**Requires at least:** 5.7  
**Tested up to:** 5.7  
**Stable tag:** 1.0  
**License:** [GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)  

## Description ##

Fetches an RSS feed from WordPress.tv and injects items into the Post type. The video is inserted into the top of the content as a shortcode. Settings are all hardcoded.  You should change:

* Feed URL
* Post type (if you want to inject into a custom post type)
* Category IDs

Please read Usage for how to make it do an import.

## Installation ##

1. Use the Plugin Add New menu item in WordPress.
1. Activate the plugin through the 'Plugins' menu in WordPress

## Usage ##

Go to your homepage and append this to the URL: ?wptvimport=yes

I recommend you exclude ?* from your cache plugin.

If you want to run this on a schedule I recommend you use an external cron and simply call this url on whatever schedule you wish.

Note: this plugin keeps a unique ID for each item/post, so running the script multiple times won't make duplicates.  This also means that if you need a post updated you should delete it and re-run the import script.

Note: the pubdate on the post is when the video was uploaded to WordPress.tv, NOT when the event happened, nor when the item was imported.

## Changelog ##

### 1.0 ###
* Initial release


