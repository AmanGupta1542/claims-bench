import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators, FormGroup } from '@angular/forms';
import { first } from 'rxjs/operators';
import { Router } from '@angular/router';

import { AuthService } from 'src/app/_services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  loading = false;
  submitted = false;
  returnUrl: string;
  invalid: boolean= false;
  constructor(
    private fb: FormBuilder,
    private router: Router,
    private authService: AuthService
  ) { }

  ngOnInit(): void {
    this.loginForm = this.fb.group({
      username: ['', Validators.required],
      password: ['', Validators.required]
  });
  }

  // convenience getter for easy access to form fields
  get f() { return this.loginForm.controls; }

  onSubmit() {
    this.submitted = true;
    
    // reset alerts on submit
    // this.alertService.clear();

    // stop here if form is invalid
    if (this.loginForm.invalid) {
        return;
    }
    console.log(this.loginForm);
    console.log(this.f);
    this.loading = true;
    // this.authService.login({
    //   username: this.f.username.value, 
    //   password: this.f.password.value})
    //     .pipe(first())
    //     .subscribe(
    //         {
    //           next: (data) => {
    //             this.router.navigate([this.returnUrl]);
    //           },
    //           error: (error) => {
    //               // this.alertService.error(error);
    //               this.loading = false;
    //           }
    //         }
    //       );
  }
}
