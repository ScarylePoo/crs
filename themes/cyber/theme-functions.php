<?php
/*********************************************
* CYBER THEME - SHARED HELPERS
**********************************************
* Included once from header.php. Everything a
* layout needs that the framework doesn't
* already provide lives here, so the layout
* files themselves stay short and readable.
*
* Nothing in required/ is touched. Every function
* is prefixed cyber_ so it can't collide with the
* framework or a plugin.
**********************************************/


/* Which masthead each layout gets. Anything not
   listed falls back to "paper", so a layout copied
   in from another theme still renders sensibly.

   paper  dotted paper, title left, page width
   prose  dotted paper, title aligned to the reading column
   band   blue marker band, centred title
   ink    dark, orange rule — advisories
   sunk   centred title on sunk paper — archives
   site   no page title, site name only (the -notitle layouts) */
function cyber_masthead_for($pagelayout) {
	$map = array(
		'page-html'            => 'prose',
		'page-md'              => 'prose',
		'page-sections'        => 'paper',
		'page-home'            => 'paper',
		'page-sidebar'         => 'paper',
		'page-sidebar-md'      => 'paper',
		'post-standard'        => 'prose',
		'post-standard-md'     => 'prose',
		'post-feature'         => 'band',
		'post-feature-md'      => 'band',
		'post-advisory'        => 'ink',
		'post-advisory-md'     => 'ink',
		'postarchives'         => 'sunk',
		'postarchives-styled'  => 'sunk',
	);
	if (substr($pagelayout, -8) === '-notitle') { return 'site'; }
	return isset($map[$pagelayout]) ? $map[$pagelayout] : 'paper';
}


/* Read a page file and split it on an optional
   <!-- sidebar --> marker. Everything above the
   marker is the main column, everything below is
   the sidebar. A page without the marker simply
   has an empty sidebar, and the sidebar layouts
   collapse to one column.

   Returns array('main' => html, 'sidebar' => html). */
function cyber_page_parts($pagename, $markdown = false) {
	$raw   = file_get_contents("./pages/" . $pagename . ".html");
	$parts = preg_split('/<!--\s*sidebar\s*-->/i', $raw, 2);

	$out = array('main' => '', 'sidebar' => '');
	foreach (array('main', 'sidebar') as $i => $key) {
		if (!isset($parts[$i])) { continue; }
		$html = parse_shortcodes($parts[$i]);
		if ($markdown) { $html = from_markdown($html); }
		$out[$key] = $html;
	}
	return $out;
}


/* Breadcrumb trail built from the URL path.
   A segment only becomes a link if a page with
   that name really exists, so a folder without
   an index page degrades to plain text instead
   of linking to a 404.

   pages/posts/ is special-cased to point at the
   archives page, because that is what a reader
   would expect "Notes" to mean. */
function cyber_breadcrumb($pagename, $siteTitle, $postsLabel = 'Notes', $postsPage = 'archives') {
	$segments = explode('/', $pagename);
	array_pop($segments); // the current page is the H1, not a crumb

	$crumbs = array('<a href="">' . htmlspecialchars($siteTitle) . '</a>');
	$path   = '';
	foreach ($segments as $segment) {
		$path .= ($path === '' ? '' : '/') . $segment;
		$label = ucwords(str_replace('-', ' ', $segment));
		$target = $path;
		if ($path === 'posts') { $label = $postsLabel; $target = $postsPage; }

		if (file_exists('pages/' . $target . '.html')) {
			$crumbs[] = '<a href="' . htmlspecialchars($target) . '">' . htmlspecialchars($label) . '</a>';
		} else {
			$crumbs[] = '<span>' . htmlspecialchars($label) . '</span>';
		}
	}
	return implode(' <span class="crumbsep" aria-hidden="true">/</span> ', $crumbs);
}


/* Category list from a raw pagecategory value.
   Mirrors the archive templates: lower-cased,
   trimmed, and "uncategorized" when empty. */
function cyber_categories($raw) {
	$categories = array();
	foreach (explode(',', (string) $raw) as $cat) {
		$cat = strtolower(trim($cat));
		if ($cat !== '') { $categories[] = $cat; }
	}
	if (empty($categories)) { $categories = array('uncategorized'); }
	return $categories;
}


/* Category chips. By framework convention each
   category has a page of the same name at the
   pages root; the chip links there when it
   exists and is plain text when it doesn't. */
function cyber_category_chips($categories, $current = '') {
	$chips = array();
	foreach ($categories as $cat) {
		$label = htmlspecialchars(ucwords(str_replace('-', ' ', $cat)));
		$class = 'chip' . ($cat === $current ? ' chip-current' : '');
		if (file_exists('pages/' . $cat . '.html')) {
			$chips[] = '<a class="' . $class . ' disable-scrolling-underline" href="' . htmlspecialchars($cat) . '">' . $label . '</a>';
		} else {
			$chips[] = '<span class="' . $class . '">' . $label . '</span>';
		}
	}
	return implode(' ', $chips);
}


/* Rough reading time. 220 words a minute, never
   less than one. */
function cyber_reading_time($pagename) {
	$raw   = file_get_contents("./pages/" . $pagename . ".html");
	$words = str_word_count(strip_tags($raw));
	return max(1, (int) round($words / 220));
}


/* Every post in pages/posts/, newest first.
   Same required-fields rule as the archive
   templates: a post needs a title, date, image
   and excerpt tag to be listed. */
function cyber_all_posts($postsFolder = 'posts') {
	$posts = array();
	foreach (glob("pages/$postsFolder/*.html") as $file) {
		$contents = file_get_contents($file);
		preg_match('/<!--\s+pagetitle:(.*?)\s+-->/s',    $contents, $t);
		preg_match('/<!--\s+pagedate:(.*?)\s+-->/s',     $contents, $d);
		preg_match('/<!--\s+pageimage:(.*?)\s+-->/s',    $contents, $i);
		preg_match('/<!--\s+pageexcerpt:(.*?)\s+-->/s',  $contents, $e);
		preg_match('/<!--\s+pagecategory:(.*?)\s+-->/s', $contents, $c);
		preg_match('/<!--\s+pageauthor:(.*?)\s+-->/s',   $contents, $a);
		if (!($t && $d && $i && $e)) { continue; }

		$posts[] = array(
			'title'      => trim($t[1]),
			'date'       => trim($d[1]),
			'image'      => trim($i[1]),
			'excerpt'    => trim($e[1]),
			'author'     => $a ? trim($a[1]) : '',
			'filename'   => $file,
			'url'        => $postsFolder . '/' . basename($file, '.html'),
			'categories' => cyber_categories($c ? $c[1] : ''),
		);
	}
	usort($posts, function ($a, $b) {
		return strtotime($b['date']) - strtotime($a['date']);
	});
	return $posts;
}


/* Up to $limit other posts, ones sharing a
   category first, then simply the most recent. */
function cyber_related_posts($pagename, $categories, $limit = 3) {
	$shared = array();
	$others = array();
	foreach (cyber_all_posts() as $post) {
		if ($post['url'] === $pagename) { continue; }
		if (array_intersect($categories, $post['categories'])) {
			$shared[] = $post;
		} else {
			$others[] = $post;
		}
	}
	return array_slice(array_merge($shared, $others), 0, $limit);
}


/* One archive/related card. $size is "card"
   (grid), "featured" (wide lead story) or
   "row" (compact list line). */
function cyber_post_card($post, $size = 'card') {
	$url   = htmlspecialchars($post['url']);
	$title = htmlspecialchars($post['title']);
	$date  = formatDate($post['date'], 'M j, Y');
	if ($date === false) { $date = htmlspecialchars($post['date']); }

	if ($size === 'row') {
		return '<li class="postrow">'
			. '<span class="postrow-date">' . $date . '</span>'
			. '<a class="postrow-title" href="' . $url . '">' . $title . '</a>'
			. '<span class="postrow-cats">' . cyber_category_chips($post['categories']) . '</span>'
			. '</li>';
	}

	$html  = '<article class="postcard postcard-' . $size . '">';
	if ($post['image'] !== '' && file_exists($post['image'])) {
		$html .= '<a class="postcard-image disable-scrolling-underline" href="' . $url . '" tabindex="-1" aria-hidden="true">'
			. '<img class="nodecoration" src="' . htmlspecialchars($post['image']) . '" alt="" loading="lazy"></a>';
	}
	$html .= '<div class="postcard-body">';
	$html .= '<p class="postcard-meta"><span>' . $date . '</span> ' . cyber_category_chips($post['categories']) . '</p>';
	$html .= '<h3 class="postcard-title"><a href="' . $url . '">' . $title . '</a></h3>';
	$html .= '<p class="postcard-excerpt">' . htmlspecialchars($post['excerpt']) . '</p>';
	if ($post['author'] !== '') {
		$html .= '<p class="postcard-author">' . htmlspecialchars($post['author']) . '</p>';
	}
	$html .= '</div></article>';
	return $html;
}


/* The block under every post: who wrote it, a
   few more posts, and the way back. */
function cyber_post_footer($pagename, $pageauthor, $categories) {
	$initial = strtoupper(substr(trim($pageauthor), 0, 1));
	echo '<footer class="postfooter">';
	echo '<div class="authorcard">';
	echo '<span class="avatar blob" aria-hidden="true">' . htmlspecialchars($initial) . '</span>';
	echo '<p><span class="eyebrow">Written by</span><strong>' . htmlspecialchars($pageauthor) . '</strong></p>';
	echo '<a class="btn btn-ink btn-small disable-scrolling-underline" href="archives">All notes</a>';
	echo '</div>';

	$related = cyber_related_posts($pagename, $categories, 3);
	if (!empty($related)) {
		echo '<p class="eyebrow">Keep reading</p>';
		echo '<div class="postgrid postgrid-related">';
		foreach ($related as $post) { echo cyber_post_card($post, 'card'); }
		echo '</div>';
	}
	echo '</footer>';
}


/* Pagination, shared by the three archive
   layouts. The cache key preserves ?page=, so
   this is safe with caching on. */
function cyber_pagination($pagename, $page, $totalPages) {
	if ($totalPages < 2) { return; }
	echo '<nav class="pagination" aria-label="Pages">';
	for ($i = 1; $i <= $totalPages; $i++) {
		if ($i == $page) {
			echo '<span class="blob" aria-current="page">' . $i . '</span> ';
		} else {
			echo '<a class="blob disable-scrolling-underline" href="' . htmlspecialchars($pagename) . '?page=' . $i . '">' . $i . '</a> ';
		}
	}
	echo '</nav>';
}
?>
