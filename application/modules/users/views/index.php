<script type="text/javascript">
	jQuery(document).ready(function($){
		
		//===== Datatables =====//
		var modulePath = "<?php echo site_url($this->router->fetch_module())  ?>",
			datatableJsonPath = modulePath + '/datatables',
			editPath = modulePath + '/edit_user',
			deletePath = modulePath + '/delete_user',
			viewPath = modulePath + '/view_user';
		
		var myTable = $('.dTable').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": datatableJsonPath,
			"aoColumns":[
		        {"mDataProp": "username", "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "fullname", "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "group", "bSearchable": false, "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "email", "bSearchable": false, "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": function ( source, type, val ) {
					var btnTemplate = ('\
					<a href="'+editPath+'/'+source.id+'" class="tablectrl_small bDefault tipS" original-title="Edit">\
						<span class="iconb" data-icon="&#xe1db;"></span>\
					</a>\
					<a href="'+deletePath+'/'+source.id+'" class="tablectrl_small bDefault tipS" original-title="Delete" onclick="return confirm(\'Are you sure you want to delete '+source.username+'\');">\
						<span class="iconb" data-icon="&#xe136;"></span>\
					</a>\
					<a href="'+viewPath+'/'+source.id+'" class="tablectrl_small bDefault tipS" original-title="View">\
						<span class="iconb" data-icon="&#xe1f7;"></span>\
					</a>\
					');
					return btnTemplate;
				}, "bSearchable": false, "bSortable": false, "sClass": 'tableActs'}
		    ],
			"fnInitComplete": function () {
				// After ajax call complete
	        }
		});
		
		//===== Form elements styling =====//

		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});
	});
</script>

<div class="infoMessage"><?php echo $data['message'];?></div>

<div class="widget">
	<div class="whead">
		<div class="titleIcon"><span class="icon-users"></span></div>
		<h6>User List</h6>
        <a href="<?php echo site_url('users/add_user') ?>" class="buttonS bGreen formSubmit">Add User</a>
		<div class="clear"></div>
	</div>
	<div class="shownpars">
		<table class="dTable" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Group</th>
					<th>Email</th>
					<th>Actions</th>
				</tr>
			</thead><!-- thead -->
			<tbody>
			</tbody><!-- tbody -->
		</table><!-- .dTable -->
	</div><!-- .shownpars -->
</div>
