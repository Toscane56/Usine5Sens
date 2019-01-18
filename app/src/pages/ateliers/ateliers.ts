import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{VisitePage} from "../visite/visite";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';

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

	constructor(public navCtrl: NavController,   public authServiceProvider : AuthServiceProvider) {
	}

  	ionViewDidLoad() {
    	console.log('ionViewDidLoad AteliersPage');
    	console.log("token :"+this.authServiceProvider.token);
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
}
