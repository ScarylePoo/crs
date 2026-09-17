</head>
<body>

	<!-- Primary Page Layout (no page title; the masthead shows the site name)
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
