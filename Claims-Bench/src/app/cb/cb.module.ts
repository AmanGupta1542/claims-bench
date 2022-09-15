import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CbRoutingModule } from './cb-routing.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { TableModule } from 'primeng/table';
import { ProfileComponent } from './settings/profile/profile.component';
import { ChangePasswordComponent } from './settings/change-password/change-password.component';



@NgModule({
  declarations: [
    DashboardComponent,
    ProfileComponent,
    ChangePasswordComponent
  ],
  imports: [
    CommonModule,
    CbRoutingModule,
    TableModule
  ],
  exports: []
})
export class CbModule { }
