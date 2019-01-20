import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ToastController } from 'ionic-angular';
import{AteliersPage} from "../ateliers/ateliers";
import{GestionComptePage} from "../gestion-compte/gestion-compte";
import{HomePage} from "../home/home";
import{SigninPage} from "../signin/signin";
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
  reservations = [];

  workshopId = {"workshop_id":""};
  boutton = {"nom":"Réserver cet atelier"};

  constructor(public navCtrl: NavController, public toastCtrl: ToastController, public navParams: NavParams,public requestServiceProvider : RequestServiceProvider, public authServiceProvider : AuthServiceProvider) {
    this.workshopArray = navParams.get('Workshop'); //récupération du tableau workshop de la page précédente 
    this.workshopId.workshop_id = this.workshopArray.id ;
    this.recupererReservations();

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

  reserverAtelier(){
    //Fonction permettant la réservation d'un atelier
    if (this.token == null) //si l'utilisateur n'est pas connecté
    {
      this.navCtrl.push(SigninPage); //mène vers l'écran pour se connecter
    }else{
      this.envoiDonneesReservation();
    }
  }

  verificationReservation(){
    console.log("j'aimerais changer le bouton en supprimer");
    console.log(this.reservations);
    for (let i in this.reservations) {
      console.log(this.reservations[i].id);
      console.log("workshopArray : "+this.workshopArray.id);
      console.log("boucle reservation");
      if(this.reservations[i].id == this.workshopArray.id){
        let text = "Atelier déjà réservé";
        console.log(text);
        this.boutton.nom = text;
      }
    }   
  }


  envoiDonneesReservation(){
    //Fonction permettant de créer un lien entre l'utilisateur et l'atelier réservé dans la base de données
    console.log(this.workshopArray.id);

    //Envoi au serveur le json   
    this.authServiceProvider.request('workshop', 'book' , this.workshopId).then((result) => {
      console.log("J'ai envoyé les donnees.")
      this.presentToastReservation(); //Prévient l'utilisateur que l'atelier est reservé
    }, (error) => {
      //erreur coté serveur
      console.log(error);
      this.presentToastReservationDejaEffectue();
      console.log("ça ne marche pas. L'id du workshop est : "+this.workshopId);
    });
  }  

  presentToastReservation() {
    //Définit le message de validation de la réservation
    let toast = this.toastCtrl.create({
      message: "Réservation effectuée !",
      duration: 3000
    });
    toast.present();
  }

  presentToastReservationDejaEffectue() {
    //Définit le message de refus de refaire la réservation car l'atelier est déjà reservé
    let toast = this.toastCtrl.create({
      message: "Atelier déjà réservé",
      duration: 3000
    });
    toast.present();
  }

  recupererReservations(){
    //Fonction permettant de récupérer les réservations dans la bdd
    this.authServiceProvider.request('workshop', 'reservations').then((result) => {
      var data = JSON.parse(result['_body']).workshops;
      this.reservations=data; //ajoute les reservations dans le tableau
      console.log(this.reservations);
      this.verificationReservation();

    }, (error) => {
      //erreur coté serveur
      console.log(error);
      console.log("ça ne marche pas");
    });
  }

}
