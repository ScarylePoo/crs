</head>
<body>

	<!-- Full-bleed sections
	     Same idea as page-home: the page file is a stack of
	     <section class="section-wide"> blocks that manage their own width
	     and background. Use it for landing-style pages (about, services,
	     prices, contact).
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="homelayout layout-sections">
		<?php
			$cyberParts = cyber_page_parts($pagename, false);
			echo $cyberParts['main'];
		?>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
