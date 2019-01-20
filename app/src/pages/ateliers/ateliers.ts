import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import{AtelierChoixPage} from "../atelier-choix/atelier-choix";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';

/**
 * Generated class for the AteliersPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
   selector: 'page-ateliers',
   templateUrl: 'ateliers.html',
 })
 export class AteliersPage {

   token = this.authServiceProvider.token;
   workshops = [];

   constructor(public navCtrl: NavController,  private params: NavParams , public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
     this.recupererAtelier();
   }

   activerEcranVisite(){
     //Fonction permettant d'amener à la page visite de l'Usine des 5 Sens
     this.navCtrl.push(VisitePage);
   }

   activerGestionCompte(){
     //Fonction permettant d'amener à la page servant à gérer le compte
     this.navCtrl.push(GestionComptePage);
   }

   activerAccueil(){
     //Fonction permettant d'amener à l'accueil
     this.navCtrl.push(HomePage);
   } 

   recupererAtelier(){
     //Fonction permettant de récupérer les ateliers de la journée dans la bdd
     this.requestServiceProvider.request('workshop', 'index').then((result) => {
       var data = JSON.parse(result['_body']).workshops;
       this.workshops.push(data); //ajoute les ateliers dans le tableau
       //console.log(data);
       
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
