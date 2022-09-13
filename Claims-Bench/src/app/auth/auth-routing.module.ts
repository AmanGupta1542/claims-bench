import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ForgetPasswordComponent } from './forget-password/forget-password.component';

import { LoginComponent } from './login/login.component';
import { LogoutComponent } from './logout/logout.component';
import { RegisterComponent } from './register/register.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';

const routes: Routes = [
  {
    path: '', 
    component: LogoutComponent, 
    data: {}
  },
  { 
    path: 'login', 
    pathMatch: 'full', 
    component: LoginComponent, 
    data: { title: 'Login' } 
  },
  { 
    path: 'register', 
    pathMatch: 'full', 
    component: RegisterComponent, 
    data: { title: 'Register' } 
  },
  { 
    path: 'forget-password', 
    pathMatch: 'full', 
    component: ForgetPasswordComponent, 
    data: { title: 'Forget Password' } 
  },
  { 
    path: 'reset-password', 
    pathMatch: 'full', 
    component: ResetPasswordComponent, 
    data: { title: 'Reset Password' } 
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthRoutingModule { }
