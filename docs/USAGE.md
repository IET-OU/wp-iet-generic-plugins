# Usage

[← Return to README][home]

Below you'll find examples of how to use this collection of WordPress plugins.

## `[ wp_query ]` shortcode

Shortcode wrapper around WordPress core class: [`WP_Query`][] — a subset of arguments is supported.

1. Full-content list, based on a tag — [evidence of the month][] example:
    ```
    [wp_query tag="evidence-of-the-month" post_type="evidence"]
    ```

2. Multiple post-types, "richlist" format — [recent content][] example:
    ```
    [wp_query
        post_type="evidence,project" format="richlist" posts_per_page="6" orderby="date" order="DESC"
    ]
    ```

3. Embed a whole page — [about page][] example::
    ```
    [wp_query query="pagename=about" format="full"]
    ```


## `[ tagcloud ]` shortcode

Shortcode wrapper around WordPress core function: [`wp_tag_cloud`][] — all arguments are supported.

1. Default taxonomy — [post_tag][] example:
    ```
    [tagcloud]
    ```

    Various [tag cloud][] demonstrations:

2. Alternative taxonomy — "evidence_hub_sector":
    ```
    [tagcloud taxonomy="evidence_hub_sector"]
    ```

3. Format "list" (vertical list):
    ```
    [tagcloud format="list" x_display_count="after"]
    ```


## `[ simple_menu ]` shortcode

Embed a pre-defined menu or sub-menu — [help page][] example:
```
[simple_menu menu="Main" sub="Help"]
```


## Simple embed plugin

Enable a whole WordPress site to be embedded, without page chrome.

URL _without_ simple-embed:
* http://trickytopic.juxtalearn.net/juxtalearn-quiz/4/

URL _with_ simple-embed:
* http://trickytopic.juxtalearn.net/juxtalearn-quiz/4/?embed=1


## [CDN_JS plugin][]

A plugin to quickly incorporate Javascripts via [CDN][].

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
[← Return to README][home]

[home]: https://github.com/IET-OU/wp-iet-generic-plugins
[evidence of the month]: http://iet-lace-approval.open.ac.uk/evidence-of-the-month/
[recent content]: http://evidence.laceproject.eu/#recent-evidence
[about page]: http://iet-lace-approval.open.ac.uk/tests-and-demos/wp-query-test/#about
[help page]: http://trickytopic.juxtalearn.net/help/
[tag cloud]: http://iet-lace-approval.open.ac.uk/tests-and-demos/tag-cloud-test/
[post_tag]: http://evidence.laceproject.eu/evidence/#tags
[CDN_JS plugin]: https://gist.github.com/nfreear/1d459d4a0a21d90d21c3#
[CDN]: http://www.jsdelivr.com/#!anchorjs "content delivery network"
[`WP_Query`]: https://codex.wordpress.org/Class_Reference/WP_Query
[`wp_tag_cloud`]: https://codex.wordpress.org/Function_Reference/wp_tag_cloud

