<?php
/* Smarty version 3.1.31, created on 2017-11-24 17:43:44
  from "C:\Program Files (x86)\Ampps\www\hwcalc\hwc_calc\home.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a18a0a0118431_83715491',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '052d29eef490050a3cb807fbf2c9a4b3b80d5809' => 
    array (
      0 => 'C:\\Program Files (x86)\\Ampps\\www\\hwcalc\\hwc_calc\\home.html',
      1 => 1511562709,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a18a0a0118431_83715491 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once 'C:\\Program Files (x86)\\Ampps\\www\\hwcalc\\hwc_calc\\smarty\\libs\\plugins\\modifier.date_format.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>HWC Calculator Home</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php $_smarty_tpl->_assignInScope('months', array('January','February','March','April','May','June','July','August','September','October','November','December'));
?>
<div class="historicalReturns">
	<h1>Historic Returns</h1>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['fundYears']->value, 'years');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['years']->value) {
?>
	<?php if ($_smarty_tpl->tpl_vars['years']->value != "1969") {?> 
	<div class="returnsSection">
		<div class="years">
			<p><?php echo $_smarty_tpl->tpl_vars['years']->value;?>
</p>
		</div>
		<div class="row">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['months']->value, 'month');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['month']->value) {
?>
			<div class="container">
				<p class="months"><?php echo $_smarty_tpl->tpl_vars['month']->value;?>
</p>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['historicalReturns']->value, 'output');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['output']->value) {
?>
					<?php if ($_smarty_tpl->tpl_vars['years']->value == smarty_modifier_date_format($_smarty_tpl->tpl_vars['output']->value['period'],"%Y")) {?>
						<?php if ($_smarty_tpl->tpl_vars['month']->value == smarty_modifier_date_format($_smarty_tpl->tpl_vars['output']->value['period'],"%B")) {?>
							<div class="returns" <?php if (strstr($_smarty_tpl->tpl_vars['output']->value['returns'],"-")) {?>id='negative'<?php }?>>
								<?php echo $_smarty_tpl->tpl_vars['output']->value['returns'];?>

							</div>
						<?php }?>
					<?php }?>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

			</div>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

		</div>
	</div>
	<?php }?>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

</div>
	
</body>
</html>
<?php }
}
