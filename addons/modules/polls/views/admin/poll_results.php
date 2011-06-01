<h2><?php echo $poll['title']; ?> <em>(<?php echo $total_votes; ?> total votes)</em></h2>

<?php if($options): ?>
	<ul id="results">
		<?php foreach($options as $option): ?>
			<li>
				<p>
					<?php echo $option['title']; ?>
					<em>(<?php echo $option['votes']; ?> votes)</em>
				</p>
				<div style="width: <?php echo $option['percent']; ?>%;">
					<?php echo $option['percent']; ?>%
				</div>
				<?php if (count($option['other']) > 0): ?>
					<ul class="other">
						<?php foreach($option['other'] as $other): ?>
							<li><?php echo $other['text']; ?> <em>&mdash; <?php echo date('l, F jS, Y', $other['created']); ?></em></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>