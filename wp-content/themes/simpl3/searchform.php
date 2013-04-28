		<form method="get" id="searchform" action="<?php
 bloginfo('home'); ?>/">
			<div>
				<input type="text" value="<?php if ($s == '') echo "What Can We Find For You?"; else echo wp_specialchars($s, 1); ?>" name="s" id="sfr" onblur="if (this.value == '') {this.value = '<?php if ($s == '') echo "What Can We Find For You?"; else echo wp_specialchars($s, 1); ?>';}"  onfocus="if (this.value == '<?php if ($s == '') echo "What Can We Find For You?"; else echo wp_specialchars($s, 1); ?>') {this.value = '';}" />
				<input type="hidden" id="ss" value="search" />
			</div>
		</form>
