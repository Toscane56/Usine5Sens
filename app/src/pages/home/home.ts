import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
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
  workshops = [];

  constructor(public navCtrl: NavController, public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
    //console.log("token :"+this.authServiceProvider.token);
    this.recupererExposition();
    this.recupererAtelier();
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
    this.requestServiceProvider.request('exposition', 'index').then((result) => {
      var data = JSON.parse(result['_body']).exposition;
      this.expositions.push(data); //ajoute les expositions dans le tableau
      console.log("exposition "+ this.expositions);
    }, (error) => {
      //erreur coté serveur
      console.log(error);
      console.log("ça ne marche pas");
    });
  }

  recupererAtelier(){
    //Fonction permettant de récupérer les ateliers de la journée dans la bdd
    this.requestServiceProvider.request('workshop', 'index').then((result) => {
      var data = JSON.parse(result['_body']).workshops;
      this.workshops.push(data); //ajoute les ateliers dans le tableau
    }, (error) => {
      //erreur coté serveur
      console.log(error);
      console.log("ça ne marche pas");
    });
  }

  activerEcranAtelierChoix(Workshop:any){
    //Fonction permettant d'amener à la page lorsque l'on clique sur un atelier en particulier
    //Envoie toutes les données de l'atelier en question à la page suivante
    this.navCtrl.push(AtelierChoixPage, {
      Workshop:Workshop               
    });
  }

}
