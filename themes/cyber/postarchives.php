</head>
<body>

	<!-- Post archives: compact index
	     Date, title, categories. One line per post, for when there are a
	     lot of them. Honours the postcategory tag like the card layout.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="contentcontainer archive archive-list">
		<?php
			$cyberPosts = cyber_all_posts('posts');

			if (!empty($postcategory)) {
				$filterCategory = strtolower(trim($postcategory));
				$cyberPosts = array_values(array_filter($cyberPosts, function ($post) use ($filterCategory) {
					return in_array($filterCategory, $post['categories']);
				}));
			}

			$itemsPerPage = 20;
			$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
			$cyberPagePosts = array_slice($cyberPosts, ($page - 1) * $itemsPerPage, $itemsPerPage);

			if (empty($cyberPagePosts)) {
				echo '<p class="lead">Nothing filed here yet.</p>';
			}

			echo '<ul class="postlist">';
			foreach ($cyberPagePosts as $post) { echo cyber_post_card($post, 'row'); }
			echo '</ul>';

			cyber_pagination($pagename, $page, ceil(count($cyberPosts) / $itemsPerPage));
		?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
