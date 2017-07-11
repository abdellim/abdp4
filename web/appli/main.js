/*var holidays = ["14/07/2017"];

var Jsem = function (date) {
		
	if (date.getDay() == 2 || date.getDay() == 0) { // La semaine commence à 0 = Dimanche
		return [false];
	} else {
		return [true];
	}
	if($.inArray.holidays){
	var Jfer = function (date) {
	var datestring = jQuery.datepicker.formatDate('dd/mm/yy', date);
	return [ holidays.indexOf(datestring) == -1 ];
	};
}	
};*/


var unavailableDates = ["01-05", "01-11", "25-12"];


function disabledays(date) {
var string = jQuery.datepicker.formatDate('dd-mm', date);
    var day = date.getDay();
		var joursem = day != 0 && day != 2;
    var jourferme = (unavailableDates.indexOf(string) == -1);
        return [joursem && jourferme];
    

};



$(function(){

  $('#datepicker').attr( 'readOnly' , 'true' ).datepicker({

    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    beforeShowDay: disabledays
  });
});

