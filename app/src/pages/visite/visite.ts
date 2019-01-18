import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';

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
  expositions = [];

	constructor(public navCtrl: NavController,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
    this.recupererExposition();
	}

  ionViewDidLoad() {
    console.log('ionViewDidLoad VisitePage');
	  console.log("token :"+this.authServiceProvider.token);
  }

  recupererExposition(){
    //Fonction permettant de récupérer l'exposition en cours dans la bdd
    this.requestServiceProvider.request('exposition', 'index').then((result) => {
        var data = JSON.parse(result['_body']).exposition;
        this.expositions.push(data); //ajoute les expositions dans le tableau
        //console.log(data);
        console.log("exposition "+ this.expositions);
      }, (error) => {
      //erreur coté serveur
        console.log(error);
        console.log("ça ne marche pas");
    });
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
