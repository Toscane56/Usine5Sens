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
	idwWorkshop:string;
  workshops = [];

  constructor(public navCtrl: NavController, public navParams: NavParams,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
    this.idwWorkshop = navParams.get('idWorkshop'); 
    this.recupererAtelier();        
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad AtelierChoixPage');
    console.log(this.idwWorkshop);   
  }

  recupererAtelier(){
    //Fonction permettant de récupérer les ateliers en cours dans la bdd
    this.requestServiceProvider.request('workshop', 'index').then((result) => {
        var data = JSON.parse(result['_body']).workshops;
        this.workshops.push(data); //ajoute les ateliers dans le tableau

        //console.log(data);
        //console.log("workshops "+ JSON.parse(result['_body']).workshops[1].senses[1].name);
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
