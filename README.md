# Mai Guides
1. Create SEO friendly guide posts that feature an ordered list of hand-picked posts.
1. Registers a new "Guides" custom post type.
1. Include standard Posts as your Guide content for SEO and internal linking.
1. Requires Advanced Custom Fields plugin to choose posts that are in your guide.
1. For flexibility the plugin does not automatically display your guide content list.
1. Includes a custom widget to show guide content.
1. Includes [guide_toc] shortcode to easily show guide content anywhere in your post content.

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
