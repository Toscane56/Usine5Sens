import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import { ToastController } from 'ionic-angular';
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import{SigninPage} from "../signin/signin";
import{VisitePage} from "../visite/visite";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';

/**
 * Generated class for the ReservationChoixPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
 	selector: 'page-reservation-choix',
 	templateUrl: 'reservation-choix.html',
 })
 export class ReservationChoixPage {

 	profilData = {"email":"", "password":"", "firstname":"", "lastname":""}; 
 	//tableau permettant de récupérer les données du profil de l'utilisateur

 	token = this.authServiceProvider.token;

 	constructor(public navCtrl: NavController, public toastCtrl: ToastController, public navParams: NavParams,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
 		this.recupererProfil();
 	}

 	ionViewDidLoad() {
 		console.log('ionViewDidLoad AtelierChoixPage');
 	}

 	StringObj(obj){
 		var str = '';
 		for (var p in obj) {
 			str += obj[p];
 		}
 		str = '#'+str;  
 		return str;
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

 	recupererProfil(){
     //Fonction permettant de récupérer le profil de l'utilisateur dans la bdd
     this.authServiceProvider.request('user', 'profile').then((result) => {
       var data = JSON.parse(result['_body']).user;
       this.profilData = data;
       console.log("profil "+ this.profilData);
     }, (error) => {
       //erreur coté serveur
       console.log(error);
       console.log("ça ne marche pas");
     });
   }

   updateProfil(){
     //Fonction permettant de mettre à jour le profil de l'utilisateur dans la bdd
     this.authServiceProvider.request('user', 'update', this.profilData).then((result) => {
       console.log("J'ai envoyé les donnees.")

       //Récupere son prenom
       var firstname = JSON.parse(result['_body']).user.firstname;          
       this.authServiceProvider.firstname = firstname;

       this.navCtrl.push(GestionComptePage);
       //passer à l'écran de gestion de compte (menu principal)
     }, (error) => {
       //erreur coté serveur
       this.presentToastFormulaireIncomplet();
       console.log(error);
       console.log("ça ne marche pas");
     });
   }


   presentToastFormulaireIncomplet() {
     //Définit le message de refus de créer un utilisateur sans toutes les données
     let toast = this.toastCtrl.create({
       message: "Veuillez remplir tout le formulaire",
       duration: 3000
     });
     toast.present();
   }
 }
