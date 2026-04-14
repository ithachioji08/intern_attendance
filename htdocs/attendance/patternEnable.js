function patternEnable(){
	let slowAble     = document.getElementsByClassName("slowAble");
	let fastAble     = document.getElementsByClassName("fastAble");
	let fastAbleChar = document.getElementsByClassName("fastAbleChar");
	let slowAbleChar = document.getElementsByClassName("slowAbleChar");
	let patternAlb   = document.getElementsByName("patternAlb");
	document.getElementsByName("patternChar").forEach(element => {
		element.addEventListener('click',()=>{
			disabledColor = "gray";
			enabledColor  = "black";
			switch(element.value){
				case "早出":
					enabledChange(slowAble,slowAbleChar,true,disabledColor);
					enabledChange(fastAble,fastAbleChar,false,enabledColor);
					if(radioCheck(patternAlb) == "G"){
						patternAlb[patternAlb.length-1].checked = false;
					}
					break;
				case "通常":
					enabledChange(slowAble,slowAbleChar,true,disabledColor);
					enabledChange(fastAble,fastAbleChar,true,disabledColor);
					if(["D","E","F","G"].indexOf(radioCheck(patternAlb)) != -1){
						patternAlb.forEach(element => {
							element.checked = false;
						});
					}
					break;
				case "遅出":
					enabledChange(slowAble,slowAbleChar,false,enabledColor);
					enabledChange(fastAble,fastAbleChar,false,enabledColor);
			}
		})
	});
}

function enabledChange(btn,char,bool,color){
	for(let i=0;i<btn.length;i++){
		btn[i].disabled     = bool;
		char[i].style.color = color;
	}
}

//ラヂオボタンの値を取得
function radioCheck(pattern){
	char = "";
	pattern.forEach(element => {
		if (element.checked){char = element.value;}
	});
	return char;
}