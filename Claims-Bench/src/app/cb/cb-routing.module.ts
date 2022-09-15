import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { DashboardComponent } from './dashboard/dashboard.component';
import { ChangePasswordComponent } from './settings/change-password/change-password.component';
import { ProfileComponent } from './settings/profile/profile.component';

const routes: Routes = [
  {
    path: '', 
    component: DashboardComponent, 
    data: {}
  },
  { 
    path: 'dashboard', 
    pathMatch: 'full', 
    component: DashboardComponent, 
    data: { title: 'Dashboard' } 
  },
  { 
    path: 'profile', 
    pathMatch: 'full', 
    component: ProfileComponent, 
    data: { title: 'Profile' } 
  },
  { 
    path: 'change-password', 
    pathMatch: 'full', 
    component: ChangePasswordComponent, 
    data: { title: 'Change Password' } 
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CbRoutingModule { }
