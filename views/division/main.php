<div class='container fade-in'>
	<ul class='breadcrumb'>
		<li><a href='./'>Home</a></li>
		<li class='active'><?php echo $division->full_name; ?></li>
	</ul>

	<div class='page-header'>
		<h2><strong><img src='assets/images/game_icons/large/<?php echo $division->short_name; ?>.png' /> <?php echo $division->full_name; ?> Division</strong></h2>
	</div>

	<p><?php echo $division->description; ?></p><hr>

	<div class='row'>
		<div class='col-md-8'>
			<div class='panel panel-primary'>
				<div class='panel-heading'>Currently Active Platoons</div>
				<div class='list-group'>

					<?php if (count($platoons)) : ?>
						<?php foreach ($platoons as $platoon) : ?>
							<a href='divisions/<?php echo $division->short_name; ?>/<?php echo $platoon->number; ?>' class='list-group-item'>
								<h5 class='pull-right text-muted'><?php echo ordSuffix($platoon->number); ?> Platoon</h5>
								<h4 class='list-group-item-heading'><strong><?php echo $platoon->name; ?></strong></h4>
								<p class='list-group-item-text text-muted'><?php echo $platoon->leader_rank . " " . $platoon->leader_name; ?></p>
							</a>
						<?php endforeach; ?>
					<?php else : ?>
						<li class='list-group-item'>No platoons currently exist for this division.</li>
					<?php endif; ?>

				</div>
			</div>
		</div>

		<div class='col-md-4'>
			<div class='panel panel-info'>
				<div class='panel-heading'>Division Command Staff</div>
				<?php if (count($division_leaders)) : ?>

					<?php foreach($division_leaders as $leader) : ?>
						<a href='member/<?php echo $leader->member_id; ?>' class='list-group-item'>
							<h5 class='pull-right'><i class='fa fa-shield fa-2x text-muted'></i></h5>
							<h4 class='list-group-item-heading'><strong><?php echo $leader->rank . " " . $leader->forum_name; ?></strong></h4>
							<p class='list-group-item-text text-muted'><?php echo $leader->position_desc; ?></p>
						</a>
					<?php endforeach; ?>

				<?php else : ?>
					<li class='list-group-item'>No leadership currently exists for this division.</li>
				<?php endif; ?>
			</div>
		</div>

	</div>


<!-- 	if ($game_id == 2) {

// statistics
$toplistMonthly = null;
$monthly = get_monthly_bf4_toplist(10);
$i = 1;
foreach ($monthly['players'] as $mem) {
$toplistMonthly .= "<tr data-id='{$mem['member_id']}'><td class='text-center text-muted'>{$i}</td><td>{$mem['rank']} {$mem['forum_name']}</td><td><strong>{$mem['aod_games']}</strong></td></tr>";
$i++;
}

$toplistDaily = null;
$daily = get_daily_bf4_toplist(10);

$i = 1;
foreach ($daily as $mem) {
$toplistDaily .= "<tr data-id='{$mem['member_id']}'><td class='text-center text-muted'>{$i}</td><td>{$mem['rank']} {$mem['forum_name']}</td><td><strong>{$mem['aod_games']}</strong></td></tr>";
$i++;
}

// end statistics

}
// bf statistics
if ($game_id == 2) {
 -->
 <!-- 
 <div class='row col-md-12 margin-top-50'>
 
 	<div class='page-header'>
 		<h3>Division Statistics</h3>
 	</div>
 </div>
 
 <div class='row'>
 
 	<div class='col-md-6'>
 		<div class='panel panel-primary toplist'>
 
 			<div class='panel-heading'>Daily Most Active Players</div>
 			<table class='table table-striped table-hover'>
 				{$toplistDaily}
 			</table>
 		</div>
 	</div>
 
 	<div class='col-md-6'>
 		<div class='panel panel-primary toplist'>
 
 			<div class='panel-heading'>Monthly Most Active Players</div>
 			<table class='table table-striped table-hover'>
 				{$toplistMonthly}
 			</table>
 		</div>
 	</div>
 </div>
 -->


</div>
