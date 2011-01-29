<?php

ob_start('ob_gzhandler');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>SimpleMath</title>
	<style type="text/css">
	body {
		background: #fff;
		display: block;
		font: 14px Helvetica, "Lucida Grande", sans-serif;
		height: 100%;
		max-width: 800px;
		margin: auto;
		padding: 0;
	}
	.equations {
		margin: 30px 10px;
		min-height: 80%;
	}
	.equation {
		font-size: 20px;
		margin-bottom: 15px;
		white-space: nowrap;
	}
	.equation span.row-num {
		background-color: #ffc;
		border: 2px solid #ffc;
		border-radius: 18px;
		-moz-border-radius: 18px;
		-webkit-border-radius: 18px;
		color: #999;
		display: block;
		float: right;
		font-size: 12px;
		font-weight: bold;
		margin-top: 3px;
		padding: 4px 5px 4px 6px;
		text-shadow: #fff 1px 1px 1px;
	}
	.equation input {
		border: 0;
		border-bottom: 3px solid #ddd;
		font-size: 20px;
		outline: none;
	}
	.equation .math {
		width: 60%;
	}
	.equation .result {
		text-align: right;
		width: 30%;
	}
	.buttons {
		margin: 0 10px 60px;
	}
	.legal {
		bottom: 5px;
		color: #bbb;
		display: none;
		font-size: 11px;
		margin: 0 10px 10px;
	}
	</style>
</head>
<body>

<div class="equations">

	<div class="equation">
		<input type="text" class="math">
		<span class="equals">=</span>
		<input type="text" class="result">
	</div>

</div>

<p class="buttons"><a href="#" class="new-eq">New</a></p>

<p class="legal">Copyright &copy; 2011 Alex King. All rights reserved.</p>

<script type="text/javascript">
<?php

include('js/jquery-1.4.4.min.js');
include('js/jquery-ui-1.8.9.custom.min.js');
include('js/jquery.caret.min.js');

?>
$(function() {
	var keyUp = true;
	$('.equation .math').live('keydown', function(e) {
// handle insertion of values from previous lines
		keyUp = true;
		if (e.ctrlKey) {
			var row = e.keyCode - 48;
			if (row >= 0 && row <= 9) {
				$result = $('.equations .equation[data-row=' + row + '] .result');
				if ($result.size()) {
					$result.closest('.equation').find('.row-num').animate({
						backgroundColor: '#fc9',
						borderColor: '#fc9'
					}, 500, function() {
						$(this).animate({
							backgroundColor: '#ffc'
						}, 500);
					});
					var eq = $(this).val();
					var a = eq.substring(0, $(this).caret().start);
					var b = eq.substring($(this).caret().end, eq.length);
					var c = $result.val();
					var pos = ('' + a + c).length;
					$(this).val('' + a + c + b).caret({ start: pos, end: pos});
					$(this).trigger('calc');
					keyUp = false;
				}
			}
		}
		if (e.metaKey && e.keyCode == 8) {
// create new one first if this is the last one
			if ($('.equation').size() == 1) {
				$('.new-eq').click();
			}
			$(this).closest('.equation').remove();
		}
	}).live('keyup', function(e) {
		if (!keyUp) {
			return;
		}
// do math
		$(this).trigger('calc');
// if key = enter, set focus to result and select for easy copying
		if (e.which === 13) {
			e.stopPropagation();
			$(this).closest('.equation').find('.result').focus().select();
		}
	}).live('calc', function() {
// simple math
		var val = null;
		var valError = '...';
		try {
			eval('val = ' + $(this).val().replace(/[^0-9 \.\)\(\+\-\*\/]+/g, '') + ';');
		}
		catch(err) {
			val = valError;
		}
		if (isNaN(val)) {
			val = valError;
		}
		$(this).closest('.equation').find('.result').val(val);
	}).filter(':first').focus();
	$('.equation .result').live('keydown', function(e) {
		if (e.metaKey && e.keyCode == 8) { // this is duplicated above - should clean it up
// create new one first if this is the last one
			if ($('.equation').size() == 1) {
				$('.new-eq').click();
			}
			$(this).closest('.equation').remove();
		}
	}).live('keyup', function(e) {
		if (e.which === 13) {
// auto-create new row on return key
			e.stopPropagation();
			$('.new-eq').click();
		}
	});
	$('.new-eq').click(function() {
// create new row
		var $eqs = $('.equation');
		$('.equation:first').clone().find(':input').val('').end().appendTo('.equations');
		$('.equations').trigger('renumber').find('.equation:last :input:first').focus();
		$('html, body').animate({scrollTop: $('body').height()}, 800);
		return false;
	});
	$('.equations').bind('renumber', function() {
// re-number rows
		var i = 0;
		var row = '';
		$($('.equation').get().reverse()).each(function() {
			$(this).find('span.row-num').remove();
			if (i == 10) {
				row = 0;
			}
			else if (i >= 1 && i <= 9) {
				row = i;
			}
			else {
				row = '';
			}
			$(this).attr('data-row', row);
			if (row != '') {
				$(this).prepend('<span class="row-num">' + row + '</span>');
			}
			i++;
		});
	});
	$('body').keydown(function(e) {
		if (e.ctrlKey && e.keyCode == 78) {
			$('.new-eq').click();
			keyUp = false;
		}		
	});
});
</script>

<!--

Copyright 2011 Alex King, All rights reserved.

FEATURES:

- math is done by JavaScript, anything more than basic algebra is an accident
- strips non-numeric garbage when evaluating (you can leave $ and , in your numbers)
- hit ENTER from expression side takes you to result side and selects result for easy copying
- hit ENTER again (while in the result field) to get a new row
- CTRL+N at any time will give you a new row
- enter the result of one of the previous 10 rows (numbered) at the cursor position by using CTRL+(1-9)
- numbers reset on every new row so that referencing the previous row is always 1, the second back is always 2, etc.
- if you get so many rows that they extend off the screen, the window automatically scrolls up like an old-school calculator tape
- delete the current row with CMD+DELETE

NOTES:

The CTRL key isn't a good choice for windows users, however I am not one. Please fork as needed.

TODO:

- README
- set focus to row before the one that is deleted on delete
- position credits
- clear button?
- html storage to save page locally
- allow re-ordering of rows?

TEST:

- iPhone
- Android
- BlackBerry (6)

-->

</body>
</html>