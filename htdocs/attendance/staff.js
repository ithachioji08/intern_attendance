


window.onload =function(){
	patternEnable();
	let modal = document.getElementById("modal");
	let layer = document.getElementById("back-layer");

	let department   = document.getElementsByName("department");
	let person       = document.getElementsByName("person");
	let patternChar  = document.getElementsByName("patternChar");
	let patternAlb   = document.getElementsByName("patternAlb");
	let start        = document.getElementsByName("start");
	let end          = document.getElementsByName("end");
	let reason       = document.getElementsByName("reason");
	let printArea    = document.getElementById("printArea");
	let nonPrintARea = document.getElementById("nonPrintArea");
	
	document.getElementById("visible").addEventListener('click',()=>{
		let startDate   = new Date(start[0].value);
		let endDate     = new Date(end[0].value);
		if(blanckCheck(department,person,patternChar,patternAlb,start,end,reason)){
			alert("必須項目を入力してください");
		}else if(endDate - startDate < 0){
			alert("終了日時が開始日時よりも前になっています");
		}else if(timeCheck(startDate,endDate,radioCheck(patternChar),radioCheck(patternAlb))){
			alert("残業時間内に通常勤務時間が重なっています");
		}else{
			document.getElementById("modal-dep").value  = department[0].value;
			document.getElementById("modal-per").value  = person[0].value;
			document.getElementById("modal-ptn").value  = patternChar[0].value + " " + patternAlb[0].value;
			document.getElementById("modal-time").value = start[0].value + "~" + end[0].value;
			document.getElementById("modal-rsn").value  = reason[0].value;
			modal.style.display = "block";
			layer.style.display = "block";
		}
	});
	
	document.getElementById("close").addEventListener('click',()=>{
		modal.style.display = "none";
		layer.style.display = "none";
	});
	document.getElementById("print").addEventListener('click',()=>{
		
		document.getElementById("printDep").innerHTML = department[0].value;
		document.getElementById("printPer").innerHTML = person[0].value;
		document.getElementById("printPtn").innerHTML = patternChar[0].value + " " + patternAlb[0].value;
		let startDate = printDate(start[0].value);
		let endDate   = printDate(end[0].value);
		document.getElementById("printTime").innerHTML = startDate + "~" + endDate;
		document.getElementById("printReason").innerHTML = reason[0].value;

		printArea.style.display    = "block";
		nonPrintARea.style.display = "none";
		window.print();
		printArea.style.display    = "none";
		nonPrintARea.style.display = "block";
		document.getElementById("post").disabled = false;

	});

	document.getElementById("repBtn").addEventListener('click',()=>{
		let excel = document.getElementById("repFile").value;
	});
	
}

//空白がないかをチェック
function blanckCheck(department,person,patternChar,patternAlb,start,end,reason){
	if(department[0].value=="" || person[0].value=="" || start[0].value=="" || end[0].value == "" || reason[0].value==""){
		return true;
	}
	//深い意味はないが長いのでif文を分割
	if(radioCheck(patternChar)=="" || radioCheck(patternAlb)==""){
		return true;
	}
	return false;
}

//ラヂオボタンの値を取得
function radioCheck(pattern){
	char = "";
	pattern.forEach(element => {
		if (element.checked){char = element.value;}
	});
	return char;
}


//申請時間に不適合が無いかを調べる
function timeCheck(startDate,endDate,char,alphabet){
	let date     = new Date(startDate);
	let firstDay = true;
	let hourMinute;
	let finalday = false;
	let oneDay   = startDate.getFullYear() == endDate.getFullYear() && startDate.getMonth() == endDate.getMonth() && startDate.getDate() == endDate.getDate();
	const holiday  = holiday_jp.between(startDate, endDate);
	let pattern = getPattern(char,alphabet);
	

	do{
		finalday = endDate.getFullYear() == date.getFullYear() && endDate.getMonth() == date.getMonth() && endDate.getDate() == date.getDate();
		if(holidayCheck(date,holiday)){
			console.log("holiday");
			if(oneDay)return false;
			date.setDate(date.getDate()+1);
			firstDay = false;
			continue;
		}
		
		if(firstDay || finalday){
			hourMinute = date.getHours() + date.getMinutes()/60;
			if (hourMinute > pattern[0] && hourMinute < pattern[1])return true;
			firstDay = false;
			if(oneDay)return false;
		}

		date.setDate(date.getDate()+1);
		
	}while(finalday);

	return false;
}

function holidayCheck(date,holiday){
	let returnBool = false;
	if(date.getDay() == 0 || date.getDay()==6){
		return true;
	}
	holiday.forEach(ho=>{
		if(ho.date.getMonth() == date.getMonth() && ho.date.getDate() == date.getDate()){
			returnBool = true;
		}
	});
	return returnBool;
}

function getPattern(char,alphabet){
	let hourPattern;
	let returnData =[];
	let splited;
	switch(char){
		case "早出":
			hourPattern = fastPattern(alphabet);
			break;
		case "通常":
			hourPattern = normalPattern(alphabet);
			break;
		case "遅出":
			hourPattern = slowPattern(alphabet);
			break
	}
	//扱いやすく小数に変換
	hourPattern.forEach(element =>{
		splited = element.split(":");
		returnData.push(Number(splited[0]) + Number(splited[1])/60);
	});
	return returnData
}

function fastPattern(alphabet){
	switch(alphabet){
		case "A":
			return ["5:30","14:15"];
		case "B":
			return ["6:00","14:45"];
		case "C":
			return ["6:30","15:15"];
		case "D":
			return ["7:00","14:45"];
		case "E":
			return ["7:30","16:15"];
		case "F":
			return ["8:00","16:45"];
	}
}

function normalPattern(alphabet){
	switch(alphabet){
		case "A":
			return ["8:30","17:15"];
		case "B":
			return ["9:00","17:45"];
		case "C":
			return ["9:30","18:15"];
	}
}

function slowPattern(alphabet){
	switch(alphabet){
		case "A":
			return ["10:00","18:45"];
		case "B":
			return ["10:30","19:15"];
		case "C":
			return ["11:00","19:45"];
		case "D":
			return ["11:30","20:15"];
		case "E":
			return ["12:00","20:45"];
		case "F":
			return ["12:30","21:15"];
		case "G":
			return ["13:00","21:45"];
	}
}

function printDate(date){
	let data = date.split("T");
	let ymd  = data[0].split("-");
	let time = data[1].split(":");
	return ymd[0] + "年"+ymd[1]+"月"+ymd[2]+"日"+time[0]+"時"+time[1]+"分";
}
