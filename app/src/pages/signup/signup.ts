import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ToastController } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{HomePage} from "../home/home";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import { RequestServiceProvider } from '../../providers/request-service/request-service';
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

	registerData = {"email":"", "password":"", "firstname":"", "lastname":""}; //tableau permettant de récupérer les données de connexion de l'utilisateur

	constructor(public navCtrl: NavController,   public requestServiceProvider : RequestServiceProvider, public toastCtrl: ToastController) {
	}

	ionViewDidLoad() {
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

  register(){
    //Fonction permettant d'enregistrer un nouveau utilisateur dans la base de données
    console.log(this.registerData);

 //Envoi au serveur le json   
    this.requestServiceProvider.request('user', 'register' ,this.registerData).then((result) => {
      console.log("J'ai envoyé les donnees.")
        //if (JSON.parse(result['_body']).hasOwnProperty("status") && JSON.parse(result['_body']).status == "error") {
          // s'il y a une erreur
         //   this.presentToastInscription();
            //prevenir l'utilisateur
        //} else {
          //Récupere le token de l'utilisateur
          var $my_token = JSON.parse(result['_body']).token;
          this.requestServiceProvider.token = $my_token;

          //Récupere son prenom
          var firstname = JSON.parse(result['_body']).firstname;          
          this.requestServiceProvider.firstname = firstname;

            this.navCtrl.push(GestionComptePage);
          //sinon passer à l'écran suivant
        //}

    }, (error) => {
      //erreur coté serveur
        console.log(error);
        console.log("ça ne marche pas");
    });
  }

  presentToastInscription() {
    //Définit le message "pseudo deja utilisé" lorsqu'un utilisateur essaye de s'inscrire avec un pseudo
    // déjà existant dans la base de données
    let toast = this.toastCtrl.create({
        message: "Pseudo déjà utilisé",
        duration: 3000
      });
    toast.present();
  }


}
