import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{AteliersPage} from "../ateliers/ateliers";
import{HomePage} from "../home/home";
import{SignupPage} from "../signup/signup";
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

	  connexion(){
	  this.authServiceProvider.token =1;
	  this.navCtrl.setRoot(GestionComptePage);
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
