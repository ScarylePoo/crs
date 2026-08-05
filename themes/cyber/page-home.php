</head>
<body>

	<!-- Full-bleed layout
	     Sections in the page file control their own width and background,
	     so this layout deliberately omits .contentcontainer.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="homelayout">
		<?php
			$filename = file_get_contents("./pages/" . $pagename . ".html");
			// Parse and replace shortcodes
			$parsed_content = parse_shortcodes($filename);
			echo $parsed_content;
		?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
