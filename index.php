<?php error_reporting(E_ALL); ?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP comparisions</title>
<link href='//fonts.googleapis.com/css?family=Inconsolata&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Roboto&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<style>
* {
	font-size: 0.98em;
}
table {
	border-collapse: collapse;
}
th, td {
	margin: 0;
	padding: 7px;
	border: 1px solid #ddd;
}
th {
	font-weight: normal;
	background-color: #eee;
}
thead th:first-child {
	border-left: 0;
	border-top: 0;
	background-color: transparent;
}
th, .code {
	font-family: Inconsolata, monospace;
	white-space: pre;
}
tbody th {
	text-align: left;
}
td {
	text-align: center;
}
span.true {
	color: green;
}
span.false {
	color: red;
	opacity: 0.5;
}
.strict {
	display: none;
}
.hi {
	background-color: #ffa;
}
div {
	-moz-user-select: none;
	-khtml-user-select: none;
	-webkit-user-select: none;
	-o-user-select: none;
	user-select: none;
}
td, div {
	font-family: Roboto, sans-serif;
}
</style>
</head>
<body>
<?php
$values = array(
	'""',
	'null',
	'false',
	'true',
	'0',
	'"0"',
	'-1',
	'"-1"',
	'array()',
	'array(null)',
	'array(false)',
	'array(0)',
	'array("0")'
);
?><table>
	<thead>
		<tr>
			<th></th>
			<?php
			for($i = 0; $i < count($values); $i++) {
				?><th><?php echo formatValue(getValue($values, $i)); ?></th><?php
			}
			?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>if(...)</th>
			<?php
			for($i = 0; $i < count($values); $i++) {
				$Vi = getValue($values, $i);
				?><td><?php echo formatCompare($Vi); ?></td><?php
			}
			?>
		</tr>
		<tr>
			<th>empty</th>
			<?php
			for($i = 0; $i < count($values); $i++) {
				$Vi = getValue($values, $i);
				?><td><?php echo formatCompare(empty($Vi)); ?></td><?php
			}
			?>
		</tr>
		<tr>
			<th>is_null</th>
			<?php
			for($i = 0; $i < count($values); $i++) {
				$Vi = getValue($values, $i);
				?><td><?php echo formatCompare(is_null($Vi)); ?></td><?php
			}
			?>
		</tr>
		<tr>
			<th>is_numeric</th>
			<?php
			for($i = 0; $i < count($values); $i++) {
				$Vi = getValue($values, $i);
				?><td><?php echo formatCompare(is_numeric($Vi)); ?></td><?php
			}
			?>
		</tr>
		<tr>
			<th>intval</th>
			<?php
			for($i = 0; $i < count($values); $i++) {
				$Vi = getValue($values, $i);
				?><td class="code"><?php echo formatValue(intval($Vi)); ?></td><?php
			}
			?>
		</tr>
		<?php
			for($j = 0; $j < count($values); $j++) {
				$Vj = getValue($values, $j);
				?><tr>
					<th><span class="loose">== </span><span class="strict">===</span> <?php echo formatValue($Vj); ?></th>
					<?php
					for($i = 0; $i < count($values); $i++) {
						$Vi = getValue($values, $i);
						?><td>
							<span class="loose"><?php echo formatCompare($Vi == $Vj); ?></span>
							<span class="strict"><?php echo formatCompare($Vi === $Vj); ?></span>
						</td><?php
					}
				?></tr><?php
			}
		?>
	</tbody>
</table>
<div style="user-select:none">
	<label><input type="checkbox" id="strict"> strict comparisions</label>
</div>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>(function() {
	$('#strict')
		.on('change', function() {
			$('.strict')[this.checked ? 'show' : 'hide']();
			$('.loose')[this.checked ? 'hide' : 'show']();
		})
		.trigger('change')
	;
	function hiCell(cell, hi) {
		var row = cell.parentNode.rowIndex, col = cell.cellIndex;
		$('thead tr th').eq(col)[hi ? 'addClass' : 'removeClass']('hi');
		$('tbody tr').eq(row - 1).find('th').eq(0)[hi ? 'addClass' : 'removeClass']('hi');
		$(cell)[hi ? 'addClass' : 'removeClass']('hi');
	}
	$('td').on('mouseleave', function() {
		hiCell(this, false);
	});
	$('td').on('mouseenter', function() {
		hiCell(this, true);
	});
})();
</script>
</body>
</html><?php

function getValue($values, $index) {
	eval('$v = ' . $values[$index] . ';');
	return $v;
}

function formatValue($o) {
	switch(gettype($o)) {
		case 'integer':
			$result = $o;
			break;
		case 'string':
			$result = '\'' . addslashes($o) . '\'';
			break;
		case 'NULL':
			$result = 'null';
			break;
		case 'boolean':
			$result = $o ? 'true' : 'false';
			break;
		case 'array':
			$values = array();
			foreach($o as $v) {
				$values[] = formatValue($v);
			}
			$result = 'array(' . implode(', ', $values) . ')';
			break;
		default:
			die(gettype($o));
	}
	return $result;
}

function formatCompare($criteria) {
	if($criteria) {
		return '<span class="true">&#x2713;</span>';
	}
	else {
		return '<span class="false">&#x2717;</span>';
	}
}
