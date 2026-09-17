</head>
<body>

	<!-- Post: advisory
	     Dark masthead, no feature image, and an "at a glance" sidebar that
	     stays in view while the reader scrolls. The sidebar is whatever
	     follows the sidebar marker comment in the post file, exactly as in
	     page-sidebar.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<?php $cyberParts = cyber_page_parts($pagename, !empty($cyberMarkdown)); ?>
	<main class="contentcontainer post post-advisory">
		<div class="layout-sidebar<?php if (trim($cyberParts['sidebar']) == '') { echo ' layout-sidebar-empty'; } ?>">
			<article class="content content-wide prose">
				<div class="section group">
					<?php echo $cyberParts['main']; ?>
				</div>
			</article>
			<?php if (trim($cyberParts['sidebar']) != '') { ?>
				<aside class="sidebar">
					<?php echo $cyberParts['sidebar']; ?>
				</aside>
			<?php } ?>
		</div>
		<?php cyber_post_footer($pagename, $pageauthor, $cyberCategories); ?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
