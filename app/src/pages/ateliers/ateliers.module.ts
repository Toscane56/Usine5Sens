import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { AteliersPage } from './ateliers';

@NgModule({
  declarations: [
    AteliersPage,
  ],
  imports: [
    IonicPageModule.forChild(AteliersPage),
  ],
})
export class AteliersPageModule {}
