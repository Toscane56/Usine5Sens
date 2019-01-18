import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{AtelierChoixPage} from "../atelier-choix/atelier-choix";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  token = this.authServiceProvider.token;
  firstname = this.authServiceProvider.firstname;

  expositions = [];

	workshops = [
	{id:0, name: "Création de parfum", description: "Créez votre parfum maison", place:"Pavillon des sens", startTime: "14:00", endTime: "16:00", startDate: "25/11/2018", endDate: "30/02/2019", img:"../assets/imgs/workshops/atelier_parfum.jpg"},
	{id:1, name: "Photographie et Nature", description: "Voyez la nature autrement", place:"Place principal - jet d'eau", startTime: "19:00", endTime: "17:00", startDate: "25/11/2018", endDate: "30/02/2019", img:"../assets/imgs/water.jpg"},
	{id:2, name: "Atelier Cuisine", description: "Testez de nouvelles recettes", place:"Halle d'Auteuil", startTime: "14:00", endTime: "22:00", startDate: "25/11/2018", endDate: "30/02/2019", img:"../assets/imgs/water.jpg"},
	]

  constructor(public navCtrl: NavController,  private params: NavParams , public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
    console.log("token :"+this.authServiceProvider.token);
    this.recupererExposition();
    
  }

  ionViewDidLoad() {
    
    console.log('ionViewDidLoad ReservationsPage');
  }

  activerEcranVisite(){
    //Fonction permettant d'amener à la page visite de l'Usine des 5 Sens
    this.navCtrl.push(VisitePage);
  }

  activerGestionCompte(){
    //Fonction permettant d'amener à la page servant à gérer le compte
    this.navCtrl.push(GestionComptePage);
  }

  activerEcranAteliers(){
    //Fonction permettant d'amener à la page regroupant tous les ateliers
    this.navCtrl.push(AteliersPage);
  }

  recupererExposition(){
    //Fonction permettant de récupérer l'exposition en cours dans la bdd
    this.requestServiceProvider.request('exposition', 'index', this.expositions).then((result) => {
        var data = JSON.parse(result['_body']).exposition;
        this.expositions.push(data); //ajoute les expositions dans le tableau
        //console.log(data);
        console.log("exposition "+ this.expositions);
      }, (error) => {
      //erreur coté serveur
        console.log(error);
        console.log("ça ne marche pas");
    });
  }

  activerEcranAtelierChoix(idAtelier:string){
    //Fonction permettant d'amener à la page lorsque l'on clique sur un atelier en particulier
    //Envoie les données "idAtelier" à la page suivante (l'atelier en question)
    idAtelier = idAtelier;
    this.navCtrl.push(AtelierChoixPage, {
      data:idAtelier
    });
  }

}
