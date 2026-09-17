</head>
<body>

	<!-- Post archives: card grid
	     Category strip, one wide lead story on the first page, then cards.
	     Filtering works exactly as in the stock template: give an archive
	     page a postcategory tag and it lists only that category.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="contentcontainer archive archive-styled">
		<?php
			$cyberPosts = cyber_all_posts('posts');

			/* Category strip: every category in use that has a page to link to. */
			$cyberAllCategories = array();
			foreach ($cyberPosts as $post) {
				foreach ($post['categories'] as $cat) { $cyberAllCategories[$cat] = true; }
			}
			ksort($cyberAllCategories);

			/* CATEGORY FILTER (from this page's own postcategory tag) */
			$filterCategory = !empty($postcategory) ? strtolower(trim($postcategory)) : '';
			if ($filterCategory !== '') {
				$cyberPosts = array_values(array_filter($cyberPosts, function ($post) use ($filterCategory) {
					return in_array($filterCategory, $post['categories']);
				}));
			}

			echo '<nav class="categorystrip" aria-label="Categories">';
			echo '<a class="chip' . ($filterCategory === '' ? ' chip-current' : '') . ' disable-scrolling-underline" href="archives">All notes</a> ';
			foreach (array_keys($cyberAllCategories) as $cat) {
				if (file_exists('pages/' . $cat . '.html')) {
					echo cyber_category_chips(array($cat), $filterCategory) . ' ';
				}
			}
			echo '</nav>';

			/* Pagination */
			$itemsPerPage = 7;
			$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
			$cyberPagePosts = array_slice($cyberPosts, ($page - 1) * $itemsPerPage, $itemsPerPage);

			if (empty($cyberPagePosts)) {
				echo '<p class="lead">Nothing filed here yet.</p>';
			}

			/* Lead story on the first page only */
			if ($page == 1 && !empty($cyberPagePosts)) {
				echo cyber_post_card(array_shift($cyberPagePosts), 'featured');
			}

			echo '<div class="postgrid">';
			foreach ($cyberPagePosts as $post) { echo cyber_post_card($post, 'card'); }
			echo '</div>';

			cyber_pagination($pagename, $page, ceil(count($cyberPosts) / $itemsPerPage));
		?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
