import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';

/**
 * Generated class for the VisitePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-visite',
  templateUrl: 'visite.html',
})
export class VisitePage {

  	token = this.authServiceProvider.token;

	constructor(public navCtrl: NavController,   public authServiceProvider : AuthServiceProvider) {
	}

  ionViewDidLoad() {
    console.log('ionViewDidLoad VisitePage');
	console.log("token :"+this.authServiceProvider.token);
  }

  activerEcranAteliers(){
    //Fonction permettant d'amener à la page regroupant tous les ateliers
    this.navCtrl.push(AteliersPage);
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
