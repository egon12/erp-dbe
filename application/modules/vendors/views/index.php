<script type="text/javascript">
	jQuery(document).ready(function($){
		
		//===== Datatables =====//
		var modulePath = "<?php echo site_url($this->router->fetch_module()); ?>",
			datatableJsonPath = modulePath + '/datatables',
			editPath = modulePath + '/edit',
			deletePath = modulePath + '/delete',
			viewPath = modulePath + '/view';
			
		var myTable = $('.dTable').dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": datatableJsonPath,
			"aoColumns":[
				{"mDataProp": "name", "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "address", "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "phone", "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "email", "sDefaultContent": "", "sType": "natural"},
		        {"mDataProp": "description", "bSearchable": false, "sDefaultContent": "", "sType": "natural", "fnRender": function ( oObj ) {
					var description = (oObj.aData['description'] ? oObj.aData['description'] : 'null');
					return description;
					}
				},
		        {"mDataProp": function ( source, type, val ) {
					var btnTemplate = ('\
					<a href="'+editPath+'/'+source.id+'" class="tablectrl_small bDefault tipS" original-title="Edit">\
						<span class="iconb" data-icon="&#xe1db;"></span>\
					</a>\
					<a href="'+deletePath+'/'+source.id+'" class="tablectrl_small bDefault tipS" original-title="Delete" onclick="return confirm(\'Are you sure you want to delete '+source.name+'?\');">\
						<span class="iconb" data-icon="&#xe136;"></span>\
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
		<div class="titleIcon"><span class="icos-basket2"></span></div>
		<h6><?php echo lang('index_heading'); ?></h6>
        <a href="<?php echo site_url('vendors/add') ?>" class="buttonM bGreen formSubmit">Add Vendors</a>
		<div class="clear"></div>
	</div>
	<div class="shownpars">
		<table class="dTable" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Email</th>
					<th>Description</th>
					<th>Actions</th>
				</tr>
			</thead><!-- thead -->
			<tbody>
			</tbody><!-- tbody -->
		</table><!-- .dTable -->
	</div><!-- .shownpars -->
</div>
