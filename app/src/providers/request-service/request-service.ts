import { Injectable } from '@angular/core';
import {Http, Headers, RequestOptions} from '@angular/http';
import { API_URL } from '../../config';

import 'rxjs/add/operator/map';

/*
  Generated class for the AuthServiceProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
  */

  let apiUrl = API_URL;

  @Injectable()
  export class RequestServiceProvider {


      constructor(public http: Http) {
          console.log('Hello AuthServiceProvider Provider');
      }

      request (entity, action, data = {}) {
          return new Promise((resolve, reject) => {
              let headers = new Headers();
              headers.append('Content-Type', 'application/json');

              let options = new RequestOptions({ headers: headers });
              let url = apiUrl + '?entity=' + entity + '&action=' + action;

              console.log('Requête POST', url, data);

              this.http.post(url, JSON.stringify(data), options=options).subscribe(res => {
                  console.log("Données renvoyées par l'API", JSON.stringify(data));
                  resolve(res);
              }, (err) => {
                  console.log("Erreur renvoyée par l'API", err);
                  reject(err);
              });
          });
      }

  }