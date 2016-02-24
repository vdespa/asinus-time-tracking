function filterList() {
	document.adminForm.submit();
}

function editEntry(id) {
	document.adminForm.cid.value = id;
	document.adminForm.option = "com_asinustimetracking";
	document.adminForm.task.value = "timetrackedit";
	document.adminForm.submit();
}