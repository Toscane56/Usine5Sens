import { BrowserModule } from '@angular/platform-browser';
import { ErrorHandler, NgModule } from '@angular/core';
import { IonicApp, IonicErrorHandler, IonicModule } from 'ionic-angular';
import { SplashScreen } from '@ionic-native/splash-screen';
import { StatusBar } from '@ionic-native/status-bar';

import { HttpModule } from '@angular/http';
import {HttpClientModule} from "@angular/common/http";
import { AuthServiceProvider } from '../providers/auth-service/auth-service';

import { MyApp } from './app.component';
import { HomePage } from '../pages/home/home';
import { SigninPage } from '../pages/signin/signin';
import { SignupPage } from '../pages/signup/signup';
import { AtelierChoixPage } from '../pages/atelier-choix/atelier-choix';
import { AteliersPage } from '../pages/ateliers/ateliers';
import { GestionComptePage } from '../pages/gestion-compte/gestion-compte';
import { ReservationChoixPage } from '../pages/reservation-choix/reservation-choix';
import { ReservationsPage } from '../pages/reservations/reservations';
import { VisitePage } from '../pages/visite/visite';


@NgModule({
  declarations: [
    MyApp,
    AtelierChoixPage,
    HomePage,
    SigninPage,
    SignupPage,
    AteliersPage,
    GestionComptePage,
    ReservationChoixPage,
    ReservationsPage,
    VisitePage
  ],
  imports: [
    BrowserModule,
    HttpModule,
    HttpClientModule,
    IonicModule.forRoot(MyApp)
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    AtelierChoixPage,
    HomePage,
    SigninPage,
    SignupPage,
    AteliersPage,
    GestionComptePage,
    ReservationChoixPage,
    ReservationsPage,
    VisitePage
  ],
  providers: [
    StatusBar,
    SplashScreen,
    {provide: ErrorHandler, useClass: IonicErrorHandler},
    AuthServiceProvider
  ]
})
export class AppModule {}
