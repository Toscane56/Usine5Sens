import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{HomePage} from "../home/home";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
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

	token = this.authServiceProvider.token;
	registerData = {"surname":"", "name":"", "email":"", "password":""}; //tableau permettant de récupérer les données de connexion de l'utilisateur

	constructor(public navCtrl: NavController,   public authServiceProvider : AuthServiceProvider) {
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

  register(){
  this.authServiceProvider.token ="1";
  this.navCtrl.push(GestionComptePage);
  }

}
