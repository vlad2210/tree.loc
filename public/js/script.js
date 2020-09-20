
$(document).ready(function(){
	fill_parent_items();
	fill_treeview();

	function fill_treeview() {
		$.ajax({
			url:"/main/list",
			success:function(data){
				var obj = JSON.parse(data);
				$('#treeview').treeview({
						data: data,
						multiSelect: $('#chk-select-multi').is(':checked'),
						onNodeSelected: function(event, node) {
						  $('#rmv-btn').attr('data-itm-id', node.id);
						  $('#rmv-btn').attr('data-itm-pid', node.pid);
						},
						onNodeUnselected: function(event, node) {

						}
					});
				},
		})
	}

	function fill_parent_items() {
		$.ajax({
			url:'/main/sub',
			success:function(data){
				$('#parent_item').html(data);
			}
		});
	}

	$('#treeview_form').on('submit', function(event) {
		event.preventDefault();

		$.ajax({
			url: "/main/add",
			method: "POST",
			data: $(this).serialize(),
			success: function(data){
				fill_treeview();
				fill_parent_items();
				$('#treeview_form')[0].reset();
				alert('New Item added successuly..!');
			}
		})
	});

	$('#rmv-btn').on('click', function(e) {
		var id = $(this).attr('data-itm-id');
		var pid = $(this).attr('data-itm-pid');
		if (confirm('This is very dangerous, you shouldn`t do it! Are you really really sure?')) {
			$.ajax({
				url:'/main/delete',
				method:'POST',
				data:'id='+id+'&pid='+pid,
				success: function(data) {
					fill_treeview();
					fill_parent_items();
					$('#treeview_form')[0].reset();
					alert('Item deleted successuly..!');
				}
			})
		}
	});
});

