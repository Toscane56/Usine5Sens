import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

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

	idAtelier: string;

  constructor(public navCtrl: NavController, public navParams: NavParams) {
    this.idAtelier = navParams.get('data');
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad AtelierChoixPage');
    console.log(this.idAtelier);    
  }



}
