<?
include_once "fhq_security.php";

function echo_menu()
{
	echo "<table cellspacing=0 cellpadding=10>";
	//add admins menu 
	if( $_SESSION['role'] == 'admin' )
	{
		echo "
		<tr bgcolor='#760505'>
			<td>Admin's Panel:</td>
			<td width=30px> </td>
			<td><a href='quest.php?action=add'> Add New Quest</a></td>
			<td width=30px> </td>
			<!-- td><a href='main.php?action=completed'> Users </a></td -->
			<!-- td width=30px> </td -->
			<td><a href='admin.php?action=feedback'>Messages</a></td>
			<td width=30px> </td>
			<td><a href='main.php?action=feedback'></a></td>
			<td></td>
			<td></td>
		</tr>";
	};

	echo "
	<tr>
		<td><a href='main.php?action=allow' class='allow'></a></td>
		<td width=30px> </td>
		<td><a href='main.php?action=process' class='process' ></a></td>
		<td width=30px> </td>
		<td><a href='main.php?action=completed' class='completed'></a></td>
		<td width=30px> </td>
		<td><a href='main.php?action=top100' class='top100'></a></td>
		<td width=30px> </td>
		<td><a href='main.php?action=feedback_my' class='feedback'></a></td>
	</tr>";

	echo "</table>";
};
//---------------------------------------------------------------------	

?>
