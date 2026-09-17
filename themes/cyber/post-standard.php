</head>
<body>

	<!-- Post: standard
	     Paper masthead, feature image breaking out of the reading column,
	     then the article. Byline and categories are in the masthead.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="contentcontainer layout-prose post post-standard">
		<?php if ($cyberImageRaw != "" && file_exists($cyberImageRaw)) { ?>
			<figure class="postimage">
				<img class="nodecoration" src="<?php echo $cyberImageRaw; ?>" alt="">
			</figure>
		<?php } ?>
		<article class="content prose">
			<div class="section group">
				<?php
					$cyberParts = cyber_page_parts($pagename, !empty($cyberMarkdown));
					echo $cyberParts['main'];
				?>
			</div>
		</article>
		<?php cyber_post_footer($pagename, $pageauthor, $cyberCategories); ?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
