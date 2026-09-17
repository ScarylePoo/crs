</head>
<body>

	<!-- Content + sidebar
	     Everything in the page file above the sidebar marker comment is the
	     main column; everything below it is the sidebar. No marker, no
	     sidebar — the layout collapses to a single column. The marker is an
	     HTML comment containing just the word: sidebar
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<?php $cyberParts = cyber_page_parts($pagename, !empty($cyberMarkdown)); ?>
	<main class="contentcontainer layout-sidebar<?php if (trim($cyberParts['sidebar']) == '') { echo ' layout-sidebar-empty'; } ?>">
		<div class="content content-wide prose">
			<div class="section group">
				<?php echo $cyberParts['main']; ?>
			</div>
		</div>
		<?php if (trim($cyberParts['sidebar']) != '') { ?>
			<aside class="sidebar">
				<?php echo $cyberParts['sidebar']; ?>
			</aside>
		<?php } ?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
