$( document ).ready( function() {

	// Fill the birthdate fields of the form.
	let dayField = $( '#day_of_birth' );
	let monthField = $( '#month_of_birth' );
	let yearField = $( '#year_of_birth' );

	for( let i = 1; i <= 31; i++ ) {
		let option = $( `<option value="${i}">${i}</option>` );
		dayField.append(option);
	}

	for( let i = 1; i <= 12; i++ ) {
		let option = $( `<option value="${i}">${i}</option>` );
		monthField.append(option);
	}

	for( let i = 2020; i >= 1950; i-- ) {
		let option = $( `<option value="${i}">${i}</option>` );
		yearField.append(option);
	}

	// Student list handlers
	$( '#classes' ).on( 'change', function() {
		$( '#students tbody' ).remove();

		let url = 'student_list_controller.php?id=' + $(this).val();

		$.ajax( {
			url: encodeURI(url)
		} ).done( function(data) {

			let tbody = $( '<tbody></tbody>' );
			tbody.append(data);

			$( '#students > thead' ).after(tbody);

		} ).done( function(data) {

			$( '#students tbody' ).on('click', 'tr', function() {
				$( 'tr[data-stid]' ).removeClass('table-active');
				$(this).addClass( 'table-active' );
			});

		});

	} );

	$( '#delete_student' ).on( 'click', function() {

		if( $( '#students .table-active' ).length ) {
			$('#confirm_del').modal('show');
		}

	} );

	$( '#confirm_del_btn' ).on( 'click', function() {

		let targetEl = $('#students .table-active');
		let elId = targetEl.data('stid');

		let url = 'student_list_controller.php?id=' + elId + '&' + 'del=true';

		$.ajax( {
			url: encodeURI(url)
		} ).done( function(data) {
			$('#students .table-active').remove();
		} );

		$('#confirm_del').modal('hide');

	} );

	// Report handlers
	$( '#show_report' ).on( 'click', function() {
		$( '#report tbody' ).remove();

		let repVal = $( '#reports' ).val();
		repVal = repVal.split("-");

		let code = repVal[0];
		let optId = repVal[1];

		let url = `report_controller.php?code=${code}&id=${optId}`;

		$.ajax( {
			url: encodeURI(url)
		} ).done( function(data) {

			let tbody = $( '<tbody></tbody>' );
			tbody.append(data);

			$( '#report > thead' ).after(tbody);

		} ).done( function(data) {

			$( '#report tbody' ).on('click', 'tr', function() {

				$( '#report tr' ).removeClass('table-active');
				$(this).addClass( 'table-active' );
			} );

		} );

	} );

	// Forms modulation.
	$( '.needs-validation' ).on( 'submit', function() {
		if ( $(this)[0].checkValidity() === false ) {
			event.preventDefault();
			event.stopPropagation();
		}

		$(this).addClass( 'was-validated' );
	} );

} );