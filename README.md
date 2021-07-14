# Mai Guides
1. Create SEO friendly guide posts that feature an ordered list of hand-picked posts.
1. Registers a new "Guides" custom post type.
1. Include standard Posts as your Guide content for SEO and internal linking.
1. Requires Advanced Custom Fields plugin to choose posts that are in your guide.
1. For flexibility the plugin does not automatically display your guide content list.
1. Includes a custom widget to show guide content.
1. Includes [guide_toc] shortcode to easily show guide content anywhere in your post content.

## Screenshots
### Table Of Contents
![Mai Guides Table Of Contents](/assets/images/toc.png)<br />
### Backend Metabox (Requires ACF)
![Mai Guides Metabox](/assets/images/metabox.png)

## How To Use
1. Install and activate the plugin as you would any WordPress plugin.
1. Navigation to Dashboard > Guides > Add New to create your first guide.
1. Add your main guide content as you would any WordPress page or post.
1. Scroll down to the "Guides" metabox and select as many posts as you want in your guide.
1. Drag posts in the order you want.
1. Any any content you'd like to show before and/or after the main guide and any entries you have in your guide. This is a great spot for the `[guide_toc]` shortcode.
1. Optionally add the "Guide Table Of Contents" widget in Dashboard > Appearance > Widgets. The widget will not display unless you're on a main guide or guide entry page.

## Table Of Contents
### Default Usage
```
[guide_toc]
```
### Custom Usage
#### Parameters
```
guide_id
```
**Example**
```
[guide_toc guide_id="123"]
```
Display the TOC for a specific guide.<br />
Default: The ID of the main guide of the post you are on.

```
title
```
**Example**
```
[guide_toc title="Welcome To My Guide!"]
```
Add a custom title to the top of the TOC.<br />
Default: The main guide title.

```
title_wrap
```
**Example**
```
[guide_toc title_wrap="h3"]
```
Change the main title wrapping element.
Default: h2

```
entry_wrap
```
**Example**
```
[guide_toc entry_wrap="h4"]
```
Change the entry title wrapping element.
Default: h3

```
class
```
**Example**
```
[guide_toc class="my-custom-class"]
```
Add one or more additional HTML classes to the main container element.

```
image_size
```
**Example**
```
[guide_toc image_size="thumbnail"]
```
Change the image size used for each guide entry.<br />
Default: tiny

## Filters
`maiguides_entry_post_types`
```
// Allow portfolio post type entries to be used as guide entries.
add_filter( 'maiguides_entry_post_types', function( $post_types ) {
	$post_types[] = 'portfolio';
	return $post_types;
});
```

`maiguides_table_of_contents`
```
// Add custom content before and after the guide table of contents.
add_filter( 'maiguides_table_of_contents', function( $content ) {
	$before = 'Add some content before the toc.
	$after  = 'This is some new content after the toc.
	return $before . $content . $after;
});
```

`maiguides_guide_icon`
```
// Replace the icon used for the Guide TOC.
add_filter( 'maiguides_guide_icon', function() {
	return '<img src="/some-img-or-svg">';
});
```
