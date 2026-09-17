</head>
<body>

	<!-- Post: feature
	     Blue marker band with a centred title; the image rides up over the
	     bottom edge of the band. The opening paragraph gets a drop cap.
	     For the long pieces you want people to notice.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="contentcontainer layout-prose post post-feature">
		<?php if ($cyberImageRaw != "" && file_exists($cyberImageRaw)) { ?>
			<figure class="postimage postimage-overlap">
				<img class="nodecoration" src="<?php echo $cyberImageRaw; ?>" alt="">
			</figure>
		<?php } ?>
		<article class="content prose prose-dropcap">
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
