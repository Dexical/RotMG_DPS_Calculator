const xPoints = 75;
const xDiff = 2;
const nameSelect = document.getElementById("weaponName");
const typeSelect = document.getElementById("weaponType");
const body = document.getElementsByTagName("body");
const graphCanvas = document.getElementById("dpsGraph");
const colors = ["#ff0000", "#00ff00", "#0000ff", "#ff00ff", "#00ffff", "#ffff00"];

$(document).ready(function(){
	$("#weaponType").change(changeNameSelect);
	$("#submitStats").click(submitStats);
});

//Creating chart with x-axis value count and interval defined by xPoints and xDiff constants
let xValues = [];
for (let i = 0; i < xPoints+1; i++){
	xValues.push(xDiff*i)
}
var dpsGraph = new Chart(graphCanvas, {
	type: 'line',
	data: {
		labels:xValues
	},
	options: {
		plugins: {
			customCanvasBackgroundColor: {
				color: 'lightGreen'
			}
		},
		scales: {
			y: {
				beginAtZero: false,
				grid:{
					color:"#555"
				},
				ticks:{
					color:"#fff"
				}
			},
			x:{
				grid:{
					color:"#555"
				},
				ticks:{
					color:"#fff"
				}
			}
		}
	}
});
var champions = [];
class Champion {
	constructor(stats, weapon){
		this.stats = stats;
		this.weapon = weapon;
		this.dps = [];
	}
};

//Changes names when selecting a Weapon Type
function changeNameSelect(){
	var type = typeSelect.value;
	$("#weaponName").load("resources/change_weapon_names.php", {
		weaponType: type
	});
}

//Updates chart values
function updateChart() {
	if (champions.length < 1) {
		return;
	}
	
	let datas = [];

	for (let i = 0; i < champions.length; i++) {
		const element = champions[i];
		datas.push({
			label: element.weapon.name,
			data: element.dps,
			borderWidth: 1,
			borderColor: colors[i%6],
			backgroundColor: colors[i%6]
		})
	}
	dpsGraph.data.datasets = datas;
	dpsGraph.update();
	console.log(dpsGraph.data.datasets);
}

//Looks for a weapon considering its type and name
async function getWeapon(type, name){
	// const src = "resources/equipment/weapons/" + type + "/" + name + ".json";
	let weap = 3;
	$.post("resources/get_weapon.php", {
		weaponID: name
	}, function(data, status){
		console.log(data);
		$("#test").html(data);
		weap = data;
		//fetch(data);
			// .then(function(response){
			// 	return response.json();
			// })
			// .then(function(weapon){
			// 	console.log(weapon);
			// })
	})
	console.log(weap);
	return weap;
}

//Submits input stats
function submitStats(){

	let stats = {
        hp:0,
        mp:0,
        att:document.getElementById("attack").value,
        def:0,
        spd:0,
        dex:document.getElementById("dexterity").value,
        vit:0,
        wis:0
	}
	let weaponType = typeSelect.value;
	let weaponName = nameSelect.value;

	getWeapon(weaponType, weaponName)
	.then(res =>{
		console.log("2 : " + res);
		// fetch(res)
		// 		.then(function(response){
		// 			return response.json();
		// 		})
		// 		.then(function(weapon){
		// 			console.log(weapon);
		// 		});
	})
	// .then(res => {
	// 	let index = champions.push(new Champion(stats, res));
	// 	champions[index-1].dps = dpsCalculations(champions[index-1]);
	// 	updateChart();
	// });
	
}

//Calculates rate of fire
function rofCaltulations(champion) {
	let rateOfFire = 1.5+6.5*(champion.stats.dex/75);
	if (champion.weapon.burst.enabled) {
		let delayDiff = champion.weapon.burst.maxDelay - champion.weapon.burst.minDelay;
		let delay = champion.weapon.burst.maxDelay - Math.min(1, champion.stats.dex/75) * delayDiff;
		let burstRof = (1/rateOfFire)*champion.weapon.burst.count;
		if (delay > burstRof) {
			rateOfFire = champion.weapon.burst.count/delay;
		} 
	}
	return rateOfFire
}

//Calculates damage
function avgDmgCalcultations(shots, minDmg, maxDmg, j) {
	let avgDmg = 0;
	if (!shots.pierce) {
		let def = j*xDiff;//44
		let maxReducDmg = Math.floor(def*10/9);

		if (maxReducDmg > minDmg && maxReducDmg < maxDmg) {
			//number of dmg max reduced (10% dmg)
			let lowestHalf = (maxReducDmg-minDmg);

			//number of dmg with normal def behaviour 
			let highestHalf = (maxDmg-maxReducDmg+1);

			//Sum of all 10% dmg as follow :
			//Triangle Nubmer (1 to lowestHalf) + missing dmg /maxReduction
			let lowestDmgSum = (lowestHalf * ( (lowestHalf+1)/2 + (minDmg-1) )) /10;
			//Sum of all remaining dmg as follow :
			//Triangle Nubmer (1 to highestHalf) + missing dmg with def reduction
			let highestDmgSum = highestHalf * ( (highestHalf+1)/2 + (maxReducDmg-1-def) );

			let totalPossibleDmg = lowestDmgSum + highestDmgSum +1;
			avgDmg = totalPossibleDmg/(maxDmg - minDmg +1);
		}
		else{
			if (maxReducDmg >= maxDmg) {
				avgDmg = (minDmg + maxDmg)/20;
			}
			else{
				avgDmg = (minDmg + maxDmg)/2 - def;
			}
		}
	}
	else{
		avgDmg = (minDmg + maxDmg)/2;
	}
	return avgDmg;
}

//Calculates dps
function dpsCalculations(champion) {
	//If no pierce (AvgDmg - def) * shotCount * rateOfFire
	//If pierce AvgDmg * shotCount * rateOfFire
	let totalDps = [];
	for (let j = 0; j < xPoints+1; j++){
		totalDps[j] = 0;
	}

	let rateOfFire = rofCaltulations(champion);
	let dmgMultiplier = 0.5 + champion.stats.att * 0.02;
	console.log(rateOfFire);
	console.log(dmgMultiplier);
	console.log("--");

	//ALED
	for (let i = 0; i < champion.weapon.shots.length; i++) {
		let element = champion.weapon.shots[i];
		let min = element.minDmg * dmgMultiplier;
		let max = element.maxDmg * dmgMultiplier;
		for (let j = 0; j < xPoints+1; j++) {
			let dmg = avgDmgCalcultations(element, min, max, j);
			totalDps[j] = totalDps[j] + (dmg * element.count * rateOfFire);
			if (!champion.weapon.burst.enabled) {
				totalDps[j] = totalDps[j] * element.rateOfFire;
			}
		}
	}
	for (let j = 0; j < xPoints+1; j++){
		console.log(totalDps[j]);
	}
	console.log("--------------------------");
	return totalDps;
}