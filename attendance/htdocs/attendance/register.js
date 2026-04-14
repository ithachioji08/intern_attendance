window.onload = ()=>{
	patternEnable();
	let staffOnly  = document.getElementsByClassName("staffOnly");
	let supervisor = document.getElementsByName("supervisor");
	let staffOnlyChar = document.getElementsByClassName("staffOnlyChar");
	let svChar = document.getElementsByClassName("svChar");
	document.getElementsByName("position").forEach(element => {
		element.addEventListener('click',()=>{
			disabledColor = "gray";
			enabledColor  = "black";
			switch(element.value){
				case "1"://課長
					workPattern(staffOnly,staffOnlyChar,false,enabledColor,supervisor,svChar,false,enabledColor);
					break;
				case "2"://次長
					workPattern(staffOnly,staffOnlyChar,false,enabledColor,supervisor,svChar,false,enabledColor);
					break;
				case "3"://社員
					workPattern(staffOnly,staffOnlyChar,false,enabledColor,supervisor,svChar,false,enabledColor);
					break;
				case "4"://部長
					workPattern(staffOnly,staffOnlyChar,true,disabledColor,supervisor,svChar,true,disabledColor);
			}
		})
	});
};

function workPattern(staffOnly,staffOnlyChar,staffBool,staffColor,supervisor,svChar,svBool,svColor){
	for(let i=0;i<staffOnly.length;i++){
		staffOnly[i].disabled        = staffBool;
		staffOnly[i].checked         = false;
		staffOnlyChar[i].style.color = staffColor;
	}
	supervisor[0].disabled = svBool;
	svChar[0].style.color = svColor;
}