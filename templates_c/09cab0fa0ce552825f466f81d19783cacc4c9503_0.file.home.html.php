<?php
/* Smarty version 3.1.31, created on 2017-11-26 23:40:30
  from "C:\Program Files (x86)\Ampps\www\hcocalc\hwc_calc\templates\home.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a1b973e0965d2_35566439',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '09cab0fa0ce552825f466f81d19783cacc4c9503' => 
    array (
      0 => 'C:\\Program Files (x86)\\Ampps\\www\\hcocalc\\hwc_calc\\templates\\home.html',
      1 => 1511757628,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a1b973e0965d2_35566439 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once 'C:\\Program Files (x86)\\Ampps\\www\\hcocalc\\hwc_calc\\smarty\\libs\\plugins\\modifier.date_format.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>HWC Calculator Home</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="css/style.css">
<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery_3.2.1.js"><?php echo '</script'; ?>
>
</head>

<body>
<div class="calcMenu">
	<a href="javascript:void(0);" id="historicReturns" class="active">Historic Returns</a>
	<a href="javascript:void(0);" id="returnStats">Return Stats</a>
	<a href="javascript:void(0);" id="quantStats">Quant Stats</a>
</div>
<div class="contentContainer">
<?php $_smarty_tpl->_assignInScope('months', array('January','February','March','April','May','June','July','August','September','October','November','December'));
?>
<div class="historicReturns hwcCalc">
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

<div style="clear:both;"></div>
</div>
	
<div class="quantStats hwcCalc">
	<h1>Quant Stats</h1>
	
	<p><span>Cumulative Return:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['cumulativeReturn'],2));?>
%</span></p>
	<p><span>1 Year Return:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['cumulativeReturnOne'],2));?>
%</span></p>
	<p><span>1 Year Return (Moving):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['cumulativeReturnOneMoving'],2));?>
%</span></p>
	<p><span>2 Year Return:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['cumulativeReturnTwo'],2));?>
%</span></p>
	<p><span>2 Year Return (Moving):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['cumulativeReturnTwoMoving'],2));?>
%</span></p>
	<p><span>CAGR:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['cagr'],2));?>
%</span></p>
	<p><span>Geometric Mean:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['geomean'],2));?>
%</span></p>
	<p><span>Sharpe Ratio:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['sharpe'],2));?>
</span></p>
	<p><span>Sharpe Ratio (Annualized):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['sharpeAnn'],2));?>
</span></p>
	<p><span>Sortino:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['sortino'],2));?>
</span></p>
	<p><span>Sortino (Annualized):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['sortinoAnn'],2));?>
</span></p>
	<p><span>Jensen's Alpha:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['jenAlpha'],2));?>
</span></p>
	<p><span>Jensen's Alpha (Monthly):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['jenAlphaMn'],2));?>
</span></p>
	<p><span>Standard Deviation:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['stdDev'],2));?>
%</span></p>
	<p><span>Standard Deviation (Annualized):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['stdDevAnn'],2));?>
%</span></p>
	<p><span>Downside Deviation:</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['dwnsideDev'],2));?>
%</span></p>
	<p><span>Downside Deviation (Annualized):</span> <span><?php echo sprintf("%.2f",round($_smarty_tpl->tpl_vars['calc']->value['dwnsideDevAnn'],2));?>
%</span></p>
</div>

</div>

<?php echo '<script'; ?>
>
	$('document').ready(function(){
		$('.calcMenu a').click(function(){
			var id = $(this).attr('id');
			$('.calcMenu a').each(function(){
				if($(this).hasClass('active') == true && $(this).attr('id') != id){
					$(this).removeClass('active');
				}else{
					if($(this).hasClass('active') != true && $(this).attr('id') == id){
						$(this).addClass('active');
					}
				}
			});		
			$('.hwcCalc').animate({
				opacity: 0,
			}, 50, function() {
				$('.hwcCalc').css('visibility', 'hidden');
			});		
			$('.'+id).animate({
				opacity: 1,
			}, 100, function(){
				$('.'+id).css('visibility', 'visible');
			});
		});
	});
<?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
