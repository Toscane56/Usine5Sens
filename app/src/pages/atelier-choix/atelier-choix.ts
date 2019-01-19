import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import{VisitePage} from "../visite/visite";
import { AuthServiceProvider } from '../../providers/auth-service/auth-service';
import { RequestServiceProvider } from '../../providers/request-service/request-service';

/**
 * Generated class for the AtelierChoixPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-atelier-choix',
  templateUrl: 'atelier-choix.html',
})
export class AtelierChoixPage {


  token = this.authServiceProvider.token;
	workshopArray:any;

  constructor(public navCtrl: NavController, public navParams: NavParams,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
    this.workshopArray = navParams.get('Workshop'); //récupération du tableau workshop de la page précédente 
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad AtelierChoixPage');
    console.log(this.workshopArray);  
     
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

}
