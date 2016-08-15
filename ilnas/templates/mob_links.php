<ul>
	<? 
	  foreach ($Place->tied as $key => $value) {
	?>
	  	<li><a href="index.php?loc=<?=$key?>"><?=$value['link']?></a>
			<? if ($value['notice'] !== "") echo "<span class='link_notice'>(".$value['notice'].")</span>"; ?>
		</li>
	<?
	  }
	?>
</ul>


