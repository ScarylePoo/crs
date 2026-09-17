</head>
<body>

	<!-- Primary Page Layout
	     The title lives in the masthead (header.php), so this layout is
	     just the reading column.
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<main class="contentcontainer layout-prose">
		<div class="content prose">
			<div class="section group">
				<?php
					$cyberParts = cyber_page_parts($pagename, !empty($cyberMarkdown));
					echo $cyberParts['main'];
				?>
			</div>
		</div>
	</main>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
