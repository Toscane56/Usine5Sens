import { Component } from '@angular/core';
import { IonicPage, NavController } from 'ionic-angular';
import { ToastController } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{HomePage} from "../home/home";
import{SignupPage} from "../signup/signup";
import{ReservationsPage} from "../reservations/reservations";
import{ReservationChoixPage} from "../reservation-choix/reservation-choix"
import { RequestServiceProvider } from '../../providers/request-service/request-service';
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
/**
 * Generated class for the GestionComptePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
   selector: 'page-gestion-compte',
   templateUrl: 'gestion-compte.html',
 })
 export class GestionComptePage {

   token = this.authServiceProvider.token;
   coData = {"email":"", "password":""}; //tableau permettant de récupérer les données de connexion de l'utilisateur

   constructor(public navCtrl: NavController, public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider, public toastCtrl: ToastController) {
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

   ActiverEcranProfil(){
     //Fonction permettant d'amener à la page qui sert à mettre à jour le profil de l'utilisateur
     this.navCtrl.push(ReservationChoixPage);
   }

   ActiverEcranReservations(){
     //Fonction permettant d'amener à la page regroupant les réservations de l'utilisateur
     this.navCtrl.push(ReservationsPage);
   }

   connexion(){
     //Fonction permettant de connecter l'utilisateur
     console.log(this.coData);

     if(this.coData.email == "" || this.coData.password == "" ){
       //Si au moins 1 des champs est vide
       
       this.presentToastFormulaireIncomplet();
       //Transmettre un message à l'utilisateur
     }else{
       //Sinon envoi au serveur le json   
       this.requestServiceProvider.request('user', 'login' ,this.coData).then((result) => {
         console.log("J'ai envoyé les donnees.")
         //Récupere le token de l'utilisateur
         var $my_token = JSON.parse(result['_body']).user.token;
         this.authServiceProvider.token = $my_token;

         //Récupere son prenom
         var firstname = JSON.parse(result['_body']).user.firstname;          
         this.authServiceProvider.firstname = firstname;

         this.navCtrl.setRoot(GestionComptePage);
         //Refresh la page

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
