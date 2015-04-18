<?php while ( have_posts() ) : the_post(); 
$custom_values=get_post_custom($post->ID);
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php the_title(); ?></title>
    <meta name="description" content="<?php echo strip_tags(get_the_excerpt()); ?>">
    <meta name="author" content="<?php echo get_the_author(); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/css/reveal.css">
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/css/theme/<?php echo $custom_values["reveal_theme"][0]; ?>" id="theme">
    <!-- Code syntax highlighting -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/lib/css/zenburn.css">
    <!-- Printing and PDF exports -->
    <style type="text/css">
    code{
        white-space: pre;
      }
<?php
      if ($custom_values["reveal_custom_css"][0] != "")
      {
        echo "//* This is a single-line comment *//";
        echo $custom_values["reveal_custom_css"][0];
      }
?>
    </style>
    <script>
      var link = document.createElement( 'link' );
      link.rel = 'stylesheet';
      link.type = 'text/css';
      link.href = window.location.search.match( /print-pdf/gi ) ? '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/css/print/pdf.css' : '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/css/print/paper.css';
      document.getElementsByTagName( 'head' )[0].appendChild( link );
    </script>
    <!--[if lt IE 9]>
    <script src="lib/js/html5shiv.js"></script>
    <![endif]-->
    </head>
    <body>
    	<div class='reveal'>
    		<div class='slides'>
        <section data-markdown data-separator="^\n---\n$" data-separator-vertical="^\n--\n$" data-separator-notes="^Notes:">
          <script type="text/template">
<?php
            echo get_the_content();
?>        
          </script>
        </section>
    </div>
  </div>
    <script src="<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/lib/js/head.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/js/reveal.js"></script>
    <script>
      // Full list of configuration options available at:
      // https://github.com/hakimel/<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js#configuration
      Reveal.initialize({
        controls: true,
        slideNumber: true,
        progress: true,
        history: true,
        center: false,
        help: false,
        transition: 'slide', // none/fade/slide/convex/concave/zoom
        //theme: Reveal.getQueryHash().theme, // available themes are in /css/theme
        //transition: Reveal.getQueryHash().transition || 'linear', // default/cube/page/concave/zoom/linear/fade/none
        // Optional <?php echo plugin_dir_url( __FILE__ ) ?>reveal.js plugins
        dependencies: [
          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/lib/js/classList.js', condition: function() { return !document.body.classList; } },
          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/markdown/marked.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/markdown/markdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/highlight/highlight.js', async: true, condition: function() { return !!document.querySelector( 'pre code' ); }, callback: function() { hljs.initHighlightingOnLoad(); } },
          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/zoom-js/zoom.js', async: true }
//          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/notes/notes.js', async: true }
//          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/search/search.js', async: true, condition: function() { return !!document.body.classList; }, }
//          { src: '<?php echo plugin_dir_url( __FILE__ ) ?>reveal.js/plugin/remotes/remotes.js', async: true, condition: function() { return !!document.body.classList; } }
        ]
      });
    </script>
  </body>
</html>
      <?php
    endwhile;
// end of the loop.