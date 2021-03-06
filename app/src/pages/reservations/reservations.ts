import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import{VisitePage} from "../visite/visite";
import{ApercuReservationPage} from "../apercu-reservation/apercu-reservation";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';
import { AlertController } from 'ionic-angular';

/**
 * Generated class for the ReservationsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
 	selector: 'page-reservations',
 	templateUrl: 'reservations.html',
 })
 export class ReservationsPage {

 	token = this.authServiceProvider.token;
 	reservations = [];
 	workshopId = {"workshop_id":""};

 	constructor(private alertCtrl: AlertController, public navCtrl: NavController, public navParams: NavParams,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
 		this.recupererReservations();
 	}

 	ionViewDidLoad() {
 		console.log('ionViewDidLoad ReservationsPage');
 		console.log(this.reservations);
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

 	activerEcranApercuReservation(Reservation:any){
 		//Fonction permettant d'amener à la page d'aperçu de la réservation choisie
 		//Envoie toutes les données de la réservation en question à la page suivante 		
 		this.navCtrl.push(ApercuReservationPage, {
 			Reservation:Reservation             
 		});
 	}  	

 	retourPagePrecendente(){
 		//Fonction permettant de retourner à la page précédente
 		this.navCtrl.pop();
 	}

 	recupererReservations(){
 		//Fonction permettant de récupérer les réservations dans la bdd
 		this.authServiceProvider.request('workshop', 'reservations').then((result) => {
 			var data = JSON.parse(result['_body']).workshops;
 			this.reservations.push(data); //ajoute les reservations dans le tableau
 		}, (error) => {
 			//erreur coté serveur
 			console.log(error);
 			console.log("ça ne marche pas");
 		});
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
 					console.log('Confirmation de la description');
 					//Envoi au serveur le json   
 					this.authServiceProvider.request('workshop', 'remove' , this.workshopId).then((result) => {
 						console.log("J'ai envoyé les donnees.")
 						this.navCtrl.setRoot(ReservationsPage);

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
