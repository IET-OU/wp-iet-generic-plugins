# Usage

[← Return to README](/IET-OU/wp-iet-generic-plugins)

## `[ wp_query ]` shortcode

Shortcode wrapper around WordPress core class: [`WP_Query`](https://codex.wordpress.org/Class_Reference/WP_Query) — a subset of arguments is supported.

Full-content list, based on a tag — http://iet-lace-approval.open.ac.uk/evidence-of-the-month/:
```
[wp_query tag="evidence-of-the-month" post_type="evidence"]
```

Multiple post-types, "richlist" format - http://evidence.laceproject.eu:
```
[wp_query
    post_type="evidence,project" format="richlist" posts_per_page="6" orderby="date" order="DESC"
]
```

About page - http://iet-lace-approval.open.ac.uk/tests-and-demos/wp-query-test/#about:
```
[wp_query query="pagename=about" format="full"]
```


## `[ tagcloud ]` shortcode

Shortcode wrapper around WordPress core function: [`wp_tag_cloud`](https://codex.wordpress.org/Function_Reference/wp_tag_cloud) — all arguments are supported.

Default taxonomy -  "post_tag" - http://evidence.laceproject.eu/evidence/#tags:
```
[tagcloud]
```

http://iet-lace-approval.open.ac.uk/tests-and-demos/tag-cloud-test/:

Alternative taxonomy - "evidence_hub_sector":
```
[tagcloud taxonomy="evidence_hub_sector"]
```

Format "list" (vertical list):
```
[tagcloud format="list" x_display_count="after"]
```


## `[ simple_menu ]` shortcode

http://trickytopic.juxtalearn.net/help/:
```
[simple_menu menu="Main" sub="Help"]
```


## Simple embed plugin

URL _without_ simple-embed:
* http://trickytopic.juxtalearn.net/juxtalearn-quiz/4/

URL _with_ simple-embed:
* http://trickytopic.juxtalearn.net/juxtalearn-quiz/4/?embed=1


## [CDN_JS plugin](https://gist.github.com/nfreear/1d459d4a0a21d90d21c3)

Quickly incorporate Javascripts via CDN.

Define in `wp_config.php`:

```php
<?php

# Space-separated URLs.
define( 'CDN_JS', '//cdn.jsdelivr.net/anchorjs/1.1.1/anchor.min.js //example.org/path/to/b.js' );

# Optionally, add inline Javascript.
define( 'CDN_JS_INLINE', ' anchors.add() ' );

?>
```


---
[← Return to README](/IET-OU/wp-iet-generic-plugins)

