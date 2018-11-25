import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import {Http, Headers, RequestOptions} from '@angular/http';

import 'rxjs/add/operator/map';

/*
  Generated class for the AuthServiceProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/

let apiUrl = '../../../../api/';

@Injectable()
export class AuthServiceProvider {


  public token: string;
  public email: string;

  constructor(public http: Http) {
    console.log('Hello AuthServiceProvider Provider');
  }

  postDataWithToken(data, type) {
    //Envoie le json au serveur avec le token en entete
    return new Promise((resolve, reject) => {
        let headers = new Headers();
        headers.append('Token', this.token);
        headers.append('Content-Type', 'application/json');
        let options = new RequestOptions({headers: headers});
        console.log("Json en cours d'envoi");
        console.log("url : "+apiUrl+type+" token : "+ this.token);

        this.http.post(apiUrl+type, JSON.stringify(data), options=options).subscribe(res => { 
          console.log("Json envoyé");
          console.log(JSON.stringify(data));
          console.log(res); 
             resolve(res); 
            }, (err) => { 
            reject(err); 
            console.log(err); 
            console.log("Petit probleme"); 
        }); 
    });
  }

}
