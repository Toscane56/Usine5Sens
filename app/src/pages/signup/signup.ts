import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ToastController } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{HomePage} from "../home/home";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import { RequestServiceProvider } from '../../providers/request-service/request-service';
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
/**
 * Generated class for the SignupPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

 @IonicPage()
 @Component({
   selector: 'page-signup',
   templateUrl: 'signup.html',
 })
 export class SignupPage {

   registerData = {"email":"", "password":"", "firstname":"", "lastname":""}; //tableau permettant de récupérer les données d'inscription de l'utilisateur

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

   activerGestionCompte(){
     //Fonction permettant d'amener à la page servant à gérer le compte
     this.navCtrl.push(GestionComptePage);
   }

   register(){
     //Fonction permettant d'enregistrer un nouveau utilisateur dans la base de données
     console.log(this.registerData);

     if(this.registerData.firstname == "" || this.registerData.lastname == "" || this.registerData.email == "" || this.registerData.password == "" ){
       //Si au moins 1 des champs est vide

       this.presentToastFormulaireIncomplet();
       //Transmettre un message à l'utilisateur pour qu'il remplisse tous les champs
     }else{
       //Sinon
       //Envoi au serveur le json   
       this.requestServiceProvider.request('user', 'register' ,this.registerData).then((result) => {
         console.log("J'ai envoyé les donnees.")
         //Récupere le token de l'utilisateur
         var $my_token = JSON.parse(result['_body']).user.token;
         this.authServiceProvider.token = $my_token;

         //Récupere son prenom
         var firstname = JSON.parse(result['_body']).user.firstname;          
         this.authServiceProvider.firstname = firstname;

         this.navCtrl.push(GestionComptePage);
         //passer à l'écran suivant

       }, (error) => {
         //erreur coté serveur
         console.log(error);
         console.log("ça ne marche pas");
         this.presentToastInscription();
       });
     }
   }

   presentToastFormulaireIncomplet() {
     //Définit le message de refus de créer un utilisateur sans toutes les données
     let toast = this.toastCtrl.create({
       message: "Veuillez remplir tout le formulaire",
       duration: 3000
     });
     toast.present();
   }

   presentToastInscription() {
     //Définit le message "Adresse email déjà utilisée" lorsqu'un utilisateur essaye de s'inscrire avec une adresse email
     // déjà existante dans la base de données
     let toast = this.toastCtrl.create({
       message: "Adresse email déjà utilisée",
       duration: 3000
     });
     toast.present();
   }


 }
