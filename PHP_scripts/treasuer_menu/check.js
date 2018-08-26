function isEmpty(string){
	return string.length === 0;
}


function validate(formularz){
	if(isEmpty(formularz.elements["student_name"].value)){
		alert("Podaj imię!");
		return false;
	} else{
		return true;
	}
}

//tu bedzie reszta funkcji np do sprwdzania czy email jest ok