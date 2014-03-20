function editEntry(id) {
	document.form_timetrack.ct_id.value = id;
	document.form_timetrack.task.value = 'edit';
	document.form_timetrack.submit();
}

function sendEntryValues(id) {
	ids = document.getElementById('ct_service').options[document
			.getElementById('ct_service').selectedIndex].id;
	ct_err = 0;

	if (ids == 1) {

		startzeit = document.form_timetrack.ct_sh.value * 60
				+ document.form_timetrack.ct_sm.value * 1;

		endzeit = document.form_timetrack.ct_eh.value * 60
				+ document.form_timetrack.ct_em.value * 1;
		startpause = document.form_timetrack.ct_psh.value * 60
				+ document.form_timetrack.ct_psm.value * 1;
		endpause = document.form_timetrack.ct_peh.value * 60
				+ document.form_timetrack.ct_pem.value * 1;

		if (isNaN(startzeit) || isNaN(endzeit)) {
			throw new TypeError('Arguments must be numbers');
		}

//		msg = 'sz:' + startzeit + ' - ez:' + endzeit + '\\n';
		msg = "";
		if (endzeit < startzeit) {
			document.getElementById('tde').style.backgroundColor = '#DC143C';
			msg += "Arbeitsende liegt vor Arbeitsbegin";
			ct_err = 1;
		}

		if (endpause < startpause) {
			document.getElementById('pde').style.backgroundColor = '#DC143C';
			msg += "Pausenende liegt vor Pausenstart";
			ct_err = 1;
		}

//		if (startpause < startzeit) {
//			document.getElementById('pde').style.backgroundColor = '#DC143C';
//			msg += "Pausenstart liegt vor Arbeitsbegin";
//			ct_err = 1;
//		}

	}

	if (ct_err > 0) {
		alert(msg);
	} else {
		document.form_timetrack.ct_id.value = id;
		document.form_timetrack.task.value = 'submit';
		document.form_timetrack.submit();
	}
}

function deleteEntry(id) {
	if (confirm("Eintrag entfernen?")) {
		document.form_timetrack.ct_id.value = id;
		document.form_timetrack.task.value = 'delete';
		document.form_timetrack.submit();

	}

	return false;
}

function doReset() {
	document.form_timetrack.ct_id.value = '';
	document.form_timetrack.task.value = 'display';
	document.form_timetrack.submit();
}

function hideme() {
	id = document.getElementById('ct_service').options[document
			.getElementById('ct_service').selectedIndex].id;
	if (id == 1) {
		document.getElementById('timedata1').className = 'showcell';
		document.getElementById('timedata2').className = 'showcell';
		document.getElementById('qtyrow').className = 'hidecell';
	} else {
		document.getElementById('timedata1').className = 'hidecell';
		document.getElementById('timedata2').className = 'hidecell';
		document.getElementById('qtyrow').className = 'showcell';

	}
	return false;
}

function textCounter(field, counter, maxlimit) {
	if (field.value.length > maxlimit)
		field.value = field.value.substring(0, maxlimit);
	else
		counter.value = maxlimit - field.value.length;
}
