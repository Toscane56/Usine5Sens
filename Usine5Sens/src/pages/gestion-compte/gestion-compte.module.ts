import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { GestionComptePage } from './gestion-compte';

@NgModule({
  declarations: [
    GestionComptePage,
  ],
  imports: [
    IonicPageModule.forChild(GestionComptePage),
  ],
})
export class GestionComptePageModule {}
