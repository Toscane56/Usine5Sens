import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

	workshops = [
	{id:0, name: "Création de parfum", description: "Créez votre parfum maison", place:"Pavillon des sens", startTime: "14:00",
   endTime: "16:00", startDate: "25/11/2018", endDate: "30/02/2019", img:"../assets/imgs/water.jpg"},
	{id:1, name: "Photographie et Nature", description: "Voyez la nature autrement", place:"Place principal - jet d'eau", startTime: "19:00",
   endTime: "17:00", startDate: "25/11/2018", endDate: "30/02/2019", img:"../assets/imgs/water.jpg"},
	{id:2, name: "Atelier Cuisine", description: "Testez de nouvelles recettes", place:"Halle d'Auteuil", startTime: "14:00",
   endTime: "22:00", startDate: "25/11/2018", endDate: "30/02/2019", img:"../assets/imgs/water.jpg"},
	]

  constructor(public navCtrl: NavController) {

  }

}
