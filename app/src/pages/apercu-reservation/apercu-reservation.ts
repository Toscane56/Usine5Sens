import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ToastController } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import{SigninPage} from "../signin/signin";
import{VisitePage} from "../visite/visite";
import{ReservationsPage} from "../reservations/reservations";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';
import { AlertController } from 'ionic-angular';

/**
 * Generated class for the ApercuReservationPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
   selector: 'page-apercu-reservation',
   templateUrl: 'apercu-reservation.html',
 })
 export class ApercuReservationPage {

   token = this.authServiceProvider.token;
   workshopArray:any;
   workshopId = {"workshop_id":""};

   constructor(private alertCtrl: AlertController, public navCtrl: NavController, public toastCtrl: ToastController, public navParams: NavParams,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
     this.workshopArray = navParams.get('Reservation'); //récupération du tableau workshop de la page précédente 
     this.workshopId.workshop_id = this.workshopArray.id ;
   }

   ionViewDidLoad() {
     console.log('ionViewDidLoad ApercuReservationPage');
     console.log(this.workshopArray);
   }


   activerEcranAteliers(){
     //Fonction permettant d'amener à la page regroupant tous les ateliers
     this.navCtrl.push(AteliersPage);
   }

   activerGestionCompte(){
     //Fonction permettant d'amener à la page servant à gérer le compte
     this.navCtrl.push(GestionComptePage);
   }

   activerEcranVisite(){
     //Fonction permettant d'amener à la page visite de l'Usine des 5 Sens
     this.navCtrl.push(VisitePage);
   }

   activerAccueil(){
     //Fonction permettant d'amener à l'accueil
     this.navCtrl.push(HomePage);
   } 

   retourPagePrecendente(){
     //Fonction permettant de retourner à la page précédente
     this.navCtrl.pop();
   }  

   desinscription(reservationId){
     //Fonction permettant de désinscrire l'utilisateur à un atelier

     console.log(reservationId);
     this.workshopId.workshop_id=reservationId;
     
     //Création d'une alerte pour demander confirmation
     let alert = this.alertCtrl.create({
       title: 'Confirmer la désinscription',
       message: 'Voulez-vous vraiment vous désinscrire ?',
       buttons: [
       {
         text: 'Annuler',
         role: 'cancel',
         handler: () => {
           console.log('Annulation de la désinscription');
         }
       },
       {
         text: 'Valider',
         handler: () => {
           console.log('Confirmation de la déscription');
           //Envoi au serveur le json   
           this.authServiceProvider.request('workshop', 'remove' , this.workshopId).then((result) => {
             console.log("J'ai envoyé les donnees.")
             this.navCtrl.push(ReservationsPage);

           }, (error) => {
             //erreur coté serveur
             console.log(error);
             console.log("ça ne marche pas. L'id du workshop est : "+this.workshopId);
           });


         }
       }
       ]
     });
     alert.present();
   }

 }
