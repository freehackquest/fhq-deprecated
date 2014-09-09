
<table bgcolor="#FFF000">
	<tr>
		<td valign="top">
			Quests<br>
			<table bgcolor="#FFFFF0">
				<tr>
					<td>
						Status:
						<select>
							<option>All</option>
							<option>Open</option>
							<option>Current</option>
							<option>Completed</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Subject:
						<select>
							<option>Any</option>
							<option>stego</option>
							<option>crypto</option>
							<option>forensics</option>
							<option>ppc</option>
							<option>web</option>
							<option>network</option>
							<option>reverse</option>
							<option>trivia</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<a href="#">Apply filters</a>
					</td>
				</tr>
				<tr>
					<td>
						Pages:
						<a href="#">1</a> 
						<a href="#">2</a> 
						<a href="#">3</a> 
					</td>
				</tr>
			<?php
				for ($i = 0; $i < 10; $i++) {
			?>
				
				<tr>
					<td id="task<?php echo $i; ?>"> <a href="#">#38 Restore</a> (+200) / Current</td>
				</tr>

			<?php
				}
			?>

			</table>
		</td>
		<td valign="top">
			Description<br>
			<table bgcolor="#FFFFF0">
				<tr>
					<td>
							info about quest
					</td>
				</tr>
				<tr>
					<td>
						<a href="#">take</a>
						<a href="#">pass</a>
					</td>
				</tr>
			</table>
			
			
		</td>
	</tr>
</table>
			
