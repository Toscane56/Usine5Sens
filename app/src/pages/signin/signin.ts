import { Component } from '@angular/core';
import { IonicPage, NavController} from 'ionic-angular';
import { ToastController } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{HomePage} from "../home/home";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{SignupPage} from "../signup/signup";
import { RequestServiceProvider } from '../../providers/request-service/request-service';
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
/**
 * Generated class for the SigninPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
 	selector: 'page-signin',
 	templateUrl: 'signin.html',
 })
 export class SigninPage {

 	token = this.authServiceProvider.token;
 	coData = {"email":"", "password":""}; //tableau permettant de récupérer les données de connexion de l'utilisateur

 	constructor(public navCtrl: NavController, public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider, public toastCtrl: ToastController) {
 	}

 	ionViewDidLoad() {
 		console.log("token :"+this.authServiceProvider.token);
 		//console.log(this.authServiceProvider.email);
 	}

 	activerEcranVisite(){
 		//Fonction permettant d'amener à la page visite de l'Usine des 5 Sens
 		this.navCtrl.push(VisitePage);
 	}

 	activerEcranAteliers(){
 		//Fonction permettant d'amener à la page regroupant tous les ateliers
 		this.navCtrl.push(AteliersPage);
 	}

 	activerAccueil(){
 		//Fonction permettant d'amener à l'accueil
 		this.navCtrl.push(HomePage);
 	}

 	activerGestionCompte(){
 		//Fonction permettant d'amener à la page servant à gérer le compte
 		this.navCtrl.push(GestionComptePage);
 	}

 	connexion(){
 		//Fonction permettant de connecter un utilisateur
 		console.log(this.coData);
 		if(this.coData.email == "" || this.coData.password == "" ){
 			//Si au moins 1 des champs est vide
 			this.presentToastFormulaireIncomplet();
 			//Transmettre un message à l'utilisateur
 		}else{
 			//Sinon
 			//Envoi au serveur le json   
 			this.requestServiceProvider.request('user', 'login' ,this.coData).then((result) => {
 				console.log("J'ai envoyé les donnees.")
 				//Récupere le token de l'utilisateur
 				var $my_token = JSON.parse(result['_body']).user.token;
 				this.authServiceProvider.token = $my_token;

 				//Récupere son prenom
 				var firstname = JSON.parse(result['_body']).user.firstname;          
 				this.authServiceProvider.firstname = firstname;

 				this.navCtrl.setRoot(HomePage);
 				//passer à l'écran de l'accueil en le réinitialisant

 			}, (error) => {
 				//erreur coté serveur
 				console.log(error);
 				console.log("ça ne marche pas");
 				this.presentToastConnexion();
 			});
 		}
 	}

 	presentToastFormulaireIncomplet() {
 		//Définit le message de refus de se connecter sans toutes les données
 		let toast = this.toastCtrl.create({
 			message: "Veuillez remplir tout le formulaire",
 			duration: 3000
 		});
 		toast.present();
 	}

 	presentToastConnexion() {
 		//Définit le message "Pseudo ou mot de passe incorrecte" lorsqu'un utilisateur essaye de se connecter
 		//avec des erreurs
 		let toast = this.toastCtrl.create({
 			message: "Pseudo ou mot de passe incorrecte",
 			duration: 3000
 		});
 		toast.present();
 	}


 	activerSignup(){
 		//Fonction permettant d'amener à l'écran d'inscription
 		this.navCtrl.push(SignupPage);
 	}

 	deconnexion(){
 		//Fonction permettant de déconnecter l'utilisateur et l'amène de nouveau sur la page d'accueil
 		this.authServiceProvider.token = undefined; //arret de la session
 		this.navCtrl.push(HomePage); //active l'écran de la page d'accueil
 	}


 }
